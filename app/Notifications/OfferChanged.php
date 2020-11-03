<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $offer;
    public $notification_title;
    public function __construct($notification_title, $offer)
    {
        $this->offer = $offer;
        $this->notification_title = $notification_title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
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
                    ->action('Notification Action', url('home/offer/'.$this->offer->id.'/edit'))
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
            'notification_title' => $this->notification_title,
            'url' => url('home/offer/'.$this->offer->id.'/edit'),
            'time' => 'just now',

        ];

    }

    public function broadCastType(){
        return 'offer.changed';
    }


    public function toDatabase($notifiable){
        return [
            'notification_title' => $this->notification_title,
            'url' => url('home/offer/'.$this->offer->id.'/edit'),
            'time' => 'just now',

        ];
    }


}
