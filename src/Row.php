<?php

namespace App;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use DOMElement;

class Row
{
    /**
     * @var DOMElement
     */
    private $element;

    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    private function nodeValue(int $nodeIndex){
        // osx count is off by one for some reason
        if(PHP_OS === "Darwin"){
            $nodeIndex++;
        }
        return $this->element->childNodes[$nodeIndex]->nodeValue;
    }

    public function isCalendarEntry(): bool
    {
        return count($this->element->childNodes) > 3 && $this->date() !== false;
    }

    public function isNotCalendarEntry(): bool
    {
        return !$this->isCalendarEntry();
    }

    public function date()
    {
        return DateTime::createFromFormat('m/d/Y h:i:s A', $this->nodeValue(0));
    }

    public function arena()
    {
        return $this->nodeValue(2);
    }

    public function division()
    {
        return $this->nodeValue(6);
    }

    public function gameType()
    {
        return $this->nodeValue(8);
    }

    public function home()
    {
        return $this->nodeValue(12);
    }

    public function away()
    {
        return $this->nodeValue(14);
    }

    public function description()
    {
        return $this->away() . ' at ' . $this->home();
    }

    public function summary()
    {
        return $this->description() . ' - ' . $this->gameType();
    }
}
