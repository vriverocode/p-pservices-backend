<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $resetUrl;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:8051');
        $this->resetUrl = $frontendUrl . '/reset-password?token=' . urlencode($token) . '&email=' . urlencode($user->email);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Restablece tu contraseña / Reset your password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
        );
    }
}
