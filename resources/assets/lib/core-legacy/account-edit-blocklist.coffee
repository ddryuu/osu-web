# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { trans } from 'utils/lang'

export default class AccountEditBlocklist
  element: 'block-list__content'
  jsClass: '.js-account-edit-blocklist'

  constructor: ->
    $(document).on 'click', @jsClass, @toggle
    $.subscribe 'user:update', @updateBlockCount


  updateBlockCount: =>
    return unless currentUser.id?

    $("#{@jsClass}-count").text trans('users.blocks.blocked_count', count: currentUser.blocks?.length ? 0)


  toggle: (e) =>
    e.preventDefault()

    $(".#{@element}").toggleClass('hidden')

    label =
      if $(".#{@element}").hasClass('hidden')
        trans 'common.buttons.show'
      else
        trans 'common.buttons.hide'

    $(@jsClass).text label
