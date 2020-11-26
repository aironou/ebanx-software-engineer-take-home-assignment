<?php
namespace App\Service;

use App\Interfaces\EntityInterface;
use App\Interfaces\EntityServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

abstract class AbstractEntityService implements EntityServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * @param EntityManagerInterface $entityManager
     */
    final public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    final public function persist(EntityInterface $entity): EntityInterface
    {
        $this->entityManager->persist($entity);
        return $entity;
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws NoResultException
     */
    final public function find(array $criteria, ?array $orderBy, ?int $limit, ?int $offset): array
    {
        $results = $this->getRepository()->findBy(...func_get_args());
        if (count($results) === 0) {
            throw new NoResultException();
        }
        return $results;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntityClass());
    }
}