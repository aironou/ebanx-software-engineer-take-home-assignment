<?php
namespace App\Service;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class TransactionService extends AbstractEntityService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Transaction::class;
    }

    /**
     * @param Account $account
     * @return array
     */
    public function findByAccount(Account $account): array
    {
        return $this->getRepository()->findByAccount($account);
    }

    /**
     * @return TransactionRepository
     */
    protected function getRepository(): TransactionRepository
    {
        return parent::getRepository();
    }
}