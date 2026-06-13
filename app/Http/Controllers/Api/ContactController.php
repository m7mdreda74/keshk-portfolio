<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 1. Save to DB
        ContactMessage::create($validated);

        // 2. Send Email notification
        try {
            Mail::to(env('OWNER_EMAIL', 'm7mdreda74@gmail.com'))
                ->send(new ContactFormMail(
                    senderName:  $validated['name'],
                    senderEmail: $validated['email'],
                    mailSubject: $validated['subject'],
                    body:        $validated['message'],
                ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Email notification failed: ' . $e->getMessage());
        }

        // 3. Send Telegram notification (instant)
        (new TelegramNotificationService())->sendContactNotification(
            senderName:  $validated['name'],
            senderEmail: $validated['email'],
            subject:     $validated['subject'],
            message:     $validated['message'],
        );

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully. Thank you!',
        ], 201);
    }
}
