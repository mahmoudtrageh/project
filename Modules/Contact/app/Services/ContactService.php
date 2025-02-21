<?php

namespace Modules\Contact\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Contact\Models\Contact;

class ContactService
{
    public function __construct(protected Contact $model) {}

    public function getAllContacts(int $perPage = 1): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function createContact(array $data): Contact
    {
        return $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'new'
        ]);
    }
}