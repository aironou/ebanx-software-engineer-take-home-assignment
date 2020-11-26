<?php
namespace App\Service;

use App\Entity\Account;
use App\Entity\Transaction;

class BalanceService
{
    /**
     * @var TransactionService
     */
    private TransactionService $transactionService;

    /**
     * BalanceService constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Account $account
     * @param Transaction|null $currentTransaction
     * @return float
     */
    public function getBalanceByAccount(Account $account, ?Transaction $currentTransaction = null): float
    {
        $transactions = $this->transactionService->findByAccount($account);
        if (is_null($currentTransaction) === false) {
            $transactions[] = $currentTransaction;
        }
        return array_sum(array_map(function (Transaction $transaction) use ($account) {
            return $this->mapTransactionAsAmount($transaction, $account);
        }, $transactions)) ?? 0;
    }

    /**
     * @param Transaction $transaction
     * @param Account $account
     * @return float
     */
    private function mapTransactionAsAmount(Transaction $transaction, Account $account): float
    {
        switch ($transaction->getType()) {
            case Transaction::DEPOSIT_TYPE:
                return $this->mapDepositTransactionAsAmount(...func_get_args());
            case Transaction::TRANSFER_TYPE:
                return $this->mapTransferTransactionAsAmount(...func_get_args());
            case Transaction::WITHDRAW_TYPE:
                return $this->mapWithdrawTransactionAsAmount(...func_get_args());
            default:
                return 0;
        }
    }

    /**
     * @param Transaction $transaction
     * @param Account $account
     * @return float
     */
    private function mapDepositTransactionAsAmount(Transaction $transaction, Account $account): float
    {
        return $this->isSameAccount($transaction->getDestination(), $account) ? $transaction->getAmount() : 0;
    }

    /**
     * @param Account|null $transactionAccount
     * @param Account $account
     * @return bool
     */
    private function isSameAccount(?Account $transactionAccount, Account $account): bool
    {
        return is_null($transactionAccount) ? false : $transactionAccount->getId() === $account->getId();
    }

    /**
     * @param Transaction $transaction
     * @param Account $account
     * @return float
     */
    private function mapTransferTransactionAsAmount(Transaction $transaction, Account $account): float
    {
        $origin = $transaction->getOrigin();
        $destination = $transaction->getDestination();
        if ($this->isSameAccount($destination, $origin)) {
            return 0;
        } elseif ($this->isSameAccount($origin, $account)) {
            return abs($transaction->getAmount()) * -1;
        } else {
            return $transaction->getAmount();
        }
    }

    /**
     * @param Transaction $transaction
     * @param Account $account
     * @return float
     */
    private function mapWithdrawTransactionAsAmount(Transaction $transaction, Account $account): float
    {
        return $this->isSameAccount($transaction->getOrigin(), $account) ? abs($transaction->getAmount()) * -1 : 0;
    }
}