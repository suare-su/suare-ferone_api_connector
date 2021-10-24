<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for SendSms.
 */
class SendSmsQuery extends AbstractQuery
{
    public const PARAM_PHONE = 'Phone';
    public const PARAM_MESSAGE = 'Message';

    /**
     * Set Phone parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPhone(string $value): self
    {
        return $this->add(self::PARAM_PHONE, $value);
    }

    /**
     * Set Message parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setMessage(string $value): self
    {
        return $this->add(self::PARAM_MESSAGE, $value);
    }
}
