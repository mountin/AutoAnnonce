<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Cars $car_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $orderBy = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCarId(): ?Cars
    {
        return $this->car_id;
    }

    public function setCarId(?Cars $car_id): static
    {
        $this->car_id = $car_id;

        return $this;
    }

    public function getOrderBy(): ?int
    {
        return $this->orderBy;
    }

    public function setOrderBy(?int $orderBy): static
    {
        $this->orderBy = $orderBy;

        return $this;
    }
}
