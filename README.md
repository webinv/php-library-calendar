# Calendar library

[![Build Status](https://travis-ci.org/webinv/php-library-calendar.svg?branch=master)](https://travis-ci.org/webinv/php-library-calendar)
[![Latest Stable Version](https://poser.pugx.org/webinv/calendar/v/stable)](https://packagist.org/packages/webinv/calendar)
[![Total Downloads](https://poser.pugx.org/webinv/calendar/downloads)](https://packagist.org/packages/webinv/calendar)
[![Latest Unstable Version](https://poser.pugx.org/webinv/calendar/v/unstable)](https://packagist.org/packages/webinv/calendar)
[![License](https://poser.pugx.org/webinv/calendar/license)](https://packagist.org/packages/webinv/calendar)


## Installation

`composer require webinv/calendar`

## Usage

```
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
```

**Results**:

```
Event 2017-11-03T10:00:00+00:00 - 2017-11-03T12:30:00+00:00
Event 2017-11-06T10:00:00+00:00 - 2017-11-06T12:30:00+00:00
Event 2017-11-09T10:00:00+00:00 - 2017-11-09T12:30:00+00:00
Event 2017-11-12T10:00:00+00:00 - 2017-11-12T12:30:00+00:00
Event 2017-11-15T10:00:00+00:00 - 2017-11-15T12:30:00+00:00
Event 2017-11-18T10:00:00+00:00 - 2017-11-18T12:30:00+00:00
Event 2017-11-21T10:00:00+00:00 - 2017-11-21T12:30:00+00:00
Event 2017-11-24T10:00:00+00:00 - 2017-11-24T12:30:00+00:00
Event 2017-11-27T10:00:00+00:00 - 2017-11-27T12:30:00+00:00
Event 2017-11-30T10:00:00+00:00 - 2017-11-30T12:30:00+00:00

```