<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

use InvalidArgumentException;

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
        $this->params[$this->unifyParamName($name)] = $value;

        return $this;
    }

    /**
     * Add new value to array with set name.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    protected function addToArray(string $name, $value): self
    {
        $name = $this->unifyParamName($name);
        if (!isset($this->params[$name])) {
            $this->params[$name] = [];
        } elseif (!\is_array($this->params[$name])) {
            $message = "Param {$name} is not an array";
            throw new InvalidArgumentException($message);
        }

        $this->params[$name][] = $value;

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

    /**
     * Convert param name to unified form.
     *
     * @param string $name
     *
     * @return string
     */
    private function unifyParamName(string $name): string
    {
        return trim($name);
    }
}
