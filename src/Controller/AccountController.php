<?php
namespace App\Controller;

use App\Entity\Account;
use App\Service\AccountService;
use App\Service\BalanceService;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController
{
    /**
     * @var AccountService
     */
    private AccountService $accountService;

    /**
     * @var BalanceService
     */
    private BalanceService $balanceService;

    public function __construct(AccountService $accountService, BalanceService $balanceService)
    {
        $this->accountService = $accountService;
        $this->balanceService = $balanceService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     *
     * @Route(
     *     "/balance",
     *     name="account-balance",
     *     methods={"GET"}
     * )
     */
    public function balance(Request $request): JsonResponse
    {
        return new JsonResponse(
            $this->balanceService->getBalanceByAccount($this->getAccountFromQuery($request)),
            Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @return Account
     * @throws NoResultException
     */
    private function getAccountFromQuery(Request $request): Account
    {
        return $this->accountService->findOne($request->get('account_id', 0));
    }
}