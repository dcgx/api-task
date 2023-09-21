<?php

namespace App\Http\Controllers\API;

use App\Actions\Users\CreateUser;
use App\Actions\Users\ListUsers;
use App\Actions\Users\UpdateUser;
use App\Http\Controllers\Controller;
use App\Models\User;
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
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     operationId="listAllUsers",
     *     tags={"Users"},
     *     summary="Display a list of users",
     *     security={{"sanctum":{}}},
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
        ListUsers $listUsers,
    ) {

        $users = $listUsers->handle(
            results: 10,
            paginated: $request->query('paginated') === 'true',
            withTrashed: $request->query('withTrashed', false),
        );

        return response()->json($users);
    }


    /**
     * @OA\Post(
     *     path="/api/users",
     *     operationId="createUser",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="User object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateUserRequest")
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
        CreateUser $createUser,
    ){
        $user = $createUser->handle(
            attributes: $request->all(),
        );

        return response()->json($user, 201);
    }


    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     operationId="updateUser",
     *     tags={"Uasks"},
     *     summary="Update a user",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="User object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of user",
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
        UpdateUser $updateUser,
        User $user,
    ){
        if(!$user) {
            return response()->json([
                'message' => 'The user is not found',
            ], 404);
        }

        if($user->trashed()){
            return response()->json([
                'message' => 'The user is deleted',
            ], 404);
        }

        $user = $updateUser->handle(
            user: $user->id,
            attributes: $request->all(),
        );

        return response()->json($user);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     operationId="showUser",
     *     tags={"Uasks"},
     *     summary="Display a user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of user",
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
        User $user,
    ){
        if(!$user) {
            return response()->json([
                'message' => 'The user is not found',
            ], 404);
        }

        if($user->trashed()){
            return response()->json([
                'message' => 'The user is deleted',
            ], 404);
        }

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     operationId="deleteUser",
     *     tags={"Uasks"},
     *     summary="Delete a user",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of user",
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
        User $user,
    ){
        if(!$user) {
            return response()->json([
                'message' => 'The user is not found',
            ], 404);
        }

        if($user->trashed()){
            return response()->json([
                'message' => 'The user is deleted',
            ], 404);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
