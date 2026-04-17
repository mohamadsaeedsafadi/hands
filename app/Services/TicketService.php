<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketAttachment;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TicketService
{
    private function ticketReferenceKey($reference)
    {
        return "ticket:reference:{$reference}";
    }

    private function ticketMessagesKey($ticketId)
    {
        return "ticket:messages:{$ticketId}";
    }

    private function ticketsFilterKey($filters)
    {
        return "tickets:filter:" . md5(json_encode($filters));
    }

    /* =========================
       CREATE TICKET
    ========================= */
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

        // Invalidate related caches (important)
        Cache::forget($this->ticketsFilterKey([]));

        if (!empty($data['attachments'])) {
            foreach ($data['attachments'] as $file) {
                TicketAttachment::create([
                    'attachable_id' => $ticket->id,
                    'attachable_type' => Ticket::class,
                    'file_path' => $file->store('tickets', 'public')
                ]);
            }
        }

        return $ticket;
    }

    /* =========================
       REPLY
    ========================= */
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

        // Invalidate cache properly
        Cache::forget($this->ticketMessagesKey($ticketId));

        return $message->load('attachments', 'sender');
    }

    /* =========================
       UPDATE STATUS
    ========================= */
    public function updateTicketStatus($ticketId, $status, $version)
    {
        $updated = Ticket::where('id', $ticketId)
            ->where('version', $version)
            ->update([
                'status' => $status,
                'version' => DB::raw('version + 1')
            ]);

        if (!$updated) {
            throw new Exception("تم تعديل الشكوى من قبل شخص آخر");
        }

        Cache::forget($this->ticketMessagesKey($ticketId));

        return Ticket::find($ticketId);
    }

    /* =========================
       GET BY REFERENCE
    ========================= */
    public function getTicketByReference($reference)
    {
        return Cache::remember(
            $this->ticketReferenceKey($reference),
            300,
            function () use ($reference) {
                return Ticket::where('reference', $reference)
                    ->firstOrFail();
            }
        );
    }

    /* =========================
       FILTER TICKETS
    ========================= */
    public function filterTickets(array $filters)
    {
        $page = request('page', 1);

        $key = $this->ticketsFilterKey([
            ...$filters,
            'page' => $page
        ]);

        return Cache::remember($key, 300, function () use ($filters) {

            $query = Ticket::query();

            if (!empty($filters['priority'])) {
                $query->where('priority', $filters['priority']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }

            if (!empty($filters['from']) && !empty($filters['to'])) {
                $query->whereBetween('created_at', [
                    $filters['from'],
                    $filters['to']
                ]);
            }

            return $query->paginate(10);
        });
    }

    /* =========================
       MESSAGES
    ========================= */
    public function getTicketMessages($ticketId)
    {
        return Cache::remember(
            $this->ticketMessagesKey($ticketId),
            300,
            function () use ($ticketId) {
                return TicketMessage::with(['sender', 'attachments'])
                    ->where('ticket_id', $ticketId)
                    ->orderBy('created_at')
                    ->paginate(20);
            }
        );
    }
}