<?php

namespace CultuurNet\UDB3\Model\Serializer\ValueObject\Calendar;

use CultuurNet\UDB3\Model\ValueObject\Calendar\Calendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRange;
use CultuurNet\UDB3\Model\ValueObject\Calendar\DateRanges;
use CultuurNet\UDB3\Model\ValueObject\Calendar\Status;
use CultuurNet\UDB3\Model\ValueObject\Calendar\StatusType;
use CultuurNet\UDB3\Model\ValueObject\Calendar\MultipleDateRangesCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Day;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Days;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Hour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Minute;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHour;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\OpeningHours;
use CultuurNet\UDB3\Model\ValueObject\Calendar\OpeningHours\Time;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PeriodicCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\PermanentCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\SingleDateRangeCalendar;
use CultuurNet\UDB3\Model\ValueObject\Calendar\TranslatedStatusReason;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CalendarDenormalizer implements DenormalizerInterface
{
    /**
     * @var TranslatedStatusReasonDenormalizer
     */
    private $statusReasonDenormalizer;

    public function __construct()
    {
        $this->statusReasonDenormalizer = new TranslatedStatusReasonDenormalizer();
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new UnsupportedException("CalendarDenormalizer does not support {$class}.");
        }

        if (!is_array($data)) {
            throw new UnsupportedException('Calendar data should be an associative array.');
        }

        $openingHoursData = isset($data['openingHours']) ? $data['openingHours'] : [];
        $openingHours = $this->denormalizeOpeningHours($openingHoursData);

        switch ($data['calendarType']) {
            case 'single':
                $dateRange = $this->denormalizeDateRange($data['subEvent'][0]);
                $calendar = new SingleDateRangeCalendar($dateRange);
                break;

            case 'multiple':
                $dateRanges = array_map([$this, 'denormalizeDateRange'], $data['subEvent']);
                $dateRanges = new DateRanges(...$dateRanges);
                $calendar = new MultipleDateRangesCalendar($dateRanges);
                break;

            case 'periodic':
                $dateRange = $this->denormalizeDateRange($data);
                $calendar = new PeriodicCalendar($dateRange, $openingHours);
                break;

            case 'permanent':
            default:
                $calendar = new PermanentCalendar($openingHours);
                break;
        }

        return $calendar;
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Calendar::class;
    }

    /**
     * @todo Extract to a separate OpeningHoursDenormalizer
     * @param array $openingHoursData
     * @return OpeningHours
     */
    private function denormalizeOpeningHours(array $openingHoursData)
    {
        $openingHours = array_map([$this, 'denormalizeOpeningHour'], $openingHoursData);
        return new OpeningHours(...$openingHours);
    }

    /**
     * @todo Extract to a separate OpeningHourDenormalizer
     * @param array $openingHourData
     * @return OpeningHour
     */
    private function denormalizeOpeningHour(array $openingHourData)
    {
        $days = $this->denormalizeDays($openingHourData['dayOfWeek']);
        $opens = $this->denormalizeTime($openingHourData['opens']);
        $closes = $this->denormalizeTime($openingHourData['closes']);
        return new OpeningHour($days, $opens, $closes);
    }

    /**
     * @todo Extract to a separate DaysDenormalizer
     * @param array $daysData
     * @return Days
     */
    private function denormalizeDays(array $daysData)
    {
        $days = array_map(
            function ($day) {
                return new Day($day);
            },
            $daysData
        );
        return new Days(...$days);
    }

    /**
     * @todo Extract to a separate TimeDenormalizer
     * @param string $timeString
     * @return Time
     */
    private function denormalizeTime($timeString)
    {
        $dateTime = \DateTimeImmutable::createFromFormat('H:i', $timeString);
        $hour = new Hour((int) $dateTime->format('H'));
        $minute = new Minute((int) $dateTime->format('i'));
        return new Time($hour, $minute);
    }

    /**
     * @todo Extract to a separate DateRangeDenormalizer
     * @param array $dateRangeData
     * @return DateRange
     */
    private function denormalizeDateRange(array $dateRangeData)
    {
        $startDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $dateRangeData['startDate']);
        $endDate = \DateTimeImmutable::createFromFormat(\DATE_ATOM, $dateRangeData['endDate']);

        $statusType = null;
        if (isset($dateRangeData['eventStatus'])) {
            $statusType = new StatusType($dateRangeData['eventStatus']);
        }

        $statusReason = null;
        if (isset($dateRangeData['eventStatusReason']) &&
            $statusType &&
            !$statusType->sameAs(StatusType::Available())
        ) {
            /** @var TranslatedStatusReason $statusReason */
            $statusReason = $this->statusReasonDenormalizer->denormalize(
                $dateRangeData['eventStatusReason'],
                TranslatedStatusReason::class
            );
        }

        $status = null;
        if ($statusType) {
            $status = new Status($statusType, $statusReason);
        }

        return new DateRange(
            $startDate,
            $endDate,
            $status
        );
    }
}
