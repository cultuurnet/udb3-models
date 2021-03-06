<?php

namespace CultuurNet\UDB3\Model\ValueObject\Contact;

use CultuurNet\UDB3\Model\ValueObject\DateTimeImmutableRange;

class BookingAvailability extends DateTimeImmutableRange
{
    /**
     * @param \DateTimeImmutable $from
     * @return static
     */
    public static function from(\DateTimeImmutable $from)
    {
        return new static($from, null);
    }

    /**
     * @param \DateTimeImmutable $to
     * @return static
     */
    public static function to(\DateTimeImmutable $to)
    {
        return new static(null, $to);
    }

    /**
     * @param \DateTimeImmutable $from
     * @param \DateTimeImmutable $to
     * @return static
     */
    public static function fromTo(\DateTimeImmutable $from, \DateTimeImmutable $to)
    {
        return new static($from, $to);
    }
}
