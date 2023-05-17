<?php
/**
 * Description du fichier : Ce fichier contient des fonctions DQL de Seisme
 *
 * @category   Fonctions DQL
 * @package    App
 * @subpackage Repository
 * @author     Elouan Teissere
 * @version    1.0 - 08/05/2023
 *
 */
namespace App\Repository;

use App\Entity\Seismes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seismes>
 *
 * @method Seismes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seismes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seismes[]    findAll()
 * @method Seismes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeismesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seismes::class);
    }

    public function save(Seismes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Seismes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // On crée une fonction qui permet de rechercher les séismes par pays et intensité maximale
    public function findByPaysAndMagMax($pays, $magMax)
{
    $pays = trim($pays);// On supprime les espaces avant et après le pays pour formater correctement la requête
    return $this->createQueryBuilder('s')
        ->where('s.pays = :pays')
        ->andWhere('s.mag <= :magMax')
        ->setParameter('pays', $pays)
        ->setParameter('magMax', $magMax)
        ->setMaxResults(50)
        ->orderBy('s.mag', 'DESC')
        ->getQuery()
        ->getResult();
}
    public function findByMag($magMax)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.mag <= :magMax')
            ->setParameter('magMax', $magMax)
            ->orderBy('s.mag', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getArrayResult()
        ;
    }
    public function findByLimit($id)
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.instant', 'DESC')
            ->setMaxResults($id)
            ->getQuery()
            ->getArrayResult()
        ;
    }
//    /**
//     * @return Seismes[] Returns an array of Seismes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Seismes
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
