<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Marvin255\FileSystemHelper\FileSystemFactory;
use SuareSu\FeroneApiConnector\Generator\EntitesGenerator;
use SuareSu\FeroneApiConnector\Generator\RemoteSwaggerExtractor;

require_once __DIR__ . '/../vendor/autoload.php';

$guzzleClient = new Client();
$extractor = new RemoteSwaggerExtractor($guzzleClient);
$rawEntites = $extractor->extractFrom('https://api.swaggerhub.com/apis/idmitrio/ferone-api/2.0');

$rawEntites['FindCitiesResponse'] = [
    'type' => 'object',
    'required' => ['id', 'label', 'value'],
    'properties' => [
        'id' => ['type' => 'string'],
        'label' => ['type' => 'string'],
        'value' => ['type' => 'string'],
    ],
];
$rawEntites['FindStreetsResponse'] = [
    'type' => 'object',
    'required' => ['id', 'city', 'label', 'value'],
    'properties' => [
        'id' => ['type' => 'string'],
        'city' => ['type' => 'string'],
        'label' => ['type' => 'string'],
        'value' => ['type' => 'string'],
    ],
];
$rawEntites['FindHousesResponse'] = [
    'type' => 'object',
    'required' => ['id', 'addr', 'label', 'value'],
    'properties' => [
        'id' => ['type' => 'string'],
        'addr' => ['type' => 'string'],
        'label' => ['type' => 'string'],
        'value' => ['type' => 'string'],
    ],
];

$fsHelper = FileSystemFactory::create();
$entitiesGenerator = new EntitesGenerator(
    $fsHelper,
    [
        'City',
        'Shop',
        'Product',
        'Group',
        'GroupModifier',
        'MenuItem',
        'Client',
        'Order',
        'OrderProduct',
        'OrderChange',
        'OrderSourceType',
        'OrderStatus',
        'Review',
        'ReviewQuestion',
        'FindCitiesResponse',
        'FindStreetsResponse',
        'FindHousesResponse',
        'ClientAddrs',
        'ShopSelected',
        'OrderFinal',
        'OrderListItem',
        'OrderListItemMod',
    ],
    [
        'OrderListItem',
        'OrderListItemMod',
    ]
);
$entitiesGenerator->generate(
    $rawEntites,
    new SplFileInfo(__DIR__ . '/../src/Entity'),
    '\\SuareSu\\FeroneApiConnector\\Entity'
);
