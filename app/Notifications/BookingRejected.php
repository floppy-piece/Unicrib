<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingRejected extends Notification
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
        return ['mail'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        /*return (new MailMessage)
            ->line('Your booking has been rejected and a refund of your fee made, try booking another property.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');*/
            $status = ucfirst($this->booking->status);
            return (new MailMessage)
                ->subject("Booking #{$this->booking->id} is {$status}")
                ->line("Your booking for {$this->booking->property->title} is now {$status}.")
                ->line("Your booking has been rejected and refund of you money has been initiated.You will recieve your refund in a period of seven days.")
                ->when($this->booking->status === Booking::STATUS_REJECTED, fn() => 
                    (new MailMessage)->line("Your Booking Has been rejected!! \n
                     Reason: {$this->booking->rejection_reason} Currently there are some issues with the room!! Refund of you money has been initiated.You will recieve your full refund in a period of seven days."))
                ->action('View Booking', url('/'))
                ->line('Thank you for using our application and trusting us!');//route('/dashboard', $this->booking));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        
        return [
            'type'        => 'booking_rejected',
            'property_id' => $this->booking->property_id,
            'property'    => $this->booking->property->title,
            //'message'     => 'This room is full or your booking was rejected.',
            'booking_id' => $this->booking->id,
            'status'     => $this->booking->status,
            'message'    => "Booking #{$this->booking->id} is {$this->booking->status}",
        ];
        
    }
}
