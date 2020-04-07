<?php
/**
 * This file is part of EspoCRM and/or TreoCore, and/or KennerCore.
 *
 * EspoCRM - Open Source CRM application.
 * Copyright (C) 2014-2020 Yuri Kuznetsov, Taras Machyshyn, Oleksiy Avramenko
 * Website: http://www.espocrm.com
 *
 * TreoCore is EspoCRM-based Open Source application.
 * Copyright (C) 2017-2020 TreoLabs GmbH
 * Website: https://treolabs.com
 *
 * KennerCore is TreoCore-based Open Source application.
 * Copyright (C) 2020 Kenner Soft Service GmbH
 * Website: https://kennersoft.de
 *
 * KennerCore as well as TreoCore and EspoCRM is free software:
 * you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * KennerCore as well as TreoCore and EspoCRM is distributed in the hope that
 * it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with EspoCRM. If not, see http://www.gnu.org/licenses/.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of
 * the "KennerCore", "EspoCRM" and "TreoCore" words.
 */

// create app
$app = new \Treo\Core\Application();

// set demo data to DB
$app
    ->getContainer()
    ->get('entityManager')
    ->nativeQuery(file_get_contents('data/demo-data.sql'));
unlink('data/demo-data.sql');

// set demo data to config
$config = $app->getContainer()->get('config');
$config->set('isMultilangActive', true);
$config->set('inputLanguageList', ['de_DE', 'en_US']);
$config->set('globalSearchEntityList', []);
$config->set(
    'tabList', [
        'Association',
        'Attribute',
        'AttributeGroup',
        'Brand',
        'Catalog',
        'Category',
        'Channel',
        'Product',
        'ProductFamily',
        'PimImage',
        '_delimiter_',
        'Account',
        'Contact',
        'Lead',
        'Call',
        'Meeting',
        'Task'
    ]
);
$config->set(
    'quickCreateList', [
        'Association',
        'Attribute',
        'AttributeGroup',
        'Brand',
        'Category',
        'Channel',
        'Product',
        'ProductFamily',
        'Catalog'
    ]
);
$unitsOfMeasure = [
    'Length'                    => [
        'unitList'  => [
            'mm',
            'cm',
            'dm',
            'm',
            'km',
            'inch'
        ],
        'baseUnit'  => 'mm',
        'unitRates' => [
            'cm'   => 1,
            'dm'   => 1,
            'm'    => 1,
            'km'   => 1,
            'inch' => 1
        ]
    ],
    'Mass'                      => [
        'unitList'  => [
            'mg',
            'g',
            'kg',
            'ounces'
        ],
        'baseUnit'  => 'mg',
        'unitRates' => [
            'g'      => 1,
            'kg'     => 1,
            'ounces' => 1
        ]
    ],
    'Time'                      => [
        'unitList'  => [
            's',
            'm',
            'h'
        ],
        'baseUnit'  => 's',
        'unitRates' => [
            'm' => 1,
            'h' => 1
        ]
    ],
    'Electric Current'          => [
        'unitList'  => [
            'A'
        ],
        'baseUnit'  => 'A',
        'unitRates' => []
    ],
    'Thermodynamic Temperature' => [
        'unitList'  => [
            'K',
            'C'
        ],
        'baseUnit'  => 'K',
        'unitRates' => [
            'C' => 1
        ]
    ],
    'Amount Of Substance'       => [
        'unitList'  => [
            'mol'
        ],
        'baseUnit'  => 'mol',
        'unitRates' => []
    ],
    'Luminous Intensity'        => [
        'unitList'  => [
            'cd'
        ],
        'baseUnit'  => 'cd',
        'unitRates' => []
    ],
    'Long intervals of time'    => [
        'unitList'  => [
            'year',
            'month',
            'week',
            'day'
        ],
        'baseUnit'  => 'year',
        'unitRates' => [
            'month' => 1,
            'week'  => 1,
            'day'   => 1
        ]
    ],
];
$config->set('unitsOfMeasure', json_decode(json_encode($unitsOfMeasure)));
$config->save();

// get data
$data = json_decode(file_get_contents('composer.json'), true);

// prepare
$data['require']['kennersoft/kennercore'] = '^3.25.10';
$data['require']['kennersoft/pim'] = '*';

// save new composer data
file_put_contents('composer.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

// copy to stable
copy('composer.json', 'data/stable-composer.json');

// drop cache
\Treo\Core\Utils\Util::removeDir('data/cache');
