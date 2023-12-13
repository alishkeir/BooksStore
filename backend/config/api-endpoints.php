<?php

return [
    'book24' => [
        'product_feed' => env('BOOK24_PRODUCT_FEED', 'https://book24.hu/product-feed'),
        'stock_info' => env('BOOK24_STOCK_INFO', 'https://www.book24.hu/cache/stock_info.xml'),
        'stock_info_xml_name' => env('BOOK24_STOCK_INFO_XML_NAME', 'book24_stock.xml'),
    ],

    'dibook' => [
        'product_feed' => env('DIBOOK_PRODUCT_FEED', 'https://dibook.hu/api/book/list?alomgyar'),
        'product_feed_name' => env('DIBOOK_PRODUCT_FEED', 'dibook_info.xml'),
    ],
];
