<?php

return [

    'key' => env('RAZORPAY_KEY'),

    'secret' => env('RAZORPAY_SECRET'),

    /*
    | Optional override. If empty, checkout uses the order's currency (from your store).
    */
    'default_currency' => env('RAZORPAY_CURRENCY'),

    /*
    | Shipped in payment link notes (e.g. FinvyPay uses "finvypay").
    */
    'notes_source' => env('RAZORPAY_NOTES_SOURCE', 'bookqueue'),

];
