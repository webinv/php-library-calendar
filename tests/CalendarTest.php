<?php

namespace Tests\Calendar;

use PHPUnit\Framework\TestCase;
use Webinv\Calendar\Event;
use Webinv\Calendar\Calendar;
use DateTime;

/**
 * Class CalendarTest
 * @package Tests\Calendar
 */
class CalendarTest extends TestCase
{
    public function testCalendar()
    {
        $event = new Event();
        $event->setRepeat(true);
        $event->setRepeatFrequency(Event::FREQUENCY_DAY);
        $event->setRepeatInterval(3);
        $event->setFrom(new DateTime('2017-10-22 10:00'));
        $event->setTo(new DateTime('2017-10-22 12:30'));


        $calendar = new Calendar(new DateTime("2017-11-01"), new DateTime("2017-11-31"));

        $calendar->addEvent($event);

        $this->assertCount(10, $calendar->getCollection());
    }
}
