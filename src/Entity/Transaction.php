<?php
namespace App\Entity;

use App\Exceptions\TransactionTypeNotAllowedException;
use App\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Transaction
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table()
 */
class Transaction implements EntityInterface
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private float $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $type;

    /**
     * @var Account|null
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Account $origin;

    /**
     * @var Account|null
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Account $destination;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Transaction
     */
    public function setId(int $id): Transaction
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Transaction
     */
    public function setAmount(float $amount): Transaction
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Transaction
     * @throws TransactionTypeNotAllowedException
     */
    public function setType(string $type): Transaction
    {
        $this->type = $this->validateType($type);
        return $this;
    }

    /**
     * @return Account|null
     */
    public function getOrigin(): ?Account
    {
        return $this->origin;
    }

    /**
     * @param Account|null $origin
     * @return Transaction
     */
    public function setOrigin(?Account $origin): Transaction
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return Account|null
     */
    public function getDestination(): ?Account
    {
        return $this->destination;
    }

    /**
     * @param Account|null $destination
     * @return Transaction
     */
    public function setDestination(?Account $destination): Transaction
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [];
    }

    /**
     * @param string $type
     * @return string
     * @throws TransactionTypeNotAllowedException
     */
    private function validateType(string $type): string
    {
        if (in_array($type, $this->getAllowedTypes()) === false) {
            throw new TransactionTypeNotAllowedException();
        }
        return $type;
    }

    /**
     * @return array
     */
    private function getAllowedTypes(): array
    {
        return array_filter(
            (new \ReflectionClass(Transaction::class))->getConstants(),
            function (string $name) {
                return strpos($name, '_TYPE') !== false;
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}