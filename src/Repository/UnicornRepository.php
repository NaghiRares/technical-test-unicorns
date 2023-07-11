<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Unicorn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unicorn>
 *
 * @method Unicorn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unicorn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unicorn[]    findAll()
 * @method Unicorn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnicornRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unicorn::class);
    }

    public function save(Unicorn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Unicorn $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getAll(): array
    {
        return $this->createQueryBuilder('u')->getQuery()->getArrayResult();
    }
}
