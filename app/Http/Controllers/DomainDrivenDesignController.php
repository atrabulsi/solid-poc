<?php

namespace App\Http\Controllers;

use App\Core\DDD\Application\CreateWave\CreateWaveCommand;
use App\Core\DDD\Application\CreateWave\CreateWaveHandler;
use App\Core\DDD\Application\Exceptions\ApplicationException;
use App\Core\DDD\Application\Exceptions\InputException;
use App\Core\DDD\Application\GetWave\GetWaveHandler;
use App\Core\DDD\Application\GetWave\GetWaveQuery;
use App\Core\DDD\Application\Transformers\WaveToArrayTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class DomainDrivenDesignController extends Controller
{
    public function createWave(Request $request, CreateWaveHandler $handler, WaveToArrayTransformer $transformer)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'startDate' => 'date',
                'endDate' => 'date',
            ]);
            $command = new CreateWaveCommand(
                $request->get('name'),
                $request->get('startDate') ? new Carbon($request->get('startDate')) : null,
                $request->get('endDate') ? new Carbon($request->get('endDate')) : null,
            );
            $output = $handler->handle($command, $transformer);
            return response()->json($output->read(), Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            $validationErrors = json_decode($e->getResponse()->getContent(), true);
            $errorMessages = '';
            foreach ($validationErrors as $validationError) {
                $errorMessages .= implode('. ', $validationError);
            }
            return response()->json(['error' => $errorMessages], Response::HTTP_BAD_REQUEST);
        } catch (InputException | ApplicationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getHttpStatusCode());
        }
    }

    public function getWave(int $id, GetWaveHandler $handler, WaveToArrayTransformer $transformer)
    {
        try {
            $query = new GetWaveQuery($id);
            $output = $handler->handle($query, $transformer);
            return response()->json($output->read(), Response::HTTP_OK);
        } catch (InputException | ApplicationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getHttpStatusCode());
        }
    }
}
