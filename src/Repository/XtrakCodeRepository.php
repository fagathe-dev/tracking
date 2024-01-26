<?php

namespace App\Repository;

use App\Entity\XtrakCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<XtrakCode>
 *
 * @method XtrakCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method XtrakCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method XtrakCode[]    findAll()
 * @method XtrakCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XtrakCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XtrakCode::class);
    }

//    /**
//     * @return XtrakCode[] Returns an array of XtrakCode objects
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

//    public function findOneBySomeField($value): ?XtrakCode
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
