<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\InputBag;

class Discord
{
    public $message;

    public $to;

    public $name;

    public function __construct($message, $to = false, $name = 'SkvadBot')
    {
        $this->message = $message;
        $this->to = $to;
        $this->name = $name;

        $this->send();
    }

    public function send()
    {
        $webhookurl = app()->environment('live') ?
            'https://discordapp.com/api/webhooks/767671022861549608/8WtOMbvWN2nwBYRq3MAGcGyQHwoXmk1B3Lfu2IptGFLWDheHIiCMuR8fB2QnvfH7W1tt' :
            'https://discord.com/api/webhooks/843856446156177408/oIPLmShuXIkw-rvenmShhYqV7hCq5FJ1tvBg5t7mA0F4mA3d1d94PHzTU9H6pCmEZAdk';

        $timestamp = date('c', strtotime('now'));
        $trace = '';

        if (is_array($this->message)) {
            if (is_a($this->message['query'], InputBag::class)) {
                $this->message['query'] = json_encode($this->message['query']);
            }
            $message = implode(' | ', $this->message);
        } else {
            $message = $this->message;
        }

        $json_data = json_encode([
            // Message
            'content' => (! empty($this->to) ? '@'.$this->to.' ' : '').$message,

            // Username
            'username' => $this->name,

            // Avatar URL.
            // Uncoment to replace image set in webhook
            //"avatar_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=512",

            // Text-to-speech
            'tts' => false,

            // File upload
            // "file" => "",

            // Embeds Array
            /*"embeds" => [
        [
        // Embed Title
        "title" => "PHP - Send message to Discord (embeds) via Webhook",

        // Embed Type
        "type" => "rich",

        // Embed Description
        "description" => "Description will be here, someday, you can mention users here also by calling userID <@12341234123412341>",

        // URL of title link
        "url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",

        // Timestamp of embed must be formatted as ISO8601
        "timestamp" => $timestamp,

        // Embed left border color in HEX
        "color" => hexdec( "3366ff" ),

        // Footer
        "footer" => [
        "text" => "GitHub.com/Mo45",
        "icon_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=375"
        ],

        // Image to send
        "image" => [
        "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=600"
        ],

        // Thumbnail
        //"thumbnail" => [
        //    "url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=400"
        //],

        // Author
        "author" => [
        "name" => "krasin.space",
        "url" => "https://krasin.space/"
        ],

        // Additional Fields array
        "fields" => [
        // Field 1
        [
        "name" => "Field #1 Name",
        "value" => "Field #1 Value",
        "inline" => false
        ],
        // Field 2
        [
        "name" => "Field #2 Name",
        "value" => "Field #2 Value",
        "inline" => true
        ]
        // Etc..
        ]
        ]
        ]*/

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init($webhookurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
        // echo $response;
        curl_close($ch);
    }
}
