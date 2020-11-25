<?php
namespace App\Entity;

use App\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Account
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class Account implements EntityInterface
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Account
     */
    public function setId(int $id): Account
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function jsonSerialize()
    {
        return $this->getId();
    }
}