<?php

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
class Webpush_lib
{
    public $webpush;
    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject'       =>  'Send Notifications',
                'publicKey'     =>  NOTIFICATION_PUBLIC_KEY,
                'privateKey'    =>  NOTIFICATION_PRIVATE_KEY
            ],
        ];

        $this->webpush = new WebPush($auth);
    }

    public function broadcast_notifications($payload=null) {

        $filters = [];
        $filters['filter'] = ['status'=>1];
        $notifications = _get_module('notifications/pushes','_search',$filters);

        if($notifications) {
            foreach ($notifications as $notification) {

                $message = Subscription::create([
                    'endpoint'  => $notification['endpoint'],
                    'publicKey' => $notification['key_p256db'],
                    'authToken' => $notification['key_auth'],
                    ]);

                $this->webpush->sendNotification(
                    $message,
                    json_encode($payload)
                );
            }

            foreach ($this->webpush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    //echo "[v] Message sent successfully for subscription {$endpoint}.";
                } else {
                    //echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
                }
            }

        }
    }
}
