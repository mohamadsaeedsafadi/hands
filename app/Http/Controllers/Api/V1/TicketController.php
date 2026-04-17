<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use App\Services\TicketService;
use App\Http\Requests\TicketRequest;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function create(TicketRequest $request)
    {
        
        $ticket = $this->ticketService->createTicket(Auth::id(), $request->validated());
        return response()->json($ticket);
    }

    public function reply($ticketId, TicketReplyRequest $request)
    {
       
        $response = $this->ticketService->replyToTicket(
    $ticketId,
    Auth::user(),
    $request->validated()
);
        return response()->json($response);
    }

    public function updateStatus($ticketId, UpdateTicketStatusRequest $request)
{
    $ticket = $this->ticketService->updateTicketStatus(
        $ticketId,
        $request->status,
        $request->version
    );

    return response()->json($ticket);
}

    public function show($reference)
    {
  
        $ticket = $this->ticketService->getTicketByReference($reference);
        return ApiResponse::success($ticket);
    }

    public function list(Request $request)
    {
        
        $tickets = $this->ticketService->filterTickets($request->all());
        return ApiResponse::success($tickets);
    }
    public function messages($ticketId)
{
    $messages = $this->ticketService->getTicketMessages($ticketId);
    return ApiResponse::success($messages);
}
}