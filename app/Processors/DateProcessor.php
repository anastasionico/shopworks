<?php

namespace App\Processors;

use \DateTime;

class DateProcessor
{
    /**
     * @param string $rotaStartDay
     * @return DateTime
     * @throws \Exception
     */
    public function getStartDay(string $rotaStartDay): DateTime
    {
        return  new DateTime($rotaStartDay);
    }

    /**
     * @param string $rotaStartDay
     * @return DateTime
     * @throws \Exception
     */
    public function getEndDay(string $rotaStartDay): DateTime
    {
        $endDay = new DateTime($rotaStartDay);
        return $endDay->modify('+6 days');
    }

    /**
     * @param DateTime $currentDay
     * @return string
     */
    public function getNameDay(DateTime $currentDay): string
    {
        return date('l', $currentDay->getTimestamp());
    }

}
