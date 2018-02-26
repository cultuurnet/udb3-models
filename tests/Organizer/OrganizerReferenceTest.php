<?php

namespace CultuurNet\UDB3\Model\Organizer;

use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Contact\Url;
use CultuurNet\UDB3\Model\ValueObject\Geography\Address;
use CultuurNet\UDB3\Model\ValueObject\Geography\CountryCode;
use CultuurNet\UDB3\Model\ValueObject\Geography\Locality;
use CultuurNet\UDB3\Model\ValueObject\Geography\PostalCode;
use CultuurNet\UDB3\Model\ValueObject\Geography\Street;
use CultuurNet\UDB3\Model\ValueObject\Geography\TranslatedAddress;
use CultuurNet\UDB3\Model\ValueObject\Identity\UUID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Categories;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\Category;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryDomain;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryID;
use CultuurNet\UDB3\Model\ValueObject\Taxonomy\Category\CategoryLabel;
use CultuurNet\UDB3\Model\ValueObject\Text\Title;
use CultuurNet\UDB3\Model\ValueObject\Text\TranslatedTitle;
use CultuurNet\UDB3\Model\ValueObject\Translation\Language;
use PHPUnit\Framework\TestCase;

class OrganizerReferenceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_creatable_using_a_organizer_id()
    {
        $id = new UUID('38d78529-29b8-4635-a26e-51bbb2eba535');
        $reference = OrganizerReference::createWithOrganizerId($id);

        $this->assertEquals($id, $reference->getOrganizerId());
        $this->assertNull($reference->getEmbeddedOrganizer());
    }

    /**
     * @test
     */
    public function it_should_be_creatable_using_a_organizer()
    {
        $id = new UUID('38d78529-29b8-4635-a26e-51bbb2eba535');

        $mainLanguage = new Language('nl');

        $title = new TranslatedTitle(
            $mainLanguage,
            new Title('Publiq')
        );

        $url = new Url('http://www.publiq.be');

        $organizer = new ImmutableOrganizer(
            $id,
            $mainLanguage,
            $title,
            $url
        );

        $reference = OrganizerReference::createWithEmbeddedOrganizer($organizer);

        $this->assertEquals($id, $reference->getOrganizerId());
        $this->assertEquals($organizer, $reference->getEmbeddedOrganizer());
    }
}
