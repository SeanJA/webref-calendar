<?php

use App\Row;
use App\WebRefClient;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Gregwar\Cache\Cache;
use GuzzleHttp\Client;

require 'config/bootstrap.php';

$cache = new Cache();
$cache->setCacheDirectory('cache');
$guzzle = new Client([
    'cookies' => true
]);

$team = $_GET['team']?? getenv('DEFAULT_TEAM_NAME');

$client = new WebRefClient($guzzle, $cache);

$rows = $client->getRows();

$calendar = new Calendar(getenv('CALENDAR_NAME'));
$calendar->setPublishedTTL('P3D');

$events = [];

/** @var DOMElement $element */
foreach ($rows as $element) {
    $row = new Row($element);
    if ($row->isNotCalendarEntry()) {
        continue;
    }
    $event = new Event();

    $event->setSummary($row->summary())
        ->setDescription($row->description())
        ->setLocation($row->arena())
        ->setDtStart($row->date())
        ->setUseUtc(true);
    $calendar->addComponent($event);
}

//header('Content-Type: text/calendar; charset=utf-8');
//header('Content-Disposition: attachment; filename="calendar2.ics"');
echo $calendar->render();
