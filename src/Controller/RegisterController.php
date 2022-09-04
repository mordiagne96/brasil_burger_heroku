<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use Namshi\JOSE\JWT;
use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegisterController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private UserPasswordHasherInterface $passwordHasher;
    private JWTTokenManagerInterface $JWTManager;
    private TokenStorageInterface $tokenStorageInterface;
     

    public function __construct(EmailVerifier $emailVerifier,UserPasswordHasherInterface $passwordHasher,JWTTokenManagerInterface $JWTManager,TokenStorageInterface $tokenStorageInterface)
    {
        $this->emailVerifier = $emailVerifier;
        $this->passwordHasher = $passwordHasher;
        $this->JWTManager = $JWTManager;
        $this->tokenStorageInterface= $tokenStorageInterface;
    }

    public function __invoke(Request $request,
    ValidatorInterface $validator,
    SerializerInterface $serializer,
    EntityManagerInterface $entityManager): JsonResponse
    {
        $client = $serializer->deserialize($request->getContent(),
        Client::class,'json');
        $errors = $validator->validate($client);

        if (count($errors) > 0) {
            $errorsString =$serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString
            ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $hashedPassword = $this->passwordHasher->hashPassword(
            $client,
            'passer'
            );
            $client->setPassword($hashedPassword);

        $entityManager->persist($client);
        $entityManager->flush();
        $datetime = new DateTime();
        $token = base64_encode($client->getLogin());
        $date = base64_encode(json_encode($datetime));
        
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $client,
                (new TemplatedEmail())
                    ->from(new Address('morradiagne1960@gmail.com', 'Koni Bot'))
                    ->to($client->getUserIdentifier())
                    ->subject('Veuillez confirmer votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context([
                        "token"=>$token,
                        "date"=>$date]
                    )
            );
    // $burger->setGestionnaire($tokenStorage->getToken()->getUser());
    
    $result =$serializer->serialize(
        [
            'code'=>Response::HTTP_CREATED,
            'data'=>$client
        ],
        "json",[
            "groups"=>["all"]
        ]
    );
        return new JsonResponse($result ,Response::HTTP_CREATED,[],true);
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, ClientRepository $repo): JsonResponse
    {
        $dateToken = json_decode(base64_decode($request->query->get("date")));
        $emailToken = json_decode(base64_decode($request->query->get("token")));
        $dateActuel = new DateTime();
        // dd(strtotime($dateToken->date)- strtotime($dateActuel->format()));
        $diff_second = strtotime($dateActuel->format("d-m-Y H:i:s")) - strtotime($dateToken->date);
        
        if($diff_second < 3600){
            $client = $repo->findOneBy(["login" => $emailToken]);  
            return "Token Valid";  
        }else{
            return "Token is invalid";
        }

        dd(strtotime($dateActuel->format("d-m-Y H:i:s")) - strtotime($dateToken->date));
        dd(strtotime($dateActuel->format("d-m-Y H:i:s")));
        // $date = $dateToken->date;

        

        $token = explode("token=",$request->getRequestUri());
        $tokenHeader = base64_decode($request->query->get("token"));
        // dd($tokenHeader);
        $jwtHeader = json_decode($tokenHeader);
        
        // dd($this->JWTManager->decode($this->tokenStorageInterface->getToken()));
        // dd();

        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        

        return new JsonResponse("" ,Response::HTTP_CREATED,[],true);
    }
}
