<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetReviewsList.
 */
class ReviewsListQuery extends PageredQuery
{
    public const PARAM_CITIES_IDS = 'CitiesIDs';
    public const PARAM_SHOPS_IDS = 'ShopsIDs';
    public const PARAM_RATING = 'Rating';
    public const PARAM_REPORT = 'Report';

    /**
     * Set CitiesIDs parameter.
     *
     * @param int[] $value
     *
     * @return $this
     */
    public function setCitiesIDs(array $value): self
    {
        return $this->add(self::PARAM_CITIES_IDS, $value);
    }

    /**
     * Set ShopsIDs parameter.
     *
     * @param int[] $value
     *
     * @return $this
     */
    public function setShopsIDs(array $value): self
    {
        return $this->add(self::PARAM_SHOPS_IDS, $value);
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
     * Set Report parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setReport(bool $value): self
    {
        return $this->add(self::PARAM_REPORT, $value);
    }
}
