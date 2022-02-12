<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Marvin255\FileSystemHelper\FileSystemFactory;
use SuareSu\FeroneApiConnector\Generator\EntitesGenerator;
use SuareSu\FeroneApiConnector\Generator\RemoteSwaggerExtractor;

require_once __DIR__ . '/../vendor/autoload.php';

$guzzleClient = new Client();
$extractor = new RemoteSwaggerExtractor($guzzleClient);
$rawEntites = $extractor->extractFrom('https://api.swaggerhub.com/apis/idmitrio/ferone-api/2.1');

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
$rawEntites['BindedClient'] = [
    'type' => 'object',
    'required' => ['OrderID', 'ClientID'],
    'properties' => [
        'OrderID' => ['type' => 'integer'],
        'ClientID' => ['type' => 'integer'],
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
        'BindClientInfoAddrInfo',
        'BindClientIdAddrInfo',
        'BindClientInfoShopId',
        'BindClientIdShopId',
        'ClientInfo',
        'AddrInfo',
        'BindedClient',
    ],
    [
        'OrderListItem',
        'OrderListItemMod',
        'BindClientInfoAddrInfo',
        'BindClientIdAddrInfo',
        'BindClientInfoShopId',
        'BindClientIdShopId',
        'ClientInfo',
        'AddrInfo',
    ]
);
$entitiesGenerator->generate(
    $rawEntites,
    new SplFileInfo(__DIR__ . '/../src/Entity'),
    '\\SuareSu\\FeroneApiConnector\\Entity'
);
