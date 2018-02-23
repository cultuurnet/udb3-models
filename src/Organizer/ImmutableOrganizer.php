<?php

namespace CultuurNet\UDB3\Model\Organizer;

use CultuurNet\UDB3\Model\ValueObject\Contact\ContactPoint;
use CultuurNet\UDB3\Model\ValueObject\Contact\Url;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;

class ImmutableOrganizer implements Organizer
{
    /**
     * @var UUID
     */
    private $id;

    /**
     * @var Language
     */
    private $mainLanguage;

    /**
     * @var TranslatedTitle
     */
    private $name;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var TranslatedAddress|null
     */
    private $address;

    /**
     * @var ContactPoint
     */
    private $contactPoint;

    /**
     * @param UUID $id
     * @param Language $mainLanguage
     * @param TranslatedTitle $name
     * @param Url $url
     */
    public function __construct(
        UUID $id,
        Language $mainLanguage,
        TranslatedTitle $name,
        Url $url
    ) {
        $this->id = $id;
        $this->mainLanguage = $mainLanguage;
        $this->name = $name;
        $this->url = $url;

        $this->contactPoint = new ContactPoint();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getMainLanguage()
    {
        return $this->mainLanguage;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param TranslatedTitle $name
     * @return ImmutableOrganizer
     */
    public function withName(TranslatedTitle $name)
    {
        $c = clone $this;
        $c->name = $name;
        $c->mainLanguage = $name->getOriginalLanguage();
        return $c;
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Url $url
     * @return ImmutableOrganizer
     */
    public function withUrl(Url $url)
    {
        $c = clone $this;
        $c->url = $url;
        return $c;
    }

    /**
     * @return TranslatedAddress|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param TranslatedAddress $address
     * @return ImmutableOrganizer
     */
    public function withAddress(TranslatedAddress $address)
    {
        $c = clone $this;
        $c->address = $address;
        return $c;
    }

    /**
     * @return ImmutableOrganizer
     */
    public function withoutAddress()
    {
        $c = clone $this;
        $c->address = null;
        return $c;
    }

    /**
     * @return ContactPoint
     */
    public function getContactPoint()
    {
        return $this->contactPoint;
    }

    /**
     * @param ContactPoint $contactPoint
     * @return ImmutableOrganizer
     */
    public function withContactPoint(ContactPoint $contactPoint)
    {
        $c = clone $this;
        $c->contactPoint = $contactPoint;
        return $c;
    }
}