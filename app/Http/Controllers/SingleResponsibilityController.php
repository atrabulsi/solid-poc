<?php

namespace App\Http\Controllers;

use App\Core\SingleResponsibility\WaveService;
use Illuminate\Http\Response;

class SingleResponsibilityController extends Controller
{
    private WaveService $waveService;

    public function __construct(WaveService $waveService)
    {
        $this->waveService = $waveService;
    }

    public function get(int $id)
    {
        $output = $this->waveService->getWaveById($id);
        if (!$output) {
            return response()->json(['error' => 'Wave not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'id' => $output->getId(),
            'title' => $output->getTitle(),
        ], Response::HTTP_OK);
    }
}
