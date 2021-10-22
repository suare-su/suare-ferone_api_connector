<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Object that represents params for method in connector.
 *
 * @psalm-consistent-constructor
 */
class AbstractQuery implements Query
{
    private array $params = [];

    /**
     * Add new param with set name and value.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    protected function add(string $name, $value): self
    {
        $this->params[trim($name)] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Create and return new instance of query.
     *
     * @return static
     */
    public static function new(): self
    {
        return new static();
    }
}
