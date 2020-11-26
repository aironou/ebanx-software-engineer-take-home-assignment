<?php
namespace App\Repository;

use App\Entity\Account;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    const ALIAS = 'transaction';

    /**
     * @param Account $account
     * @return array
     */
    public function getByAccount(Account $account): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        return $queryBuilder->select()
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq(self::ALIAS . '.origin', ':account'),
                $queryBuilder->expr()->eq(self::ALIAS . '.destination', ':account')
            ))
            ->setParameter('account', $account)
            ->getQuery()
            ->getResult();
    }
}