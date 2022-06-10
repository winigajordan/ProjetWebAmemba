<?php
/*
This call sends a message based on a template.
*/
namespace App\Service\Mail;

use Mailjet\Client;
use \Mailjet\Resources;

class Mail {
        
    private $api_key = "429492cd53254d9a81b4982846766008";
    private $api_key_privat = "8030de8de10528b1fd8ddcd748e73720";

    public function send($emailTo, $name, $subject, $content){
        $mj = new Client($this->api_key, $this->api_key_privat,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "winigajordan@gmail.com",
                        'Name' => "Winiga jordan"
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name' => $name
                        ]
                    ],
                    'TemplateID' => 3936720 ,
                    'TemplateLanguage' => true,
                    'Variables' => [
                        'content' => $content,
                        'objet' => $subject
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    
    }

    
}

?>