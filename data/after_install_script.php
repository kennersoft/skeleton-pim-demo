<?php

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
$config->save();

// get data
$data = json_decode(file_get_contents('composer.json'), true);

// prepare
$data['require']['treolabs/treocore'] = '^3.25.10';
$data['require']['treolabs/pim'] = '*';

// save new composer data
file_put_contents('composer.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

// copy to stable
copy('composer.json', 'data/stable-composer.json');

// drop cache
\Treo\Core\Utils\Util::removeDir('data/cache');
