<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Http\Resources\Tasks;
use App\Rules\Cron;
use App\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TaskResource|JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'cron' => ['required', 'string', new Cron()],
            'command' => ['required', 'string']
        ]);
        $response = new JsonResponse();
        if ($validator->fails()) {
            $response->setStatusCode($response::HTTP_BAD_REQUEST);
            $response->setData(['errors' => $validator->errors()->messages()]);
            return $response;
        } else {
            $task = new Task($request->all());
            $task->save();
            return new TaskResource($task);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return TaskResource
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return TaskResource|JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->json()->all(), [
            'cron' => ['string', new Cron()],
            'command' => ['string']
        ]);

        if ($validator->fails()) {
            $response = new JsonResponse();
            $response->setStatusCode($response::HTTP_BAD_REQUEST);
            $response->setData(['errors' => $validator->errors()->messages()]);
            return $response;
        } else {
            $task->update($request->all());
            return new TaskResource($task);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return new JsonResponse(['status' => 204], 204);
    }
}
