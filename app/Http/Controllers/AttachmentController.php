<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    public function show(Attachment $attachment): StreamedResponse
    {
        $this->authorize('view', $attachment->ticket);

        return Storage::disk('local')->download($attachment->path, $attachment->original_name);
    }
}
