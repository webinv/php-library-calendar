<?php

namespace Webinv\Calendar;

use Traversable;

/**
 * Class Calendar
 * @package Calendar
 */
class Calendar implements \IteratorAggregate
{
    /**
     * @var EventCollection
     */
    private $collection;

    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

    /**
     * @var \DateInterval
     */
    private $range;

    /**
     * Calendar constructor.
     * @param \DateTime $from
     * @param \DateTime $to
     */
    public function __construct(\DateTime $from, \DateTime $to)
    {
        $this->collection = new EventCollection();
        $this->from = $from;
        $this->to = $to;
        $this->range = $to->diff($from, true);
    }

    /**
     * @param Event $event
     * @throws \Exception
     */
    public function addEvent(Event $event)
    {
        if (
            ($event->getFrom() <= $this->from && $event->getTo() >= $this->to) ||
            ($event->getFrom() >= $this->from && $event->getFrom() <= $this->to) ||
            ($event->getTo() >= $this->from && $event->getTo() <= $this->to)
        ) {
            $this->collection->add($event);
        }

        $this->processRepetition($event);
    }

    /**
     * @param Event $event
     * @throws \Exception
     */
    private function processRepetition(Event $event)
    {
        if ($event->isRepeatable()) {
            $shift = $event->getFrom()->diff($this->from, true);
            $duration = $event->getFrom()->diff($event->getTo(), true);
            $unitCount = 1;
            switch ($event->getRepeatFrequency()) {
                case Event::FREQUENCY_DAY:
                    $unitShiftFormat = '%a';
                    $periodFormat = 'P%dD';
                    break;
                case Event::FREQUENCY_WEEK:
                    $unitShiftFormat = '%a';
                    $periodFormat = 'P%dD';
                    $unitCount = 7;
                    break;
                case Event::FREQUENCY_MONTH:
                    $unitShiftFormat = '%m';
                    $periodFormat = 'P%dM';
                    break;
                case Event::FREQUENCY_YEAR:
                    $unitShiftFormat = '%y';
                    $periodFormat = 'P%dY';
                    break;

                default:
                    throw new \Exception(
                        sprintf('Unknown "%s" frequency unit', $event->getRepeatFrequency())
                    );
            }

            $shiftUnit = (int)$shift->format($unitShiftFormat);
            $repeatedEvent = clone $event;

            $intervalMultiplier = ceil($shiftUnit/$unitCount/$event->getRepeatInterval());
            if ($intervalMultiplier > 0) {
                $shiftInterval = new \DateInterval(sprintf(
                    $periodFormat,
                    ($intervalMultiplier*$unitCount*$event->getRepeatInterval())
                ));
                $repeatedEvent->getFrom()->add($shiftInterval);
                $repeatedEvent->getTo()->add($shiftInterval);
            }
            $interval = new \DateInterval(sprintf(
                $periodFormat,
                $event->getRepeatInterval() * $unitCount
            ));

            $repeatFrom = (clone $repeatedEvent->getFrom())->add($interval);

            if ($repeatFrom > $this->to) {
                return ;
            }

            $period = new \DatePeriod($repeatFrom, $interval, $this->to);
            foreach ($period as $date) {
                $eventCopy = clone $repeatedEvent;
                $eventCopy->setFrom($date);
                $eventCopy->setTo((clone $date)->add($duration));

                $this->collection->add($eventCopy);
            }
        }
    }
    /**
     * @return EventCollection
     */
    public function getCollection(): EventCollection
    {
        return $this->collection;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator() : Traversable
    {
        return $this->collection;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->getCollection()->jsonSerialize();
    }
}
