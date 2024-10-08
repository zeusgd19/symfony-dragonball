<?php

namespace App\Entity;

use App\Repository\PersonajeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonajeRepository::class)]
class Personaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre no puede estar vacio")]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La raza del personaje no puede estar vacia")]
    private ?string $raza = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getRaza(): ?string
    {
        return $this->raza;
    }

    public function setRaza(string $raza): self
    {
        $this->raza = $raza;

        return $this;
    }
}
