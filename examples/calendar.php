<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Webinv\Calendar\Event;
use Webinv\Calendar\Calendar;

$event = new Event();
$event->setRepeat(true);
$event->setRepeatFrequency(Event::FREQUENCY_DAY);
$event->setRepeatInterval(3);
$event->setFrom(new DateTime('2017-10-22 10:00'));
$event->setTo(new DateTime('2017-10-22 12:30'));


$calendar = new Calendar(new DateTime("2017-11-01"), new DateTime("2017-11-31"));
$calendar->addEvent($event);

foreach ($calendar as $item) {
    echo sprintf("Event %s - %s\n", $item->getFrom()->format(\DateTime::ATOM), $item->getTo()->format(\DateTime::ATOM));
}