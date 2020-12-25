<?php

namespace RoelReijn\SmsApi\Notifications;

use RoelReijn\SmsApi\Notifications\SmsApiMessage;
use RoelReijn\SmsApi\SmsApi;
use Illuminate\Notifications\Notification;

class SmsApiChannel
{
    /** @var Client */
    protected $client;

    /**
     * @param SmsApi $client
     */
    public function __construct(SmsApi $client) {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     * @throws \RoelReijn\SmsApi\Exception\InvalidMethodException
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $mobile = $notifiable->routeNotificationFor('sms_api')) {
            return;
        }

        $message = $notification->toSmsApi($notifiable);

        if (is_string($message)) {
            $message = new SmsApiMessage($message);
        }

        $this->client->sendMessage($mobile,$message->content,$message->params,$message->headers);
    }
}