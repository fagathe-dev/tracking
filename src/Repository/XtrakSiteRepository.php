<?php

namespace App\Repository;

use App\Entity\XtrakSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<XtrakSite>
 *
 * @method XtrakSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method XtrakSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method XtrakSite[]    findAll()
 * @method XtrakSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XtrakSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XtrakSite::class);
    }

//    /**
//     * @return XtrakSite[] Returns an array of XtrakSite objects
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

//    public function findOneBySomeField($value): ?XtrakSite
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
