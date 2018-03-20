<?php

namespace Webinv\Calendar;

/**
 * Class Event
 * @package Calendar
 */
class Event
{
    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

    /**
     * @var bool
     */
    private $repeat = false;

    /**
     * Repeat frequency (day, week, month, year)
     * @var string
     */
    private $repeatFrequency;

    /**
     * Repeat interval
     *
     * @var int
     */
    private $repeatInterval = 0;

    /**
     * @var array
     */
    private $extra = [];

    const FREQUENCY_DAY   = 'day';
    const FREQUENCY_MONTH = 'month';
    const FREQUENCY_WEEK  = 'week';
    const FREQUENCY_YEAR  = 'year';

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom(\DateTime $from)
    {
        $this->from = $from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo(\DateTime $to)
    {
        $this->to = $to;
    }

    public function isRepeatable(): bool
    {
        return $this->repeat;
    }

    public function setRepeat(bool $repeat)
    {
        $this->repeat = $repeat;
    }

    /**
     * @return string|null
     */
    public function getRepeatFrequency()
    {
        return $this->repeatFrequency;
    }

    public function setRepeatFrequency(string $repeatFrequency)
    {
        $this->repeatFrequency = $repeatFrequency;
    }

    public function getRepeatInterval(): int
    {
        return $this->repeatInterval;
    }

    public function setRepeatInterval(int $repeatInterval)
    {
        $this->repeatInterval = $repeatInterval;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setExtra(array $extra)
    {
        $this->extra = $extra;
    }

    public function addExtra(string $key, $value)
    {
        $this->extra[$key] = $value;
    }

    public function __clone()
    {
        $this->from = clone  $this->from;
        $this->to = clone $this->to;
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
        $json = array_replace_recursive($this->extra, [
            'from' => $this->getFrom()->format(\DateTime::ATOM),
            'to' => $this->getTo()->format(\DateTime::ATOM),
        ]);

        if ($this->isRepeatable()) {
            $json['repeat_interval'] = $this->getRepeatInterval();
            $json['repeat_frequency'] = $this->getRepeatFrequency();
        }

        return $json;
    }
}
