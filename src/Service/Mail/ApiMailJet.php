<?php
/*
This call sends a message based on a template.
*/
namespace App\Service\Mail;

use App\Service\Api;
use Mailjet\Client;
use \Mailjet\Resources;

class ApiMailJet {
    
    private $api_key = "f5c2029d0b7eaaaf3e35dcb28a409c53";
    private $api_key_privat = "3b0f58a74088b3a329d0a0316792719c";

    public function send($emailTo, $name, $subject, $content){
        $mj = new Client($this->api_key, $this->api_key_privat,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "services@mariamaba-alumni.com",
                        'Name' => "AMEMBA"
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name' => $name
                        ]
                    ],
                    'TemplateID' => 4079681 ,
                    'TemplateLanguage' => true,
                    'Variables' => [
                        'content' => $content,
                        'objet' => $subject
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //$response->success() && var_dump($response->getData());
        //dd($response->getData());
    
    }


    public function part($emailTo, $name, $content){
        $mj = new Client($this->api_key, $this->api_key_privat,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "services@mariamaba-alumni.com",
                        'Name' => "AMEMBA"
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name' => $name
                        ]
                    ],
                    'TemplateID' => 4079668 ,
                    'TemplateLanguage' => true,
                    'Variables' => [
                        'content' => $content,
                        'name' => $name
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //$response->success() && var_dump($response->getData());
        //dd($response->getData());
    
    }

    public function news($emailTo, $name, $titre, $content){
        $mj = new Client($this->api_key, $this->api_key_privat,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "services@mariamaba-alumni.com",
                        'Name' => "AMEMBA"
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name' => $name
                        ]
                    ],
                    'TemplateID' => 4079693 ,
                    'TemplateLanguage' => true,
                    'Variables' => [
                        'content' => $content,
                        'titre' => $titre
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //$response->success() && var_dump($response->getData());
        //dd($response->getData());
    
    }

    
}

?>