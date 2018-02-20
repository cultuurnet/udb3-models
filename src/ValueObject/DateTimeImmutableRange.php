<?php

namespace CultuurNet\UDB3\Model\ValueObject;

abstract class DateTimeImmutableRange
{
    /**
     * @var \DateTimeImmutable
     */
    private $from;

    /**
     * @var \DateTimeImmutable
     */
    private $to;

    /**
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     */
    public function __construct(\DateTimeImmutable $from = null, \DateTimeImmutable $to = null)
    {
        if ($from && $to && $from > $to) {
            throw new \InvalidArgumentException('"From" date should not be later than the "to" date.');
        }

        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getTo()
    {
        return $this->to;
    }

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
