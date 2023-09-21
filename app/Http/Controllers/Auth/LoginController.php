<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/login",
 *     operationId="login",
 *     tags={"Authentication"},
 *     summary="Iniciar sesión",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Credenciales de inicio de sesión",
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="test@kiibo-task.test"),
 *             @OA\Property(property="password", type="string", format="password", example="password"),
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Inicio de sesión exitoso",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", description="Token de acceso"),
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Credenciales inválidas",
 *     )
 * )
 */
class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos de inicio de sesión
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($user->id)->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            // Si las credenciales son incorrectas, devolver una respuesta de error
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }
    }}
