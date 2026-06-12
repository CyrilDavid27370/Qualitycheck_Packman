<?php

namespace App\Service\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHandler
{
  public function __construct(
    private UserRepository $userRepository,
    private EntityManagerInterface $em,
    private UserPasswordHasherInterface $passwordHasher,
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
    }

    $this->em->persist($user);
    $this->em->flush();
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