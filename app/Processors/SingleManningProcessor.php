<?php

namespace App\Processors;

use App\Http\Repositories\StaffRepository;
use App\Models\Shift;
use Illuminate\Support\Collection;
use \DateTime;
use \Exception;

class SingleManningProcessor
{
    use SetTimes;

    protected $staffRepository;

    protected $startTimeInterval;
    protected $endTimeInterval;
    protected $firstTime;
    protected $lastTime;

    /**
     * SingleManningProcessor constructor.
     * @param StaffRepository $staffRepository
     */
    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    /**
     * @param Collection $shifts
     * @return Collection
     */
    public function handleUniqueStaff(Collection $shifts): Collection
    {
        return $shifts->mapWithKeys(function ($shift) {
            return $this->getSingleManningMinutes($shift);
        });
    }

    /**
     * @param Shift $shift
     * @return Collection
     */
    protected function getSingleManningMinutes(Shift $shift): Collection
    {
        $staff = $this->staffRepository->getName($shift->staff_id);

        $start_time = DateTime::createFromFormat('Y-m-d H:i:s', $shift->start_time);
        $end_time = DateTime::createFromFormat('Y-m-d H:i:s', $shift->end_time);

        if ($start_time > $end_time) {
            throw new Exception("error handling times");
        }

        $diff = date_diff($start_time, $end_time);

        return collect([$staff['first_name'] => $diff->h * 60]);
    }

    /**
     * @param Collection $shifts
     * @return Collection
     */
    public function handleMultipleStaff(Collection $shifts): Collection
    {
        $return = collect([]);

        $this->setStartTimes($shifts);
        $this->setEndTimes($shifts);

        if ($this->doesSameStaffMemberOpenAndClose() === true) {
            $return->put(
                $this->staffRepository->getName($this->startTimeInterval['staffId'])->first_name,
                ($this->startTimeInterval['hoursInterval'] * 60) + ($this->endTimeInterval['hoursInterval'] * 60)
            );
        }

        if ($this->doesSameStaffMemberOpenAndClose() === false) {
            $return->put(
                $this->staffRepository->getName($this->startTimeInterval['staffId'])->first_name,
                $this->startTimeInterval['hoursInterval'] * 60);
            $return->put(
                $this->staffRepository->getName($this->endTimeInterval['staffId'])->first_name,
                $this->endTimeInterval['hoursInterval'] * 60);
        }

        return $return;
    }

    /**
     * @return bool
     */
    protected function doesSameStaffMemberOpenAndClose(): bool
    {
        return $this->startTimeInterval['staffId'] === $this->endTimeInterval['staffId'];
    }
}
