# Ferone API Connector

[![Latest Stable Version](https://poser.pugx.org/suare-su/suare-ferone_api_connector/v/stable.png)](https://packagist.org/packages/suare-su/suare-ferone_api_connector)
[![License](https://poser.pugx.org/suare-su/suare-ferone_api_connector/license.svg)](https://packagist.org/packages/suare-su/suare-ferone_api_connector)
[![Build Status](https://github.com/suare-su/suare-ferone_api_connector/workflows/ferone_api_connector/badge.svg)](https://github.com/suare-su/suare-ferone_api_connector/actions?query=workflow%3A%22ferone_api_connector%22)



## Установка

Устанавливается с помощью [composer](https://getcomposer.org/).

```bash
composer req suare-su/suare-ferone_api_connector
```

Отдельно необходимо установить какой-либо PSR-совместимый http клиент, если в проекте еще нет такого. Например, [guzzle http](https://docs.guzzlephp.org/en/stable/).

```bash
composer req guzzlehttp/guzzle
```



## Использование

Использование библиотеки совместно с guzzle http:

```php
use GuzzleHttp\Client;
use SuareSu\FeroneApiConnector\Connector\Connector;
use SuareSu\FeroneApiConnector\Transport\TransportFactory;

// инициируем клиент
$guzzleClient = new Client();

// создаем транспорт с помощью фабрики
$transport = TransportFactory::new()
    ->setUrl('http://api.url/api/v2')
    ->setAuthKey('api_auth_key')
    ->createForGuzzleClient($guzzleClient);

// создаем коннектор и передаем в него объект транспорта
$connector = new Connector($transport);

// коннектор готов к использованию
$connector->pingApi();
```
