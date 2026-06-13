<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    private string $token;
    private string $chatId;

    public function __construct()
    {
        $this->token  = config('services.telegram.bot_token', '');
        $this->chatId = config('services.telegram.chat_id', '');
    }

    public function sendContactNotification(
        string $senderName,
        string $senderEmail,
        string $subject,
        string $message
    ): void {
        if (empty($this->token) || empty($this->chatId)) {
            Log::warning('Telegram notification skipped: TELEGRAM_BOT_TOKEN or TELEGRAM_CHAT_ID not set.');
            return;
        }

        $text = "📩 *New Portfolio Message*\n\n"
              . "👤 *Name:* " . $this->escape($senderName) . "\n"
              . "📧 *Email:* " . $this->escape($senderEmail) . "\n"
              . "📌 *Subject:* " . $this->escape($subject) . "\n\n"
              . "💬 *Message:*\n" . $this->escape($message);

        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id'    => $this->chatId,
                'text'       => $text,
                'parse_mode' => 'MarkdownV2',
            ]);
        } catch (\Throwable $e) {
            // Don't crash the request if Telegram is down
            Log::error('Telegram notification failed: ' . $e->getMessage());
        }
    }

    /** Escape special characters required by MarkdownV2 */
    private function escape(string $text): string
    {
        $specialChars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($specialChars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        return $text;
    }
}
