<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Customer;
use App\Models\Room;

class NewFoodOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Room $room, String $totalPrice, array $orderItems)
    {
        $this->customer = $customer;
        $this->room = $room;
        $this->totalPrice = $totalPrice;
        $this->orderItems = $orderItems;
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
        $orderedItemsText = '';

        foreach ($this->orderItems as $item) {
            $orderedItemsText .= $item['name'] . ' (Quantity: ' . $item['quantity'] . '), ';
        }

        $orderedItemsText = rtrim($orderedItemsText, ', '); // Remove trailing comma and space

        return [
            'message' => $this->customer->name . ' in Room ' . $this->room->number . ' placed an order for food worth ' . $this->totalPrice . '. Ordered items include: ' . $orderedItemsText,
            'url' => route('order.index', ['customer' => $this->customer->id, 'room' => $this->room->id, 'query' => $orderedItemsText])
        ];
    }

    public function toBroadcast($notifiable)
    {
        $orderedItemsText = '';

        foreach ($this->orderItems as $item) {
            $orderedItemsText .= $item['name'] . ' (Quantity: ' . $item['quantity'] . '), ';
        }

        $orderedItemsText = rtrim($orderedItemsText, ', '); // Remove trailing comma and space

        return new BroadcastMessage([
            'message' => $this->customer->name . ' in Room ' . $this->room->number . ' placed an order for food worth ' . $this->totalPrice . '. Ordered items include: ' . $orderedItemsText,
            'url' => route('order.index', ['customer' => $this->customer->id, 'room' => $this->room->id, 'query' => $orderedItemsText])
        ]);
    }
}
