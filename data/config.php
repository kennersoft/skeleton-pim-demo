<?php
return [
    'passwordSalt' => 'b90f21a75',
    'database' => [
        'driver' => 'pdo_mysql',
        'host' => 'database_server',
        'port' => '',
        'charset' => 'utf8mb4',
        'dbname' => 'db',
        'user' => 'db',
        'password' => 'db'
    ],
    'recordsPerPage' => 50,
    'recordsPerPageSmall' => 20,
    'lastViewedCount' => 20,
    'decimalMark' => '.',
    'thousandSeparator' => ',',
    'useCache' => false,
    'applicationName' => 'TreoDAM',
    'outboundEmailFromName' => 'KennerCore',
    'smtpPort' => 25,
    'languageList' => [
        0 => 'en_US',
        1 => 'de_DE'
    ],
    'language' => 'en_US',
    'currencyList' => [
        0 => 'EUR'
    ],
    'defaultCurrency' => 'EUR',
    'baseCurrency' => 'EUR',
    'authenticationMethod' => 'Espo',
    'globalSearchEntityList' => [
        0 => 'Asset',
        1 => 'AssetCategory',
        2 => 'Collection',
        3 => 'Association',
        4 => 'Attribute',
        5 => 'AttributeGroup',
        6 => 'Brand',
        7 => 'Category',
        8 => 'Catalog',
        9 => 'Channel',
        10 => 'Product',
        11 => 'ProductFamily'
    ],
    'tabList' => [
        0 => 'Association',
        1 => 'Attribute',
        2 => 'AttributeGroup',
        3 => 'Brand',
        4 => 'Catalog',
        5 => 'Category',
        6 => 'Channel',
        7 => 'Product',
        8 => 'ProductFamily',
        9 => 'PimImage',
        10 => '_delimiter_',
        11 => 'Account',
        12 => 'Contact',
        13 => 'Lead',
        14 => 'Call',
        15 => 'Meeting',
        16 => 'Task',
        17 => 'Asset',
        18 => 'AssetCategory',
        19 => 'Collection'
    ],
    'quickCreateList' => [
        0 => 'Association',
        1 => 'Attribute',
        2 => 'AttributeGroup',
        3 => 'Brand',
        4 => 'Category',
        5 => 'Channel',
        6 => 'Product',
        7 => 'ProductFamily',
        8 => 'Catalog',
        9 => 'Asset',
        10 => 'AssetCategory',
        11 => 'Collection'
    ],
    'theme' => 'TreoDarkTheme',
    'dashboardLayout' => [
        0 => (object) [
            'name' => 'My KennerCore',
            'layout' => [
                
            ]
        ]
    ],
    'webMassUpdateMax' => 200,
    'cronMassUpdateMax' => 3000,
    'developMode' => false,
    'exportDelimiter' => ';',
    'timeZone' => 'UTC',
    'unitsOfMeasure' => (object) [
        'Length' => (object) [
            'unitList' => [
                0 => 'mm',
                1 => 'cm',
                2 => 'dm',
                3 => 'm',
                4 => 'km',
                5 => 'inch'
            ],
            'baseUnit' => 'mm',
            'unitRates' => (object) [
                'cm' => 1,
                'dm' => 1,
                'm' => 1,
                'km' => 1,
                'inch' => 1
            ]
        ],
        'Mass' => (object) [
            'unitList' => [
                0 => 'mg',
                1 => 'g',
                2 => 'kg',
                3 => 'ounces'
            ],
            'baseUnit' => 'mg',
            'unitRates' => (object) [
                'g' => 1,
                'kg' => 1,
                'ounces' => 1
            ]
        ],
        'Time' => (object) [
            'unitList' => [
                0 => 's',
                1 => 'm',
                2 => 'h'
            ],
            'baseUnit' => 's',
            'unitRates' => (object) [
                'm' => 1,
                'h' => 1
            ]
        ],
        'Electric Current' => (object) [
            'unitList' => [
                0 => 'A'
            ],
            'baseUnit' => 'A',
            'unitRates' => [
                
            ]
        ],
        'Thermodynamic Temperature' => (object) [
            'unitList' => [
                0 => 'K',
                1 => 'C'
            ],
            'baseUnit' => 'K',
            'unitRates' => (object) [
                'C' => 1
            ]
        ],
        'Amount Of Substance' => (object) [
            'unitList' => [
                0 => 'mol'
            ],
            'baseUnit' => 'mol',
            'unitRates' => [
                
            ]
        ],
        'Luminous Intensity' => (object) [
            'unitList' => [
                0 => 'cd'
            ],
            'baseUnit' => 'cd',
            'unitRates' => [
                
            ]
        ],
        'Long intervals of time' => (object) [
            'unitList' => [
                0 => 'year',
                1 => 'month',
                2 => 'week',
                3 => 'day'
            ],
            'baseUnit' => 'year',
            'unitRates' => (object) [
                'month' => 1,
                'week' => 1,
                'day' => 1
            ]
        ],
        'File Size' => (object) [
            'unitList' => [
                0 => 'kb'
            ],
            'baseUnit' => 'kb',
            'unitRates' => (object) [
                
            ]
        ]
    ],
    'cacheTimestamp' => 1744989637,
    'twoLevelTabList' => [
        0 => 'Association',
        1 => 'Attribute',
        2 => 'AttributeGroup',
        3 => 'Brand',
        4 => 'Category',
        5 => 'Catalog',
        6 => 'Channel',
        7 => 'ProductFamily',
        8 => 'Product',
        9 => 'KsBadgets',
        10 => 'ItScope',
        11 => 'Icecat',
        12 => 'IcecatFeature',
        13 => (object) [
            'id' => '_delimiter_b367f2d0',
            'name' => 'Konfiguration',
            'color' => '',
            'iconClass' => '',
            'items' => [
                0 => 'KsComponentManagementVue',
                1 => 'KsComponentPriceManagementVue'
            ]
        ],
        14 => 'Asset',
        15 => 'AssetCategory',
        16 => 'Collection',
        17 => 'DistScopeType'
    ],
    'fullTextSearchMinLength' => 4,
    'isInstalled' => true,
    'treoId' => '1cc64ea64af05212eb6a17',
    'isMultilangActive' => true,
    'inputLanguageList' => [
        0 => 'de_DE',
        1 => 'fr_FR',
        2 => 'es_ES',
        3 => 'it_IT',
        4 => 'en_GB'
    ],
    'userThemesDisabled' => false,
    'displayListViewRecordCount' => true,
    'avatarsDisabled' => false,
    'scopeColorsDisabled' => false,
    'tabColorsDisabled' => false,
    'tabIconsDisabled' => false,
    'dashletsOptions' => (object) [
        
    ],
    'exportDisabled' => false,
    'followCreatedEntities' => false,
    'aclStrictMode' => false,
    'textFilterUseContainsForVarchar' => true,
    'aclAllowDeleteCreated' => false,
    'dateFormat' => 'DD.MM.YYYY',
    'timeFormat' => 'HH:mm',
    'addressFormat' => 1,
    'weekStart' => 1,
    'isConnectorsActive' => true,
    'cronPhpVersion' => 'php7.3',
    'dataQuality' => (object) [
        'imagesAllowTypes' => [
            0 => 'image/png',
            1 => 'image/jpeg',
            2 => 'image/gif'
        ],
        'imagesMinCount' => 1,
        'imagesMinWidth' => 600,
        'imagesMinHeight' => 600,
        'imagesMinFileSize' => 10000,
        'imagesMinAspectRatio' => 0.5,
        'imagesMaxWidth' => 1600,
        'imagesMaxHeight' => 1600,
        'imagesMaxFileSize' => 2000000,
        'imagesMaxAspectRatio' => 2
    ],
    'integrations' => (object) [
        'Shopware6-mysql' => true,
        'Shopware6-api' => true
    ],
    'Shopware6MysqlHost' => 'localhost',
    'Shopware6MysqlPort' => 3306,
    'Shopware6MysqlDbname' => 'storepimdemo',
    'Shopware6MysqlUsername' => 'storepimdemo',
    'Shopware6MysqlPassword' => '7NxrAt3URoMj1zzu',
    'isMultilangTabs' => true
];
?>