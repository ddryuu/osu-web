<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

use App\Models\Country;
use App\Models\User;
use App\Models\UserAccountHistory;

$factory->define(User::class, function (Faker\Generator $faker) {
    $existing_users = DB::table('phpbb_users')->get();

    $existing_names = [];
    $existing_ids = [];

    foreach ($existing_users as $existing_usr) {
        $existing_ids[] = $existing_usr->user_id;
        $existing_names[] = $existing_usr->username;
    }

    $username = null;
    $userid = null;

    // Check username doesn't already exist
    while ($username === null) {
        if (!in_array($uname = $faker->userName, $existing_names, true)) {
            $username = substr(str_replace('.', ' ', $uname), 0, 15); // remove fullstops from username
        }
    }

    // Generate a random unique ID
    $userid = null;
    while ($userid === null) {
        if (!in_array($uid = rand(2, 600000), $existing_ids, true)) {
            $userid = $uid;
        }
    }

    // Get a random country (or blank)
    $countryAcronym = array_rand_val(Country::pluck('acronym')) ?? '';

    // cache password hash to speed up tests (by not repeatedly calculating the same hash over and over)
    static $password = null;
    if ($password === null) {
        $password = password_hash(md5('password'), PASSWORD_BCRYPT);
    }

    return [
        'username' => $username,
        'user_id' => $userid,
        'user_password' => $password,
        'user_email' => $faker->safeEmail,
        'user_lastvisit' => time(),
        'user_posts' => rand(1, 500),
        'user_warnings' => 0,
        'user_type' => 0,
        'osu_kudosavailable' => rand(1, 500),
        'osu_kudosdenied' => rand(1, 500),
        'osu_kudostotal' => rand(1, 500),
        'country_acronym' => $countryAcronym,
        'osu_playstyle' => [array_rand(User::PLAYSTYLES)],
        'user_website' => 'http://www.google.com/',
        'user_twitter' => 'ppy',
        'user_permissions' => '',
        'user_interests' => substr($faker->bs, 30),
        'user_occ' => substr($faker->catchPhrase, 30),
        'user_sig' => function () use ($faker) {
            // avoids running if user_sig is supplied.
            return $faker->realText(155);
        },
        'user_from' => substr($faker->country, 30),
        'user_regdate' => $faker->dateTimeBetween('-6 years', 'now'),
    ];
});

foreach (['admin', 'bng', 'gmt', 'nat'] as $identifier) {
    $attribs = ['group_id' => app('groups')->byIdentifier($identifier)->getKey()];

    $factory->state(User::class, $identifier, function () use ($attribs) {
        return $attribs;
    });

    $factory->afterCreatingState(User::class, $identifier, function ($user) use ($attribs) {
        $user->userGroups()->create($attribs);
    });
}

$factory->state(User::class, 'restricted', function (Faker\Generator $faker) {
    return [
        'user_warnings' => 1,
    ];
});

$factory->afterCreatingState(User::class, 'with_note', function ($user, $faker) {
    $user->accountHistories()->save(
        factory(UserAccountHistory::class)->states('note')->make()
    );
});

$factory->afterCreatingState(User::class, 'restricted', function ($user, $faker) {
    $user->accountHistories()->save(
        factory(UserAccountHistory::class)->states('restriction')->make()
    );
});

$factory->afterCreatingState(User::class, 'silenced', function ($user, $faker) {
    $user->accountHistories()->save(
        factory(UserAccountHistory::class)->states('silence')->make()
    );
});
