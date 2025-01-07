<?php

namespace App\Entity;

use App\Repository\CarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

use App\Entity\CarType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Vich\Uploadable
 */

#[ORM\Entity(repositoryClass: CarsRepository::class)]
class Cars
{
    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     * @var File|null
     */
    private ?File $imageFile = null;

    private ?string $imageName = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\ManyToMany(targetEntity: Options::class, mappedBy: 'car', fetch: 'EAGER')]
    private Collection $options;
    /**
     * @var Collection<int, Photos>
     */
    #[ORM\OneToMany(targetEntity: Photos::class, mappedBy: 'car_id')]
    private Collection $photos;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    private ?Brands $brand = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarType $type = null;

    #[ORM\Column(length: 100)]
    private ?string $fuel = null;

    #[ORM\Column]
    private ?int $year = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->options = new ArrayCollection();
    }

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setCarId($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCarId() === $this) {
                $photo->setCarId(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?Brands
    {
        return $this->brand;
    }

    public function setBrand(?Brands $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getType(): ?CarType
    {
        return $this->type;
    }

        public function setType(CarType $status): self
        {
            $this->type = $status;

            return $this;
        }

        public function getPrice(): ?string
        {
            return $this->price;
        }

        public function setPrice(?string $price): static
        {
            $this->price = $price;

            return $this;
        }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // Update some property to trigger a Doctrine update (e.g., updatedAt)
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): static
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }


    public function getOptions(): Collection
    {
        return $this->options;
    }
    
        public function addOption(Options $opt): self
    {
        if (!$this->options->contains($opt)) {
            $this->options[] = $opt;
        }
    
        return $this;
    }
    
        public function removeOption(Options $opt): self
    {
        $this->options->removeElement($opt);
    
        return $this;
    }

}