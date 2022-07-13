<?php


namespace Post\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Post\Model\Entities\User;

class UserRepository extends EntityRepository
{
    public function findByLogin(string $login): ?User
    {
        return $this->findOneBy(['login' => $login]);
    }

    public function findByLoginAndPassword(string $login, string $password): ?User
    {
        return $this->findOneBy(['login' => $login, 'password' => $password]);
    }
}