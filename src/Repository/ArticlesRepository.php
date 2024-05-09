<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    // Add your custom methods here

    // Custom method to find all articles
    public function findAllArticles()
    {
        return $this->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
    }


    public function searchByKeyword(string $keyword): array
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.title LIKE :keyword OR a.content LIKE :keyword')
            ->setParameter('keyword', "%$keyword%");

        return $qb->getQuery()->getResult();
    }

    public function filterByDates(array $articles, string $startDate, string $endDate): array
    {
        $filteredArticles = [];
        foreach ($articles as $article) {
            $articleCreatedDate = $article->getCreationDate(); // Assuming you have a "createdAt" property

            // Convert strings to DateTime objects for comparison
            $startDateObject = new \DateTime($startDate);
            $endDateObject = new \DateTime($endDate);
            $endDateObject->modify('+1 day'); // Include articles on the end date

            if ($articleCreatedDate >= $startDateObject && $articleCreatedDate < $endDateObject) {
                $filteredArticles[] = $article;
            }
        }
        return $filteredArticles;
    }
     /**
     * Sorts articles by creation date in descending order (newest first)
     *
     * @param array $articles Array of Articles entities
     * @return array Sorted array of Articles entities
     */
    public function sortByNewest(array $articles): array
    {
        usort($articles, function ($a, $b) {
            return $b->getCreationDate() <=> $a->getCreationDate();
        });
        return $articles;
    }

    /**
     * Sorts articles by creation date in ascending order (oldest first)
     *
     * @param array $articles Array of Articles entities
     * @return array Sorted array of Articles entities
     */
    public function sortByOldest(array $articles): array
    {
        usort($articles, function ($a, $b) {
            return $a->getCreationDate() <=> $b->getCreationDate();
        });
        return $articles;
    }
}
