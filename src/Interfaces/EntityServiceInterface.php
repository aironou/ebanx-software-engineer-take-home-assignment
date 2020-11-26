<?php
namespace App\Interfaces;

use Doctrine\ORM\EntityManagerInterface;

interface EntityServiceInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void;

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function persist(EntityInterface $entity): EntityInterface;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function find(array $criteria, ?array $orderBy, ?int $limit, ?int $offset): array;
}