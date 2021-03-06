<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function modify($id, $nomSortie, $villeOrganisatrice, $dateSortie, $lieuSortie, $dateLimite, $nbPlaces,
                           $duree, $description, $etat)
    {
        $query = $this->createQueryBuilder('s')
            ->update('Sortie')
            ->set('nom', 'test')
            ->where('id = 9')
            ->getQuery();

        return $query->getResult();
    }

    public function findAllFilter(
        Participant $user,
        Site $formSite = null,
        bool $organisateur = false
    )
    {
        $qb = $this->createQueryBuilder('s');

        if ($formSite != null){
            $qb ->andWhere('s.site = :site')
                ->setParameter('site', $formSite->getId());
        }

        if ($organisateur){
            $qb ->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $user->getId());
        }

        $qb = $qb->getQuery();
        return $qb->execute();
    }
}
