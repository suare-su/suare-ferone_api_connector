<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for AddReview.
 */
class AddReviewRatingQuery extends AbstractQuery
{
    public const PARAM_ORDER_ID = 'OrderID';
    public const PARAM_REVIEW = 'Review';
    public const PARAM_RATING = 'Rating';
    public const PARAM_PHOTO = 'Photo';

    /**
     * Set OrderID parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setOrderId(int $value): self
    {
        return $this->add(self::PARAM_ORDER_ID, $value);
    }

    /**
     * Set Review parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setReview(string $value): self
    {
        return $this->add(self::PARAM_REVIEW, $value);
    }

    /**
     * Set Rating parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setRating(int $value): self
    {
        return $this->add(self::PARAM_RATING, $value);
    }

    /**
     * Set Photo parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPhoto(string $value): self
    {
        return $this->add(self::PARAM_PHOTO, $value);
    }
}
