<?php

use App\FieldLocation;
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
    if(!$row->isForTeam($team)){
        continue;
    }

    if($row->isByeWeek()){
        continue;
    }

    $event = new Event();

    $location = new FieldLocation($row->arena());

    $event->setSummary($row->summary())
        ->setDescription($row->description())
        ->setLocation($row->address(), $row->arena(), $row->geo())
        ->setDtStart($row->date())
        ->setUrl($row->gameSheet())
        ->setUseUtc(true);
    $calendar->addComponent($event);
}

//header('Content-Type: text/calendar; charset=utf-8');
//header('Content-Disposition: attachment; filename="calendar2.ics"');
echo $calendar->render();
