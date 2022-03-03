<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Commande $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Commande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
    * @return Commande[] Returns an array of Commande objects
    */
    
    public function findAllCommandBetweenTwoDates($value1, $value2)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date_commande>=:val1 AND c.date_commande<=:val2')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Commande[] Returns an array of Commande objects
    */
    
    public function findAllTurnOversPerClient($value1='2022')
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT cli, SUM(p.pu) FROM App\Entity\Commande c, App\Entity\Produit p, App\Entity\Client cli 
            WHERE c.clients=cli AND c.produits=p AND YEAR(c.date_commande)=:val GROUP BY cli.nom ORDER BY cli.numcli"
        )->setParameter('val', $value1)->getResult();


        return $query;
    }

    /**
    * @return Commande Returns a Commande objects
    */
    
    public function findAllCommandMadeByAClientOnASpecificDate($value1, $value2)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT c FROM App\Entity\Commande c WHERE c.clients = :value1 AND c.date_commande=:value2"
        )->setParameter('value1', $value1)->setParameter('value2', $value2);

        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
