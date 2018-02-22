<?php

namespace CultuurNet\UDB3\Model\Offer;

use CultuurNet\UDB3\Model\ValueObject\Audience\AgeRange;
use CultuurNet\UDB3\Model\ValueObject\Contact\BookingInfo;
use CultuurNet\UDB3\Model\ValueObject\Contact\ContactPoint;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Moderation\WorkflowStatus;
use CultuurNet\UDB3\Model\ValueObject\Price\PriceInfo;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Label\Labels;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedDescription;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Languages;

interface Offer
{
    /**
     * @return UUID
     */
    public function getId();

    /**
     * @return Language
     */
    public function getMainLanguage();

    /**
     * @return TranslatedTitle
     */
    public function getTitle();

    /**
     * @return TranslatedDescription|null
     */
    public function getDescription();

    /**
     * @return Categories
     */
    public function getTerms();

    /**
     * @return Labels
     */
    public function getLabels();

    /**
     * @return AgeRange|null
     */
    public function getAgeRange();

    /**
     * @return PriceInfo|null
     */
    public function getPriceInfo();

    /**
     * @return BookingInfo
     */
    public function getBookingInfo();

    /**
     * @return ContactPoint
     */
    public function getContactPoint();

    /**
     * @return WorkflowStatus
     */
    public function getWorkflowStatus();
}
