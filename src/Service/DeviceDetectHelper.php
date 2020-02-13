<?php

namespace App\Service;

class DeviceDetectHelper
{
    private $mobileDetect;

    public function __construct()
    {
        $this->mobileDetect = new \Mobile_Detect();
    }

    public function getDevice()
    {
        $device = 'desktop';

        if ($this->mobileDetect->isMobile()) {
            $device = 'mobile';
        } else if ($this->mobileDetect->isTable()) {
            $device = 'tablet';
        }

        return $device;
    }
}
