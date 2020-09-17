<?php

namespace App\Http\Controllers;

use App\Core\DependencyInversion\Services\WaveService;
use App\Core\DependencyInversion\Transformers\WaveToArrayTransformer;
use App\Core\DependencyInversion\Transformers\WaveToHtmlTransformer;
use Illuminate\Http\Response;

class DependencyInversionController extends Controller
{
    public function getWave(int $id, WaveService $waveService, WaveToArrayTransformer $transformer)
    {
        $output = $waveService->getWaveById($id, $transformer);
        if (!$output) {
            return response()->json(['error' => 'Wave not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($output->read(), Response::HTTP_OK);
    }

    public function getWaveAsHtml(int $id, WaveService $waveService, WaveToHtmlTransformer $transformer)
    {
        $output = $waveService->getWaveById($id, $transformer);
        if (!$output) {
            return response('Wave not found!!!', Response::HTTP_NOT_FOUND);
        }
        return response($output->read(), Response::HTTP_OK);
    }
}
