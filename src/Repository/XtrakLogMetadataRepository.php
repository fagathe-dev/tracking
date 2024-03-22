<?php

namespace App\Repository;

use App\Entity\XtrakLogMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<XtrakLogMetadata>
 *
 * @method XtrakLogMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method XtrakLogMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method XtrakLogMetadata[]    findAll()
 * @method XtrakLogMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XtrakLogMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XtrakLogMetadata::class);
    }

//    /**
//     * @return XtrakLogMetadata[] Returns an array of XtrakLogMetadata objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('x.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?XtrakLogMetadata
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
