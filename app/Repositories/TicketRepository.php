<?php
namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{
    public function create($data)
    {
        return Ticket::create($data);
    }

    public function getUserTickets($userId)
    {
        return Ticket::where('user_id', $userId)->latest()->paginate(10);
    }

    public function getAll($filters)
    {
        $query = Ticket::query();

        if(isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if(isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if(isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->latest()->paginate(10);
    }

    public function find($id)
    {
        return Ticket::findOrFail($id);
    }

    public function update($ticket, $data)
    {
        $ticket->update($data);
        return $ticket;
    }
}