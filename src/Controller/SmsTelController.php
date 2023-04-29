<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Rest\Client;



class SmsTelController extends AbstractController
{
   
    // rest of the controller code
    #[Route('/app-sms', name: 'app_sms', methods: ['POST','GET'])]
    public function sendSMS(Request $request)
    {
        $toPhoneNumber = $request->request->get('toPhoneNumber');
        $smsBody = $request->request->get('smsBody');
        
        $sid = 'AC994716dce6ecd7e7d811ba2941899bf2';
        $token = '68b4a985124be1ea5e709174b56f0cc3';
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            "+216".$toPhoneNumber,
            [
                'from' => '+16206477715', 
                'body' => $smsBody,
            ]
        );
      
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    
}