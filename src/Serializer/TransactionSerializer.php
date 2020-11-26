<?php
namespace App\Serializer;

use App\Entity\Transaction;
use App\Exceptions\TransactionTypeNotAllowedException;
use App\Interfaces\EntityInterface;
use App\Service\AccountService;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Serializer\Encoder\ContextAwareDecoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class TransactionSerializer implements ContextAwareDecoderInterface, ContextAwareDenormalizerInterface
{
    /**
     * @var AccountService
     */
    private AccountService $accountService;

    /**
     * TransactionSerializer constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @param string $format
     * @param array $context
     * @return bool
     */
    public function supportsDecoding(string $format, array $context = [])
    {
        return class_exists($format) && in_array(EntityInterface::class, class_implements($format));
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $type === Transaction::class;
    }

    /**
     * @param string $data
     * @param string $format
     * @param array $context
     * @return mixed
     */
    public function decode(string $data, string $format, array $context = [])
    {
        return json_decode($data, true);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return Transaction|array|object
     * @throws NoResultException
     * @throws TransactionTypeNotAllowedException
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $transaction = $context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (is_null($transaction) === true || $transaction instanceof Transaction === false) {
            $transaction = new Transaction();
        }
        return $this->denormalizeTransaction($data, $transaction);
    }

    /**
     * @param array $data
     * @param Transaction $transaction
     * @return Transaction
     * @throws NoResultException
     * @throws TransactionTypeNotAllowedException
     */
    private function denormalizeTransaction(array $data, Transaction $transaction): Transaction
    {
        switch ($transaction->setType($data['type'])->setAmount($data['amount'])->getType()) {
            case Transaction::DEPOSIT_TYPE:
                return $this->denormalizeDepositTransaction(...func_get_args());
            case Transaction::TRANSFER_TYPE:
                return $this->denormalizeTransferTransaction(...func_get_args());
            case Transaction::WITHDRAW_TYPE:
                return $this->denormalizeWithdrawTransaction(...func_get_args());
            default:
                throw new TransactionTypeNotAllowedException();
        }
    }

    /**
     * @param array $data
     * @param Transaction $transaction
     * @return Transaction
     */
    private function denormalizeDepositTransaction(array $data, Transaction $transaction): Transaction
    {
        return $transaction->setDestination($this->accountService->findOrCreate($data['destination']));
    }

    /**
     * @param array $data
     * @param Transaction $transaction
     * @return Transaction
     * @throws NoResultException
     */
    private function denormalizeWithdrawTransaction(array $data, Transaction $transaction): Transaction
    {
        return $transaction->setOrigin($this->accountService->findOne($data['origin']));
    }

    /**
     * @param array $data
     * @param Transaction $transaction
     * @return Transaction
     * @throws NoResultException
     */
    private function denormalizeTransferTransaction(array $data, Transaction $transaction): Transaction
    {
        return $transaction->setDestination($this->accountService->findOrCreate($data['destination']))
            ->setOrigin($this->accountService->findOne($data['origin']));
    }
}