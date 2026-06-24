<?php

namespace App\Service\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class UserHandler
{
  public function __construct(
    private UserRepository $userRepository,
    private EntityManagerInterface $em,
    private UserPasswordHasherInterface $passwordHasher,
    private ResetPasswordHelperInterface $resetPasswordHelper,
    private MailerInterface $mailer,
  )
  {
  }

  // Récupère tous les utilisateurs
  public function getAllUsers(): array
  {
    return $this->userRepository->findAll();
  }

  // Récupère un utilisateur par son id
  public function getUserById(int $id): User
  {
    $user = $this->userRepository->find($id);

    if (!$user) {
      throw new NotFoundHttpException(
        sprintf('Utilisateur #%d introuvable.', $id)
      );
    }

    return $user;
  }

  // Création et modification
  public function save(User $user, FormInterface $form, bool $isEdit): void
  {
    $plainPassword = $form->get('plainPassword')->getData();

    // Hash le mot de passe uniquement si un nouveau a été saisi
    // En modification on peut laisser vide pour conserver l'ancien
    if ($plainPassword) {
      $user->setPassword(
        $this->passwordHasher->hashPassword($user, $plainPassword)
      );
    }

    // Compte actif par défaut à la création
    if (!$isEdit) {
      $user->setActive(true);

    // Mot de passe temporaire aléatoire si non saisi
      if (!$plainPassword) {
        $user->setPassword(
          $this->passwordHasher->hashPassword($user, bin2hex(random_bytes(16)))
        );
      }
    }

    $this->em->persist($user);
    $this->em->flush();

    // Envoie l'email de définition de mot de passe à la création
        if (!$isEdit) {
            $this->sendPasswordSetupEmail($user);
        }
  }

  private function sendPasswordSetupEmail(User $user): void
    {
        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);

            $email = (new TemplatedEmail())
                ->from(new Address(
                    'noreply@qualitycheck-packman.local',
                    'QualityCheck Packman Administrateur'
                ))
                ->to($user->getMatricule() . '@qualitycheck-packman.local')
                ->subject('Définition de votre mot de passe — QualityCheck Packman')
                ->htmlTemplate('reset_password/email.html.twig')
                ->context([
                    'resetToken' => $resetToken,
                ])
            ;

            $this->mailer->send($email);

        } catch (\Exception $e) {
            // Si l'envoi échoue on ne bloque pas la création de l'utilisateur
        }
    }

  
  // Suppression avec protection auto-suppression
  public function delete(int $id, User $currentUser): void
  {
    $user = $this->getUserById($id);

    // Empêche l'admin de se supprimer lui-même
    if ($user->getId() === $currentUser->getId()) {
        throw new \LogicException(
          'Vous ne pouvez pas supprimer votre propre compte.'
        );
    }

    $this->em->remove($user);
    $this->em->flush();
  }
}