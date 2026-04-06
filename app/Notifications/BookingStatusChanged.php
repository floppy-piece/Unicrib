<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $status = ucfirst($this->booking->status);
        return (new MailMessage)
            ->subject("Booking #{$this->booking->id} is {$status}")
            ->line("Your booking for {$this->booking->property->title} is now {$status}.")
            ->when($this->booking->status === Booking::STATUS_REJECTED, fn() => 
                (new MailMessage)->line("Reason: {$this->booking->rejection_reason}"))
            ->action('View Booking', route('bookings.show', $this->booking));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            
            'booking_id' => $this->booking->id,
            'status'     => $this->booking->status,
            'message'    => "Booking #{$this->booking->id} is {$this->booking->status}",
           
        ];
    }

}
