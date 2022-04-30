<?php

namespace App\Http\Controllers;

use App\Http\Requests\RotaRequest;
use App\Processors\DateProcessor;
use App\Http\Services\RotaService;
use App\Models\Rota;
use App\Transformers\SingleManning;
use Illuminate\Http\Request;
use \Exception;

class RotaController extends Controller
{
    public $service;
    public $dateProcessor;

    /**
     * RotaController constructor.
     * @param RotaService $rotaService
     * @param DateProcessor $dateProcessor
     */
    public function __construct(RotaService $rotaService, DateProcessor $dateProcessor)
    {
        $this->service = $rotaService;
        $this->dateProcessor = $dateProcessor;
    }

    /**
     * @param Request $request
     * @param Rota $rota
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RotaRequest $request, Rota $rota)
    {
        try {
            $request->validated();
            $result = SingleManning::fromRota($this->service, $this->dateProcessor, $rota);
        } catch (Exception $exception) {
            $exception->getMessage();
        } catch (ErrorException $exception) {
            $exception->getMessage();
        }

        return response()->json($result->result);
    }
}
