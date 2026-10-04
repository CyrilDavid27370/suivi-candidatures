<?php

namespace App\Repository;

use App\Entity\Candidature;
use App\Entity\User;
use App\Enum\StatusCandidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidature>
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

    public function findByFilters(User $user, ?StatusCandidature $status, string $recherche, string $tri): array
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user);

        if ($status !== null) {
            $qb->andWhere('c.statut = :statut')
                ->setParameter('statut', $status);
        }

        if ($recherche !== '') {
            $qb->andWhere('c.entreprise LIKE :recherche OR c.poste LIKE :recherche')
                ->setParameter('recherche', '%' . $recherche . '%');
        }
            $qb->orderBy('c.dateCandidature', $tri === 'asc' ? 'ASC' : 'DESC');

            return $qb->getQuery()->getResult();
    }
}
