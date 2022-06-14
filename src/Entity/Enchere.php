<?php

namespace App\Entity;

use App\Repository\EnchereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnchereRepository::class)]
class Enchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date_debut;

    #[ORM\Column(type: 'datetime')]
    private $date_fin;

    #[ORM\Column(type: 'integer')]
    private $id_panier_globale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getEnchereFournisseurs(): Collection
    {
        return $this->enchereFournisseurs;
    }

    public function addEnchereFournisseur(EnchereFournisseur $enchereFournisseur): self
    {
        if (!$this->enchereFournisseurs->contains($enchereFournisseur)) {
            $this->enchereFournisseurs[] = $enchereFournisseur;
            $enchereFournisseur->setIdEnchere($this);
        }

        return $this;
    }

    public function removeEnchereFournisseur(EnchereFournisseur $enchereFournisseur): self
    {
        if ($this->enchereFournisseurs->removeElement($enchereFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($enchereFournisseur->getIdEnchere() === $this) {
                $enchereFournisseur->setIdEnchere(null);
            }
        }

        return $this;
    }

    public function getIdPanierGlobale(): ?int
    {
        return $this->id_panier_globale;
    }

    public function setIdPanierGlobale(int $id_panier_globale): self
    {
        $this->id_panier_globale = $id_panier_globale;

        return $this;
    }
}
