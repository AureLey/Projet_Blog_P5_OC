<?php

namespace Aleyg\Core\Mail;



class Mailer {

    function sendEmail($request)
    {
        $destinationAdress = "a.leygnac@gmail.com";
        $subject ='Demande de contact';
        $mailHeader =   "From : "  .$request->request->get('name')." "
                                .$request->request->get('surname')."
                        Contenu "  .$request->request->get('content').", pouvant être contacter à l'adresse" 
                                .$request->request->get('email');
        $headers =  'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

        mail($destinationAdress,$subject,$mailHeader,$headers);
    }

}