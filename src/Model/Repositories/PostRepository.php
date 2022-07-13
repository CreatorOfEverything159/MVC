<?php

namespace Post\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Post\Model\Entities\Post;

class PostRepository extends EntityRepository
{
    public function findById(string $id): ?Post
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getAll(): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id', 'u.login', 'p.title', 'p.content')
            ->innerJoin('p.user', 'u');
        return $query->getQuery()->getArrayResult();
    }
}