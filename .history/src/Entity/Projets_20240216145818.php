<?php

namespace App\Entity;

use App\Repository\ProjetsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: ProjetsRepository::class)]
class Projets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @Vich\UploadableField(mapping="project_images", fileNameProperty="image")
     */
    private ?File $imageFile = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Category = null;

    #[ORM\Column(length: 255)]
    private ?string $WalletAddress = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeCreation = null;

    #[ORM\OneToMany(targetEntity: Traits::class, mappedBy: 'projets')]
    private Collection $traits;

    public function __construct()
    {
        $this->traits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(string $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    public function getWalletAddress(): ?string
    {
        return $this->WalletAddress;
    }

    public function setWalletAddress(string $WalletAddress): static
    {
        $this->WalletAddress = $WalletAddress;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->DateDeCreation;
    }

    public function setDateDeCreation(\DateTimeInterface $DateDeCreation): static
    {
        $this->DateDeCreation = $DateDeCreation;

        return $this;
    }

    /**
     * @return Collection<int, Traits>
     */
    public function getTraits(): Collection
    {
        return $this->traits;
    }

    public function addTrait(Traits $trait): static
    {
        if (!$this->traits->contains($trait)) {
            $this->traits->add($trait);
            $trait->setProjets($this);
        }

        return $this;
    }

    public function removeTrait(Traits $trait): static
    {
        if ($this->traits->removeElement($trait)) {
            // set the owning side to null (unless already changed)
            if ($trait->getProjets() === $this) {
                $trait->setProjets(null);
            }
        }

        return $this;
    }
}
