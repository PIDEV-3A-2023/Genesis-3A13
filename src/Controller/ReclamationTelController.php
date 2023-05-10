<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReclamationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Twilio\Rest\Client;




class ReclamationTelController extends AbstractController
{
    
    #[Route('/afficheMobile', name: 'app_reclamation_json', methods: ['GET'])]
    public function indexjson(ReclamationRepository $reclamationRepository,NormalizerInterface $normalizer): Response
    {
        $rec=$reclamationRepository->findAll() ;
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($rec);
        return new JsonResponse($formatted);

    }

    #[Route('/ajoutMobile', name: 'app_reclamation_jsonAjouter', methods: ['GET','POST'])]
    public function indexjsonAjouter(Request $request)
    {
    $Reclamation = new Reclamation();
$message = $request->query->get('message');
$feedback = $request->query->get('feedback');


$em = $this->getDoctrine()->getManager();

$Reclamation->setMessage($message);
$Reclamation->setFeedback($feedback);

$em-> persist($Reclamation);
$em-> flush();


$serializer = new Serializer([new ObjectNormalizer()]);
$formatted = $serializer->normalize($Reclamation);
$sid    = "AC994716dce6ecd7e7d811ba2941899bf2";
$token  = "68b4a985124be1ea5e709174b56f0cc3";
    // Send SMS notification using Twilio
    $sid = 'AC994716dce6ecd7e7d811ba2941899bf2';
    $token = '68b4a985124be1ea5e709174b56f0cc3';
    $twilioNumber = '+16206477715'; // Enter your Twilio phone number here

    $client = new Client($sid, $token);
    $message = $client->messages->create(
        '+21626620734', // Enter the recipient's phone number here
        [
            'from' => $twilioNumber,
            'body' => 'Votre réclamation a été envoyée.'
        ]
    );
return new JsonResponse($formatted);


    }


    #[Route('/ligne/updateEventJSON/{id}', name: "updateEventJSON")]
    public function updateLigneJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $ligne = $em->getRepository(Ligne::class)->find($id);
        $ligne->setNomLigne($req->get('nom_ligne'));
        $ligne->setTypeLigne($req->get('type_ligne'));
        $em->flush();

        $jsonContent = $Normalizer->normalize($ligne, 'json', ['groups' => 'ligne']);
        $sid    = "";
        $token  = "";
        $client = new Client($sid,$token);
 
         $message = $client->messages
         ->create("whatsapp:", // replace with admin's phone number
             [
               'from' => 'whatsapp:', // replace with your Twilio phone number
                'body' => 'New update has been done ' ,
             ]
         );
        return new Response("Ligne updated successfully " . json_encode($jsonContent));
    }


    #[Route('/deleteMobile', name: 'app_mobile_delete', methods: ['GET','DELETE'])]
    public function deleteMobile(Request $request)
    {
        $id=$request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        if ($Reclamation != null) {
            $em->remove($Reclamation);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("Reclamation supprimée.");
            return new JsonResponse($formatted);

        }
    } 


    #[Route('/updateMobile', name: 'app_mobile_update', methods: ['PUT','GET'])]
    public function modifierReclamation(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()
                        ->getRepository(Reclamation::class)
                        ->find($request->get("id"));

        $reclamation->setMessage($request->get("message"));
        $reclamation->setfeedback($request->get("feedback"));


        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("reclamation a ete modifiee avec success.");

    }




    



}
