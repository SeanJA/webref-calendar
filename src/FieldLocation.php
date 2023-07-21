<?php

namespace App;

class FieldLocation
{
    private $fieldName;
    private $location;
    private $geo;

    private $locations = [
        'Westminster High School' => [
            'geo' => '42.960962, -81.277180',
            'location' => '230 Base Line Rd W, London, ON'
        ],
        'West Lions Soccer Field' => [
            'geo' => '42.98651357267666, -81.2670331526552',
            'location' => 'West Lions Park, Granville Street, London, ON'
        ],
        'Springbank Flats' => [
            'geo' => '42.97610753583049, -81.26747482068902',
            'location' => 'Springbank Flats, Springbank Drive, London, ON'
        ],
        'Riverbend #1' => [
            'geo' => '42.9715951171613, -81.36293842795867',
            'location' => '1585 Riverbend Road, London, ON'
        ],
        'Riverbend #2' => [
            'geo' => '42.9715951171613, -81.36293842795867',
            'location' => '1585 Riverbend Road, London, ON'
        ],
        'Greenway #1' => [
            'geo' => '42.9715951171613, -81.36293842795867',
            'location' => 'Terry Fox Pkwy, London, ON N6J 1E8'
        ],
        'Greenway #3' => [
            'geo' => '42.974511174899156,-81.28715515136719',
            'location' => '109 Greenside Avenue, London, Ontario'
        ],
    ];

    public function __construct($fieldName)
    {
        $fieldName = $this->cleanupFieldName($fieldName);
        $this->fieldName = $fieldName;
        $this->geo = $this->locations[$fieldName] ? $this->locations[$fieldName]['geo'] : null;
        $this->location = $this->locations[$fieldName] ? $this->locations[$fieldName]['location'] : null;
    }

    public function getGeo()
    {
        return $this->geo;
    }

    public function getTitle()
    {
        return $this->fieldName;
    }

    public function getAddress()
    {
        return isset($this->location)? $this->location : $this->fieldName;
    }

    private function cleanupFieldName($fieldName)
    {
        $fieldName = trim(preg_replace('/\(.*?\)/', '', $fieldName));
        $typos = [
            'Riverband #2' => 'Riverbend #2',
            'Greenway #2' => 'Greenway #3'
        ];
        return isset($typos[$fieldName])? $typos[$fieldName] : $fieldName;
    }
}