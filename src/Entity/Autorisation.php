<?php

namespace App\Entity;

use App\Repository\AutorisationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutorisationRepository::class)
 */
class Autorisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Lecture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Ecriture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLecture(): ?bool
    {
        return $this->Lecture;
    }

    public function setLecture(bool $Lecture): self
    {
        $this->Lecture = $Lecture;

        return $this;
    }

    public function getEcriture(): ?bool
    {
        return $this->Ecriture;
    }

    public function setEcriture(bool $Ecriture): self
    {
        $this->Ecriture = $Ecriture;

        return $this;
    }
}
