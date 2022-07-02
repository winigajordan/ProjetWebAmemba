<?php

namespace App\Service\Mail;

class ApiMailVerification{
    
    public function test($mail){
            // Initialize cURL.
        $ch = curl_init();

        // Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, "https://emailvalidation.abstractapi.com/v1/?api_key=8d72020e303a4245b7378aa6e6fe30c4&email=$mail");

        // Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Execute the request.
        $data = curl_exec($ch);

        // Close the cURL handle.
        curl_close($ch);

        // Print the data out onto the page.
        return json_decode($data, true);
    }
}