<?php

namespace App;

use DateTime;
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

    /**
     * @param $nodeIndex
     * @return \DOMNode
     */
    private function node($nodeIndex)
    {
        // osx count is off by one for some reason
        if (PHP_OS === "Darwin") {
            $nodeIndex++;
        }
        return $this->element->childNodes[$nodeIndex];
    }

    /**
     * @param int $nodeIndex
     * @return string
     */
    private function nodeValue(int $nodeIndex)
    {
        return $this->node($nodeIndex)->nodeValue;
    }

    /**
     * @return bool
     */
    public function isCalendarEntry(): bool
    {
        return count($this->element->childNodes) > 3 && $this->date() !== false;
    }

    /**
     * @return bool
     */
    public function isNotCalendarEntry(): bool
    {
        return !$this->isCalendarEntry();
    }

    /**
     * @return DateTime
     */
    public function date()
    {
        return DateTime::createFromFormat('m/d/Y h:i:s A', $this->nodeValue(0));
    }

    /**
     * @return string
     */
    public function arena()
    {
        return $this->nodeValue(2);
    }

    /**
     * @return string
     */
    public function division()
    {
        return $this->nodeValue(6);
    }

    /**
     * @return string
     */
    public function gameType()
    {
        return $this->nodeValue(8);
    }

    /**
     * @return string
     */
    public function gameSheet()
    {
        return 'http://webreferee.net/Leagues/' . $this->node(10)->firstChild->attributes['href']->textContent;
    }

    /**
     * @return string
     */
    public function home()
    {
        return $this->nodeValue(12);
    }

    /**
     * @return string
     */
    public function away()
    {
        return $this->nodeValue(14);
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->gameType() . PHP_EOL . $this->gameSheet();
    }

    /**
     * @return string
     */
    public function summary()
    {
        return $this->away() . ' at ' . $this->home();

    }
}
