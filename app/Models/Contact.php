<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read',
        'read_at',
        'is_archived',
        'archived_at',
        'is_spam',
        'spam_at',
        'created_at',
        'updated_at',
    ];

    protected static function booted()
{
    static::updating(function ($contact) {
        if ($contact->isDirty('is_read') && $contact->is_read) {
            $contact->read_at = now();
        }
        if ($contact->isDirty('is_archived') && $contact->is_archived) {
            $contact->archived_at = now();
        }
        if ($contact->isDirty('is_spam') && $contact->is_spam) {
            $contact->spam_at = now();
        }
    });
}

}
