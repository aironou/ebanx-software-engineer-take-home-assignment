<?php
namespace App\Controller;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Service\BalanceService;
use App\Service\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionController
{
    /**
     * @var BalanceService
     */
    private BalanceService $balanceService;

    /**
     * @var TransactionService
     */
    private TransactionService $transactionService;

    /**
     * TransactionController constructor.
     * @param BalanceService $balanceService
     * @param TransactionService $transactionService
     */
    public function __construct(
        BalanceService $balanceService,
        TransactionService $transactionService
    ) {
        $this->balanceService = $balanceService;
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     *
     * @Route(
     *     "/event",
     *     name="transaction-event",
     *     methods={"POST"}
     * )
     */
    public function event(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $transaction = $this->transactionService->persist($serializer->deserialize(
            $request->getContent(),
            Transaction::class,
            Transaction::class
        ));
        $this->addBalanceToAccounts($transaction);
        return new JsonResponse($transaction, Response::HTTP_CREATED);
    }

    /**
     * @param Transaction $transaction
     */
    private function addBalanceToAccounts(Transaction $transaction): void
    {
        $this->addBalanceToAccount($transaction->getDestination(), $transaction);
        $this->addBalanceToAccount($transaction->getOrigin(), $transaction);
    }

    /**
     * @param Account|null $account
     * @param Transaction $transaction
     * @return Account|null
     */
    private function addBalanceToAccount(?Account $account, Transaction $transaction): ?Account
    {
        return (is_null($account)) ? null : $account->setBalance(
            $this->balanceService->getBalanceByAccount($account, $transaction)
        );
    }
}