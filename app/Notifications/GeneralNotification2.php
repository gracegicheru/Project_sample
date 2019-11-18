<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\NotificationMessage;

class GeneralNotification2 extends Notification
{
    use Queueable;



    protected $channels = ['database', 'mail'];

    protected $slug;
    protected $user;


    public function __construct($slug, $user=null)
    {

        $this->slug = $slug;
        $this->user = $user;

        $notification_message = Notification::where('slug', $slug)->first();
        if (!$notification_message) {
            return false;
            throw new \Exception('NotificationMessage message with slug ' . $slug . ' not found');
        }
        $this->notification_message = $notification_message;
        $channels = ['database'];
        if ($notification_message->sms && $notification_message->message) {
            $channels = ['database', 'nexmo'];
        } elseif ($notification_message->message) {
            $channels = ['database', 'mail'];
        } elseif ($notification_message->sms) {
            $channels = ['database', 'nexmo'];
        }

        $this->channels = $channels;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $this->replaceNotificationVars($notifiable);
        $channels = $this->channels;
        unset($channels['nexmo']);
        return $channels;

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $this->replaceNotificationVars($notifiable);
        if (!$this->notification_message->action_url)
            return (new MailMessage)
                ->greeting('Hello ' . $notifiable->email)
                ->subject($this->notification_message->subject)
                ->line(@$this->notification_message->message)
                ->line('Thank you');
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->email)
            ->subject($this->notification_message->subject)
            ->line(nl2br($this->notification_message->message))
            ->line(nl2br($this->dispute_reason))
            ->action($this->notification_message->action_text, url($this->notification_message->action_url))
            ->line('Thank you');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->replaceNotificationVars($notifiable);
        $array = [
            'message' => @$this->notification_message->message,
            'dispute' => $this->dispute_reason,
            'summary' => $this->notification_message->subject,
            'action_url' => $this->notification_message->action_url
        ];
        return $array;

    }

    public function replaceNotificationVars($notifiable)
    {

        if ($this->user) {
            $user_arr = $this->user->toArray();


            foreach (array_keys($user_arr) as $key) {
                @$this->notification_message->message = str_replace(['{user_' . $key . '}'], $user_arr[$key], $this->notification_message->message);
                @$this->notification_message->sms = str_replace('{user_' . $key . '}', $user_arr[$key], $this->notification_message->sms);
                @$this->notification_message->subject = str_replace('{user_' . $key . '}', $user_arr[$key], $this->notification_message->subject);
                @$this->notification_message->action_url = str_replace('{user_' . $key . '}', $user_arr[$key], $this->notification_message->action_url);
            }
        }



        $this->replaced = 1;
    }

    public function toNexmo($notifiable)
    {
        $this->replaceNotificationVars($notifiable);
        return (new NexmoMessage)
            ->content($this->notification_message->sms);
    }

    public function toAfricasTalking($notifiable)
    {
        $this->replaceNotificationVars($notifiable);

        return $this->notification_message->sms;
    }

}
