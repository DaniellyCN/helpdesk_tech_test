<?php

namespace App\Http\Controllers;

use App\Exceptions\UuidValidationException;
use App\Models\Ticket;
use App\Services\TicketValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


/**
 * @OA\Info(
 *     title="Helpdesk API",
 *     version="1.0.0",
 *     description="This API provides features to streamline the management of support requests and user information in a helpdesk system."
 * )
 */
class TicketController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/tickets",
     *     summary="List all tickets",
     *     tags={"Tickets"},
     *     @OA\Response(
     *         response="200",
     *         description="Tickets list"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="No tickets found"
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tickets = Ticket::all();

        if($tickets->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No tickets found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'tickets' => $tickets
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/tickets",
     *     summary="Create a new ticket",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="user_requester_id", type="string"),
     *             @OA\Property(property="user_assigned_id", type="string"),
     *             @OA\Property(property="category", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"})
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Ticket created successfully"
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

        if (!Str::isUuid($request->user_requester_id) || ($request->user_assigned_id && !Str::isUuid($request->user_assigned_id))) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid UUID format for user_requester_id or user_assigned_id'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'user_requester_id' => 'required|exists:users,id|string',
            'user_assigned_id' => 'exists:users,id',
            'category' => 'required',
            'description' => 'required',
            'priority' => ['required', 'in:low,medium,high'],

        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages()
            ], 422);
        }


        $ticket = Ticket::create([
            'user_requester_id' => $request->user_requester_id,
            'user_assigned_id' => $request->user_assigned_id,
            'category' => $request->category,
            'description' => $request->description,
            'priority' => $request->priority,
            'created_at' => now(),
            'update_at' => now()
        ]);

        if(!$ticket) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong'
            ],500);
        }

        return response()->json([
            'status' => 201,
            'ticket' => $ticket
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tickets/{id}",
     *     summary="Show a specific ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ticket ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ticket details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", format="int32"),
     *             @OA\Property(property="ticket", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ticket not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", format="int32"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if(!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket not found'
            ],404);
        }

        return response()->json([
            'status' => 200,
            'ticket' => $ticket
        ],200);
    }

    /**
     * @OA\Put(
     *     path="/api/tickets",
     *     summary="Update a specific ticket",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         description="Ticket data to update",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", description="Ticket ID"),
     *             @OA\Property(property="user_requester_id", type="string", description="Requester user ID"),
     *             @OA\Property(property="user_assigned_id", type="string", description="Assigned user ID"),
     *             @OA\Property(property="category", type="string", description="Category"),
     *             @OA\Property(property="description", type="string", description="Description"),
     *             @OA\Property(property="status", type="string", description="Status", enum={"open", "in progress", "resolved"}),
     *             @OA\Property(property="priority", type="string", description="Priority", enum={"low", "medium", "high"}),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Ticket updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ticket not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", format="int32"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", format="int32"),
     *             @OA\Property(property="message", type="object")
     *         )
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, TicketValidationService $validationService): JsonResponse
    {
        if (!Str::isUuid($request->user_requester_id) ||
            ($request->user_assigned_id && !Str::isUuid($request->user_assigned_id))
            || (!Str::isUuid($request->id))) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid UUID format for user_requester_id, user_assigned_id or ticket id'
            ], 422);
        }

        $ticket = Ticket::find($request->id);

        if(!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket not found'
            ],404);
        }

        if(!$validationService->validate($request->user_requester_id, $request->user_assigned_id)) {
            return response()->json([
                'status' => 400,
                'message' => 'The requester and assigned IDs cannot be the same'
            ],400);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'user_requester_id' => 'required|exists:users,id|string',
            'user_assigned_id' => 'required|exists:users,id|string',
            'category' => 'required',
            'description' => 'required',
            'status' => ['required', 'in:open,in progress,resolved'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages()
            ], 422);
        }


        $ticket->update([
            'user_requester_id' => $request->user_requester_id,
            'user_assigned_id' => $request->user_assigned_id,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'update_at' => now()
        ]);

        return response()->json([
            'status' => 201,
            'ticket' => $ticket
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/tickets/{id}",
     *     summary="Delete a specific ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ticket ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ticket deleted"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ticket not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", format="int32"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if(!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket not found'
            ],404);
        }

        $ticket->destroy($id);

        return response()->json([
            'status' => 200,
            'message' => 'Ticket deleted'
        ],200);
    }
}
