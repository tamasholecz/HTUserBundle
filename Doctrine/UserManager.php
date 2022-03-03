<?php

namespace HT\UserBundle\Doctrine;

use HT\UserBundle\Entity\HTUserInterface;
use HT\UserBundle\Model\UserManager as BaseUserManager;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends BaseUserManager
{
    protected $em;

    public function __construct(EntityManagerInterface $em, string $userClass)
    {
        $this->em = $em;
        $this->userClass = $userClass;
    }

    public function findUserBy(array $criteria): ?HTUserInterface
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    protected function getRepository()
    {
        return $this->em->getRepository($this->userClass);
    }

    public function updateUser(HTUserInterface $user, $andFlush = true): void
    {
        $this->em->persist($user);
        if ($andFlush) {
            $this->em->flush();
        }
    }
}
