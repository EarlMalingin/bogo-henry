<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $tutor;
    public $daysRemaining;
    public $endDate;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $tutor, $daysRemaining, $endDate)
    {
        $this->student = $student;
        $this->tutor = $tutor;
        $this->daysRemaining = $daysRemaining;
        $this->endDate = $endDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Monthly Subscription Expiring Soon - MentorHub',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-expiring',
            with: [
                'student' => $this->student,
                'tutor' => $this->tutor,
                'daysRemaining' => $this->daysRemaining,
                'endDate' => $this->endDate,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
