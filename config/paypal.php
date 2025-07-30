<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PayPal API Credentials
    |--------------------------------------------------------------------------
    |
    | حفظ مفاتيح PayPal الخاصة بك هنا باستخدام متغيرات البيئة (.env).
    |
    */

    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'secret' => env('PAYPAL_SECRET', ''),

    // رابط API الخاص بـ PayPal، افتراضيًا رابط sandbox (بيئة الاختبار)
    'api_url' => env('PAYPAL_API_URL', 'https://api-m.sandbox.paypal.com'),

];
