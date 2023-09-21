<?php

namespace App\Http\Controllers\API;

use App\Actions\Tasks\ListTasks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="listAllTasks",
     *     tags={"Tasks"},
     *     summary="Display a list of tasks",
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
}
