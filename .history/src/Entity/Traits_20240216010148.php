<?php

namespace App\Entity;

use App\Repository\TraitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraitsRepository::class)]
class Traits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $rarete = null;

    #[ORM\Column(length: 255)]
    private ?string $type_de_trait = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeCreation = null;

    #[ORM\Column(length: 255)]
    private ?string $Couleur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getRarete(): ?string
    {
        return $this->rarete;
    }

    public function setRarete(string $rarete): static
    {
        $this->rarete = $rarete;

        return $this;
    }

    public function getTypeDeTrait(): ?string
    {
        return $this->type_de_trait;
    }

    public function setTypeDeTrait(string $type_de_trait): static
    {
        $this->type_de_trait = $type_de_trait;

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

    public function getCouleur(): ?string
    {
        return $this->Couleur;
    }

    public function setCouleur(string $Couleur): static
    {
        $this->Couleur = $Couleur;

        return $this;
    }
}
