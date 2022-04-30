<?php

namespace App\Processors;

use Illuminate\Support\Collection;
use \DateTime;

trait SetTimes
{
    /**
     * @param Collection $shifts
     */
    protected function setStartTimes(Collection $shifts)
    {
        $shifts->map(function ($shift) {
            return [
                'staff_id' => $shift['staff_id'],
                'start_time' => $shift['start_time']
            ];
        })->each(function ($startTimes){
            if ($this->firstTime === null) {
                $this->firstTime = $startTimes['start_time'];
                $this->firstStaff = $startTimes['staff_id'];
            }

            if ($this->firstTime < $startTimes['start_time']) {
                $origin = new DateTime($this->firstTime);
                $target = new DateTime($startTimes['start_time']);
                $interval = $origin->diff($target);
                $this->startTimeInterval = [
                    'staffId' => $this->firstStaff,
                    'hoursInterval' => $interval->h,
                    'openingTime' => $this->firstTime
                ];
            }
        });
    }

    /**
     * @param Collection $shifts
     */
    protected function setEndTimes(Collection $shifts)
    {
        $shifts->sortBy('end_time')
            ->reverse()
            ->map(function ($shift) {
                return [
                    'staff_id' => $shift['staff_id'],
                    'end_time' => $shift['end_time']
                ];
            })->each(function ($endTimes){
                if ($this->lastTime === null) {
                    $this->lastTime = $endTimes['end_time'];
                    $this->lastStaff = $endTimes['staff_id'];
                }

                if ($this->lastTime > $endTimes['end_time']) {
                    $origin = new DateTime($this->lastTime);
                    $target = new DateTime($endTimes['end_time']);
                    $interval = $origin->diff($target);
                    $this->endTimeInterval = [
                        'staffId' => $this->lastStaff,
                        'hoursInterval' => $interval->h,
                        'closingTime' => $this->lastTime
                    ];
                }
            });
    }
}
