<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {

    }

    /**
     *
     * @OA\Get(
     *     path="/api/users",
     *     summary=" List all users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response="201",
     *         description="Users list"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="No users found"
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        if($users->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No users found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="role", type="string"),
     *             @OA\Property(property="user_type", type="string", enum={"requester", "assigned"})
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer"),
     *             @OA\Property(property="message", type="object")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'name' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'user_type'  => ['required', 'in:requester,assigned'],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages()
            ], 422);
        }



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'user_type' => $request->user_type,
            'created_at' => now(),
            'update_at' => now()
        ]);

        if(!$user) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ],500);
        }

        return response()->json([
            'status' => 201,
            'user' => $user
        ], 201);
    }
}
