<?php

namespace CultuurNet\UDB3\Model\ValueObject\Web;

use TwoDotsTwice\ValueObject\Collection\Collection;

class EmailAddresses extends Collection
{
    /**
     * @param EmailAddress[] ...$values
     */
    public function __construct(EmailAddress ...$values)
    {
        parent::__construct(...$values);
    }
}