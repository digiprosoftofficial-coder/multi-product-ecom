<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->forceFill(['is_read' => true])->save();
        }
    }
}
