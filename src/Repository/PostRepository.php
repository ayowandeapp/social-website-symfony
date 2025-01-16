<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator, )
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPosts(int $page, int $limit): PaginationInterface
    {
        // $dql = "SELECT a FROM AcmeMainBundle:Article a";
        // $query = $em->createQuery($dql);
        $query = $this->createQueryBuilder('posts')
            ->leftJoin('posts.user', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();

        return $this->paginator->paginate(
            $query, /* query NOT result */
            $page,
            $limit
        );
    }

    public function findAllUserPosts(int $page, int $limit, $userId): PaginationInterface
    {
        // $dql = "SELECT a FROM AcmeMainBundle:Article a";
        // $query = $em->createQuery($dql);
        $query = $this->createQueryBuilder('posts')
            ->leftJoin('posts.user', 'u')
            ->where('posts.user = :val')
            ->setParameter('val', $userId)
            ->addSelect('u')
            ->getQuery()
            ->getResult();

        return $this->paginator->paginate(
            $query, /* query NOT result */
            $page,
            $limit
        );
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
