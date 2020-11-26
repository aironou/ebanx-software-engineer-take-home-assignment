<?php
namespace App\Service;

use App\Entity\Account;
use Doctrine\ORM\NoResultException;

class AccountService extends AbstractEntityService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Account::class;
    }

    /**
     * @param int $id
     * @return Account
     * @throws NoResultException
     */
    public function findOne(int $id): Account
    {
        return $this->find(['id' => $id], null, null, null)[0];
    }

    /**
     * @param int $id
     * @return Account
     */
    public function findOrCreate(int $id): Account
    {
        try {
            return $this->findOne($id);
        } catch (NoResultException $exception) {
            return $this->persist((new Account())->setId($id));
        }
    }
}