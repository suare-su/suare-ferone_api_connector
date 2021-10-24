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
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->city = (string) ($apiResponse['city'] ?? null);
        $this->delivery = new ClientAddrsDelivery($apiResponse['delivery'] ?? []);
        $this->client = isset($apiResponse['client']) ? new ClientAddrsClient($apiResponse['client']) : null;
        $this->addrs = [];
        foreach (($apiResponse['addrs'] ?? []) as $tmpItem) {
            $this->addrs[] = new ClientAddrsAddrs(\is_array($tmpItem) ? $tmpItem : []);
        }
        $this->shops = [];
        foreach (($apiResponse['shops'] ?? []) as $tmpItem) {
            $this->shops[] = new ShopSelected(\is_array($tmpItem) ? $tmpItem : []);
        }
        $this->list = [];
        foreach (($apiResponse['list'] ?? []) as $tmpItem) {
            $this->list[] = new OrderProduct(\is_array($tmpItem) ? $tmpItem : []);
        }
    }
}
