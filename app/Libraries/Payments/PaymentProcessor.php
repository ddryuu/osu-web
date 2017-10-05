<?php

/**
 *    Copyright 2015-2017 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Libraries\Payments;

use App\Events\Fulfillments\PaymentEvent;
use App\Events\Fulfillments\ProcessorValidationFailed;
use App\Exceptions\InvalidSignatureException;
use App\Exceptions\ModelNotSavedException;
use App\Libraries\OrderNumber;
use App\Models\Store\Order;
use App\Models\Store\Payment;
use App\Traits\Validatable;
use Carbon\Carbon;
use DB;

abstract class PaymentProcessor implements \ArrayAccess
{
    use Validatable;

    protected $order;
    protected $params;
    protected $signature;
    protected $orderNumber;

    public function __construct(array $params, PaymentSignature $signature)
    {
        \Log::debug($params);

        $this->params = $params;
        $this->signature = $signature;

        $this->orderNumber = new OrderNumber($this->getOrderNumber());
    }

    /**
     * Gets the string that corresponds to an internal Order id.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderNumber->getOrderId();
    }


    /**
     * Gets a more friendly identifying order number string that represents an Order.
     *
     * @return string
     */
    abstract public function getOrderNumber();

    /**
     * string representing the payment provider.
     *
     * @return string
     */
    abstract public function getPaymentProvider();

    /**
     * Transaction ID returned by the payment provider.
     *
     * @return string
     */
    abstract public function getPaymentTransactionId();

    /**
     * Gets the transaction ID for the payment tagged with the payment processor used..
     * Transaction IDs should be unique scoped to the payment processor.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return "{$this->getPaymentProvider()}-{$this->getPaymentTransactionId()}";
    }

    /**
     * Gets the payment amount given by the payment provider.
     *
     * @return float
     */
    abstract public function getPaymentAmount();

    /**
     * Gets the payment date given by the payment provider.
     *
     * @return Carbon\Carbon
     */
    abstract public function getPaymentDate();

    /**
     * Validates the transaction.
     * Returns true if the transaction is valid; false, otherwise.
     *
     * @return bool
     */
    abstract public function validateTransaction();

    /**
     * Gets the type of payment notification.
     *
     * @return string
     */
    abstract public function getNotificationType();

    /**
     * Tells callers to ignore this notification.
     */
    public function isSkipped()
    {
        return false;
    }

    /**
     * Auto run apply() or cancel() depending on the notification type.
     *
     * @return void
     * @throws UnsupportedNotificationTypeException thrown if the notification type is unsupported.
     */
    public function run()
    {
        $type = $this->getNotificationType();
        switch ($type) {
            case NotificationType::PAYMENT:
                $this->apply();
                break;
            case NotificationType::REFUND:
                $this->cancel();
                break;
            default:
                throw new UnsupportedNotificationTypeException($type);
        }
    }

    /**
     * Processes the payment transaction.
     *
     * @return void
     */
    public function apply()
    {
        if (!$this->validateTransaction()) {
            $this->throwValidationFailed(new PaymentProcessorException($this->validationErrors()));
        }

        DB::connection('mysql-store')->transaction(function () {
            $order = $this->getOrder();

            // Using a unique constraint, so we don't need to lock any rows.
            $payment = new Payment();
            $payment->provider = $this->getPaymentProvider();
            $payment->transaction_id = $this->getPaymentTransactionId();
            $payment->paid_at = $this->getPaymentDate();

            if (!$order->payments()->save($payment)) {
                throw new ModelNotSavedException('failed saving model');
            }

            $order->paid($payment);

            $eventName = "store.payments.completed.{$payment->provider}";
            event($eventName, new PaymentEvent($order));
        });
    }

    /**
     * Cancels the payment transaction.
     *
     * @return void
     */
    public function cancel()
    {
        if (!$this->validateTransaction()) {
            $this->throwValidationFailed(new PaymentProcessorException($this->validationErrors()));
        }

        DB::connection('mysql-store')->transaction(function () {
            $order = $this->getOrder();
            $payment = $order->payments->where('cancelled', false)->first();
            $payment->cancel();

            $order->cancel();

            $eventName = "store.payments.cancelled.{$payment->provider}";
            event($eventName, new PaymentEvent($order));
        });
    }

    public function ensureValidSignature()
    {
        // TODO: post many warnings
        if (!$this->signature->isValid()) {
            $this->validationErrors()->add('header.signature', '.signature.not_match');
            $this->throwValidationFailed(new InvalidSignatureException());
        }
    }

    /**
     * Sends a ValidationFailedEvent with the validation errors.
     *
     * @return void
     */
    protected function dispatchValidationFailed()
    {
        event(
            "store.payments.validation.failed.{$this->getPaymentProvider()}",
            new ProcessorValidationFailed($this, $this->validationErrors())
        );
    }

    /**
     * Fetches the Order corresponding to this payment and memoizes it.
     *
     * @return Order
     */
    protected function getOrder()
    {
        if (!isset($this->order)) {
            $this->order = Order::withPayments()->find($this->getOrderId());
        }

        return $this->order;
    }

    /**
     * Convenience method that calls dispatchValidationFailed() and then throws the supplied exception.
     *
     * @param Exception $exception
     * @return void
     */
    protected function throwValidationFailed(\Exception $exception)
    {
        $this->dispatchValidationFailed();
        throw $exception;
    }

    /**
     * implements ArrayAccess.
     */
    public function offsetExists($key)
    {
        return array_has($this->params, $key);
    }

    public function offsetGet($key)
    {
        return data_get($this->params, $key);
    }

    public function offsetSet($key, $value)
    {
        throw new \BadMethodCallException('not supported');
    }

    public function offsetUnset($key)
    {
        throw new \BadMethodCallException('not supported');
    }

    /**
     * Validatable.
     */
    public function validationErrorsTranslationPrefix()
    {
        return 'payments';
    }

    public function validationErrorsKeyBase()
    {
        return 'model_validation/';
    }
}
