<?php

namespace App\Http\Services;

use App\Processors\SingleManningProcessor;
use App\Http\Repositories\StaffRepository;
use App\Models\Rota;
use App\Models\Shift;
use App\Transformers\SingleManning;
use Illuminate\Support\Collection;
use \DateTime;

class RotaService
{
    protected $staffRepository;
    protected $singleManningProcessor;

    /**
     * RotaService constructor.
     * @param StaffRepository $staffRepository
     * @param SingleManningProcessor $singleManningProcessor
     */
    public function __construct(StaffRepository $staffRepository, SingleManningProcessor $singleManningProcessor)
    {
        $this->staffRepository = $staffRepository;
        $this->singleManningProcessor = $singleManningProcessor;
    }

    /**
     * @param Rota $rota
     * @param DateTime $currentDay
     * @return Collection
     */
    public function handle(Rota $rota, DateTime $currentDay): Collection
    {
        $shifts = collect($this->getCurrentDays($rota, $currentDay));

        if ($this->hasMoreThanOneMemberWorkedToday($shifts) === true) {
            $result = $this->singleManningProcessor->handleMultipleStaff($shifts);
        }

        if ($this->hasMoreThanOneMemberWorkedToday($shifts) === false) {
            $result = $this->singleManningProcessor->handleUniqueStaff($shifts);
        }

        return $result;
    }

    /**
     * @param Rota $rota
     * @param DateTime $currentDay
     * @return Collection
     */
    protected function getCurrentDays(Rota $rota, DateTime $currentDay): Collection
    {
        return Shift::where('rota_id', $rota->id)
            ->where('start_time', '>=', $currentDay->format('Y-m-d'))
            ->where('end_time', '<', $currentDay->modify('+1 days')->format('Y-m-d'))
            ->orderBy('start_time')
            ->get();
    }

    /**
     * @param Collection $shifts
     * @return bool
     */
    protected function hasMoreThanOneMemberWorkedToday(Collection $shifts): bool
    {
        return $shifts->count() > 1;
    }
}
