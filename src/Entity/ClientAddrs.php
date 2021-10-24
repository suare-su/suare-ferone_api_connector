<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ClientAddrs
{
    /** Название города */
    private string $city;

    /** Информация о доставке для заказа */
    private ClientAddrsDelivery $delivery;

    /** Информация о клиенте, передается если указан Phone */
    private ?ClientAddrsClient $client;

    /**
     * Список прошлых адресов доставки клиента, передается если указан Phone и доставка доступна.
     *
     * @var ClientAddrsAddrs[]
     */
    private array $addrs;

    /**
     * Доступные для заказа заведения.
     *
     * @var ShopSelected[]
     */
    private array $shops;

    /**
     * Список элементов заказа.
     *
     * @var OrderProduct[]
     */
    private array $list;

    public function getCity(): string
    {
        return $this->city;
    }

    public function getDelivery(): ClientAddrsDelivery
    {
        return $this->delivery;
    }

    public function getClient(): ?ClientAddrsClient
    {
        return $this->client;
    }

    /**
     * @return ClientAddrsAddrs[]
     */
    public function getAddrs(): array
    {
        return $this->addrs;
    }

    /**
     * @return ShopSelected[]
     */
    public function getShops(): array
    {
        return $this->shops;
    }

    /**
     * @return OrderProduct[]
     */
    public function getList(): array
    {
        return $this->list;
    }

    public function __construct(array $apiResponse)
    {
        $this->city = (string) ($apiResponse['City'] ?? null);
        $this->delivery = new ClientAddrsDelivery($apiResponse['Delivery'] ?? []);
        $this->client = isset($apiResponse['Client']) ? new ClientAddrsClient($apiResponse['Client']) : null;
        $this->addrs = [];
        foreach (($apiResponse['Addrs'] ?? []) as $tmpItem) {
            $this->addrs[] = new ClientAddrsAddrs(\is_array($tmpItem) ? $tmpItem : []);
        }
        $this->shops = [];
        foreach (($apiResponse['Shops'] ?? []) as $tmpItem) {
            $this->shops[] = new ShopSelected(\is_array($tmpItem) ? $tmpItem : []);
        }
        $this->list = [];
        foreach (($apiResponse['List'] ?? []) as $tmpItem) {
            $this->list[] = new OrderProduct(\is_array($tmpItem) ? $tmpItem : []);
        }
    }
}
