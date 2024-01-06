<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Customer;
use App\Models\Room;

class NewRoomServiceNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Room $room, String $query)
    {
        $this->customer = $customer;
        $this->room = $room;
        $this->query = $query;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [// 'mail',
            'database',
            'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->customer->name . ' in Room ' . $this->room->number . ' needs room service for ' . $this->query . '.',
            'url' => route('ticket.index', ['customer' => $this->customer->id, 'room' => $this->room->id, 'query' => $this->query])
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->customer->name . ' in Room ' . $this->room->number . ' needs room service for ' . $this->query . '.',
            'url' => route('ticket.index', ['customer' => $this->customer->id, 'room' => $this->room->id, 'query' => $this->query])
        ]);
    }
}
