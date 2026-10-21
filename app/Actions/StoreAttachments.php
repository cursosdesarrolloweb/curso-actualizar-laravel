<?php

namespace App\Actions;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class StoreAttachments
{
    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function handle(Ticket $ticket, User $uploader, array $files): void
    {
        foreach ($files as $file) {
            $attachment = $ticket->attachments()->make([
                'path' => $file->store("attachments/{$ticket->id}", 'local'),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $attachment->user()->associate($uploader);
            $attachment->save();
        }
    }
}
