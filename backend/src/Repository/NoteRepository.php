<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Note;
use App\Enum\Priority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * Returns notes newest first, optionally filtered by priority (null = all notes).
     *
     * @return list<Note>
     */
    public function findByPriority(?Priority $priority): array
    {
        $queryBuilder = $this->createQueryBuilder('note')
            ->orderBy('note.id', 'DESC');

        if (null !== $priority) {
            $queryBuilder
                ->andWhere('note.priority = :priority')
                ->setParameter('priority', $priority);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function save(Note $note): void
    {
        $this->getEntityManager()->persist($note);
        $this->getEntityManager()->flush();
    }

    public function remove(Note $note): void
    {
        $this->getEntityManager()->remove($note);
        $this->getEntityManager()->flush();
    }
}
