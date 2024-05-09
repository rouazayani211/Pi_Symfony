<?php
// src/Repository/JournalistsRepository.php

namespace App\Repository;

use App\Entity\Journalists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Journalists|null find($id, $lockMode = null, $lockVersion = null)
 * @method Journalists|null findOneBy(array $criteria, array $orderBy = null)
 * @method Journalists[]    findAll()
 * @method Journalists[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalistsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journalists::class);
    }

    // Add your custom methods here

    // Custom method to find all journalists
    public function findAllJournalists()
    {
        return $this->createQueryBuilder('j')
            ->getQuery()
            ->getResult();
    }

    

    /**
     * Find a journalist by name.
     *
     * @param string $name The name of the journalist to find.
     * @return Journalist|null The journalist entity or null if not found.
     */
    public function findOneByName(string $name): ?Journalists
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
