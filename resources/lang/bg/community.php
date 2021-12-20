<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

return [
    'support' => [
        'convinced' => [
            'title' => 'Убеден съм! :D',
            'support' => 'подкрепи osu!',
            'gift' => 'или подари osu!support на друг играч',
            'instructions' => 'кликни върху сърцето за продължаване към osu!store',
        ],
        'why-support' => [
            'title' => 'Защо да подкрепя osu!? Къде отиват парите?',

            'team' => [
                'title' => 'Подкрепяте нашия екип',
                'description' => 'Малък екип от разработчици развиват и поддържат osu!. Вашата подкрепа им помага, нали знаете... да живеят.',
            ],
            'infra' => [
                'title' => 'Сървърна инфраструктура',
                'description' => 'Приносите са насочени за поддържка към сървърите на уебсайта, мултиплейър услугите ни, онлайн класациите и т.н.',
            ],
            'featured-artists' => [
                'title' => 'Представени автори',
                'description' => 'С ваша подкрепа, може да се обърнем към страхотни изпълнители и лицензираме уникална музика за използване в osu!',
                'link_text' => 'Вижте текущия списък &raquo;',
            ],
            'ads' => [
                'title' => 'Вие издържате osu!',
                'description' => 'Приносите помагат да запазим играта независима, без никакви реклами и външни спонсори.',
            ],
            'tournaments' => [
                'title' => 'Официални турнири',
                'description' => 'Финансово помагате за официалните osu! World Cup турнири (тяхното организиране и награди).',
                'link_text' => 'Разгледайте турнирите &raquo;',
            ],
            'bounty-program' => [
                'title' => 'Open Source Bounty Program',
                'description' => 'Подкрепяте сътрудниците на общността, които отделят своето време и усилия, да направят osu! по-добър.',
                'link_text' => 'Разберете повече &raquo;',
            ],
        ],
        'perks' => [
            'title' => 'Чудесно! А какво получавам?',
            'osu_direct' => [
                'title' => 'osu!direct',
                'description' => 'Бърз и лесен достъп за търсене и сваляне на бийтмапове, без да излизате от играта.',
            ],

            'friend_ranking' => [
                'title' => 'Приятелско класиране',
                'description' => "Как се класирате спрямо приятелите си в класацията на бийтмапове, както в играта така и уебсайта.",
            ],

            'country_ranking' => [
                'title' => 'Държавно класиране',
                'description' => 'Завладейте страната си преди да завладеете света.',
            ],

            'mod_filtering' => [
                'title' => 'Подреждане по модове',
                'description' => 'Как се класирате спрямо HDHR играчи? Няма проблем!',
            ],

            'auto_downloads' => [
                'title' => 'Автоматично изтегляне',
                'description' => 'Бийтмаповете ще се изтеглят автоматично в мултиплейър игри, докато наблюдавате играч или кликнете върху връзка в чата!',
            ],

            'upload_more' => [
                'title' => 'Повече качване',
                'description' => 'Допълнителни слотове за чакащи класиране бийтмапове (на класиран бийтмап) до максимум 10.',
            ],

            'early_access' => [
                'title' => 'Ранен достъп',
                'description' => 'Получи ранен достъп до нови osu! версии, с нови функции преди да станат публични!<br/><br/>Това включва и нови функции в уебсайта!',
            ],

            'customisation' => [
                'title' => 'Персонализация',
                'description' => "Зашемети останалите със своя собствена корица или напълно персонализирана страница 'за мен'!",
            ],

            'beatmap_filters' => [
                'title' => 'Бийтмап подреждане',
                'description' => 'Подреди бийтмап търсенията по изиграни/неизиграни или постигнат ранг.',
            ],

            'yellow_fellow' => [
                'title' => 'Златен колега',
                'description' => 'Отличи се в играта със своето златножълто потребителско име.',
            ],

            'speedy_downloads' => [
                'title' => 'Бързо изтегляне',
                'description' => 'По-леки ограничения за бързината на изтегляне, особено при използване на osu!direct.',
            ],

            'change_username' => [
                'title' => 'Промяна на име',
                'description' => 'Една безплатна промяна на име при първото закупуване на osu!supporter.',
            ],

            'skinnables' => [
                'title' => 'Персонализиране',
                'description' => 'Допълнителни персонализации в играта, като например фон в главното меню.',
            ],

            'feature_votes' => [
                'title' => 'Гласуване за функции',
                'description' => 'Възможност да се гласува за предложени функции! (2 на месец)',
            ],

            'sort_options' => [
                'title' => 'Опции за подреждане',
                'description' => 'Способност да разгледате бийтмап класациите по държава / приятели / мод-специфични по време на игра.',
            ],

            'more_favourites' => [
                'title' => 'Повече любими',
                'description' => 'Максималният брой бийтмапове, които може да се отбележат като любими е увеличен от :normally &rarr; :supporter ',
            ],
            'more_friends' => [
                'title' => 'Повече приятели',
                'description' => 'Максималният брой приятели, които може да имате е увеличен от :normally &rarr; :supporter',
            ],
            'more_beatmaps' => [
                'title' => 'Качване на повече бийтмапове',
                'description' => 'Максималният брой изчакващи бийтмапове, които може да имате, се изчислява от обичайната стойност плюс допълнителен бонус за всеки в момента класиран бийтмап (до определен лимит).<br/><br/>Обикновено, това е :base плюс :bonus за класиран бийтмап (до максимум :bonus_max). С osu!supporter, това се увеличава до :supporter_base плюс :supporter_bonus за класиран бийтмап (до максимум :supporter_bonus_max).',
            ],
            'friend_filtering' => [
                'title' => 'Приятелски класации',
                'description' => 'Съревновавай се с приятели и виж как се класирате сред тях!',
            ],

        ],
        'supporter_status' => [
            'contribution' => 'Благодарим за вашата подкрепа! Допринесохте общо :dollars за :tags osu!supporter покупки!',
            'gifted' => "Дадохте :giftedTags от покупките като подарък (общо :giftedDollars струващ), колко щедро!",
            'not_yet' => "Никога не сте имали osu!supporter :(",
            'valid_until' => 'Вашият osu!supporter е валиден до :date!',
            'was_valid_until' => 'Вашият osu!supporter беше валиден преди :date.',
        ],
    ],
];
