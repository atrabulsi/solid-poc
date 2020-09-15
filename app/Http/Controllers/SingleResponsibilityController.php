<?php

namespace App\Http\Controllers;

use App\Core\SingleResponsibility\Services\WaveServiceWithCacheableRepo;
use App\Core\SingleResponsibility\Services\WaveServiceWithCaching;
use App\Core\SingleResponsibility\Services\WaveServiceWithoutCache;
use Illuminate\Http\Response;

class SingleResponsibilityController extends Controller
{
    public function getWithoutCache(int $id, WaveServiceWithoutCache $waveService)
    {
        $output = $waveService->getWaveById($id);
        if (!$output) {
            return response()->json(['error' => 'Wave not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($output, Response::HTTP_OK);
    }

    public function getWithCacheInService(int $id, WaveServiceWithCaching $waveService)
    {
        $output = $waveService->getWaveById($id);
        if (!$output) {
            return response()->json(['error' => 'Wave not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($output, Response::HTTP_OK);
    }

    public function getWithCacheableRepo(int $id, WaveServiceWithCacheableRepo $waveService)
    {
        $output = $waveService->getWaveById($id);
        if (!$output) {
            return response()->json(['error' => 'Wave not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($output, Response::HTTP_OK);
    }
}
