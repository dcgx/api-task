<?php

namespace App\Http\Controllers\API;

use App\Actions\Tasks\CreateTask;
use App\Actions\Tasks\ListTasks;
use App\Actions\Tasks\UpdateTask;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

/**
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="listAllTasks",
     *     tags={"Tasks"},
     *     summary="Display a list of tasks",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="The title of task",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="The description of task",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="The status of task",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="paginated",
     *         in="query",
     *         description="Enable pagination",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Request $request,
        ListTasks $listTasks,
    ) {

        $tasks = $listTasks->handle(
            fields: ['id', 'title', 'description', 'status', 'created_at'],
            filters: $request->query(),
            results: 10,
            paginated: $request->query('paginated') === 'true',
            withTrashed: $request->query('withTrashed', false),
        );

        return response()->json($tasks);
    }


    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     operationId="createTask",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="Task object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *     ),
     *     @OA\Response(
     *          response=401, 
     *          description="Unauthorized"
     *     )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(
        Request $request,
        CreateTask $createTask,
    ){
        $task = $createTask->handle(
            attributes: $request->all(),
        );

        return response()->json($task, 201);
    }


    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     operationId="updateTask",
     *     tags={"Tasks"},
     *     summary="Update a task",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="Task object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of task",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *     ),
     *     @OA\Response(
     *          response=401, 
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *          response=404, 
     *          description="Not Found"
     *     )
     * )
     *
     * Update the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        Request $request,
        UpdateTask $updateTask,
        Task $task,
    ){
        if(!$task) {
            return response()->json([
                'message' => 'The task is not found',
            ], 404);
        }

        if($task->trashed()){
            return response()->json([
                'message' => 'The task is deleted',
            ], 404);
        }

        $task = $updateTask->handle(
            task: $task->id,
            attributes: $request->all(),
        );

        return response()->json($task);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     operationId="showTask",
     *     tags={"Tasks"},
     *     summary="Display a task",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of task",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *     ),
     *     @OA\Response(
     *          response=401, 
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *          response=404, 
     *          description="Not Found"
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(
        Task $task,
    ){
        if(!$task) {
            return response()->json([
                'message' => 'The task is not found',
            ], 404);
        }

        if($task->trashed()){
            return response()->json([
                'message' => 'The task is deleted',
            ], 404);
        }

        return response()->json($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     operationId="deleteTask",
     *     tags={"Tasks"},
     *     summary="Delete a task",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of task",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="No Content",
     *     ),
     *     @OA\Response(
     *          response=401, 
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *          response=404, 
     *          description="Not Found"
     *     )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(
        Task $task,
    ){
        if(!$task) {
            return response()->json([
                'message' => 'The task is not found',
            ], 404);
        }

        if($task->trashed()){
            return response()->json([
                'message' => 'The task is deleted',
            ], 404);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}
