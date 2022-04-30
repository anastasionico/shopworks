<?php

namespace App\Transformers;

use App\Http\Services\RotaService;
use App\Models\Rota;
use App\Processors\DateProcessor;
use Illuminate\Support\Collection;

class SingleManning
{
    public $result;

    /**
     * SingleManning constructor.
     * @param Collection $result
     */
    public function __construct(Collection $result)
    {
        $this->result = $result;
    }

    /**
     * @param RotaService $rotaService
     * @param DateProcessor $dateProcessor
     * @param Rota $rota
     * @return static
     * @throws \Exception
     */
    public static function fromRota(RotaService $rotaService, DateProcessor $dateProcessor, Rota $rota)
    {
        $result = collect([]);

        $rotaStartDay = $rota->week_commence_date;
        $currentDay = $dateProcessor->getStartDay($rotaStartDay);

        do {
            $dayName = $dateProcessor->getNameDay($currentDay);
            $result[$dayName] = $rotaService->handle($rota, $currentDay);
        } while ($dateProcessor->getEndDay($rotaStartDay) >= $currentDay);

        return new static($result);
    }

}
