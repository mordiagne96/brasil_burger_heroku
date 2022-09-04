<?php

namespace App\DataPersister;

use App\Entity\User;
use Twig\Environment;
use App\Entity\BlogPost;
use App\Services\FileService;
use App\Services\MailerService;
use App\Controller\MailerController;
use App\Services\SetRoleUserService;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\PasswordhasherRoleService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserDataPersister implements DataPersisterInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private PasswordhasherRoleService $serviceHasher;
    private EntityManagerInterface $entityManager;
    private ?TokenInterface $token;
    private $fileService;
    private SetRoleUserService $setRoleUserService;
    // private Environment $twig;
    public function __construct(UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager,TokenStorageInterface $tokenStorage,PasswordhasherRoleService $serviceHasher,SetRoleUserService $setRoleUserService)
    {
        $this->passwordHasher= $passwordHasher;
        $this->entityManager = $entityManager;
        $this->serviceHasher = $serviceHasher;
        $this->setRoleUserService = $setRoleUserService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User; 
    }

    public function persist($data, array $context = [])
    {
        $data = $this->setRoleUserService->setRoleUser($data);
        // dd($data);

            // $hashedPassword = $this->passwordHasher->hashPassword(
            // $data,
            // 'passer'
            // );
            // $data->setPassword($hashedPassword);

            // $service = new MailerService($this->mailer, $this->twig);
            // $service->sendEmail($this->mailer, $this->twig);

            $data = $this->serviceHasher->hasherPassword($data);
            $data->setEtat("Disponible");
            // dd($data);
            $this->entityManager->persist($data);
            $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
    }
}