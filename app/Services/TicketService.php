<?php
namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function createTicket($userId, array $data)
    {
        
        $reference = 'TCK-' . strtoupper(Str::random(8));

       
        $ticket = Ticket::create([
            'reference' => $reference,
            'user_id' => $userId,
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
        ]);

        
        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $file) {
              $at=  TicketAttachment::create([
                    'attachable_id' => $ticket->id,
                    'attachable_type' => Ticket::class,
                    'file_path' => $file->store('tickets', 'public')
                ]);
            }
            return [$ticket , $at ];
        }

        return $ticket;
    }

   public function replyToTicket($ticketId, $sender, $data)
{
    $ticket = Ticket::findOrFail($ticketId);

    
    if (empty($data['message']) && empty($data['attachments'])) {
        throw new Exception("يجب إرسال رسالة أو ملف على الأقل");
    }

    DB::beginTransaction();

    $message = TicketMessage::create([
        'ticket_id' => $ticket->id,
        'sender_id' => $sender->id,
        'sender_type' => get_class($sender),
        'message' => $data['message'] ?? null
    ]);

    
    if (!empty($data['attachments'])) {
        foreach ($data['attachments'] as $file) {
            TicketAttachment::create([
                'attachable_id' => $message->id,
                'attachable_type' => TicketMessage::class,
                'file_path' => $file->store('tickets/messages', 'public')
            ]);
        }
    }

   
    if ($ticket->status !== 'resolved') {
        $ticket->update(['status' => 'waiting_user']);
    }

    DB::commit();

    return $message->load('attachments', 'sender');
}

    public function updateTicketStatus($ticketId, $status, $version)
{
    $updated = Ticket::where('id', $ticketId)
        ->where('version', $version)
        ->update([
            'status' => $status,
            'version' => DB::raw('version + 1')
        ]);

    if (!$updated) {
        throw new \Exception("تم تعديل الشكوى من قبل شخص آخر");
    }

    return Ticket::find($ticketId);
}

    public function getTicketByReference($reference)
    {
       
        $ticket = Ticket::where('reference', $reference)->firstOrFail();
        return $ticket;
    }

    public function filterTickets(array $filters)
    {
        $query = Ticket::query();

       
        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

      
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
         if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

      
        if (isset($filters['from']) && isset($filters['to'])) {
            $query->whereBetween('created_at', [$filters['from'], $filters['to']]);
        }

       
        return $query->get();
    }
    public function getTicketMessages($ticketId)
{
    return TicketMessage::with(['sender', 'attachments'])
        ->where('ticket_id', $ticketId)
        ->orderBy('created_at')
        ->get();
}
}