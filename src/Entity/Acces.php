<?php

namespace App\Entity;

use App\Repository\AccesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccesRepository::class)
 */
class Acces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Autorisation::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $autorisation;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Document;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getAutorisation(): ?Autorisation
    {
        return $this->autorisation;
    }

    public function setAutorisation(?Autorisation $autorisation): self
    {
        $this->autorisation = $autorisation;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->Document;
    }

    public function setDocument(?Document $Document): self
    {
        $this->Document = $Document;

        return $this;
    }
}
