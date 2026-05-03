<?php

return [

    /*
    | Subscription billing currency (prices below are in this currency).
    */
    'currency' => 'USD',

    /*
    | Fixed plans: unlimited catalog reading during the period.
    | price_cents = smallest units (USD cents).
    */
    'plans' => [
        '2m' => [
            'label' => '2 months',
            'price_cents' => 100_00,
            'period' => ['months' => 2],
        ],
        '5m' => [
            'label' => '5 months',
            'price_cents' => 200_00,
            'period' => ['months' => 5],
        ],
        '1y' => [
            'label' => '1 year',
            'price_cents' => 500_00,
            'period' => ['years' => 1],
        ],
    ],

    /*
    | Custom: fixed rate per day; reader chooses duration and up to N books.
    */
    'custom' => [
        'price_per_day_cents' => 200,
        'max_books' => 5,
        'min_days' => 1,
        'max_days' => 366,
    ],

];
