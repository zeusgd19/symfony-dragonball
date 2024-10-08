<?php

namespace App\Entity;

use App\Repository\PoderesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PoderesRepository::class)]
class Poderes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre no puede estar vacio")]
    private ?string $nombre = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La potencia no puede ser nula")]
    #[Assert\Type(type: 'int', message: "Inserta un numero entero")]
    private ?int $potencia = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Tienes que poner algun color")]
    private ?string $color = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Selecciona algún personaje")]
    private ?Personaje $personaje = null;


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

    public function getPotencia(): ?int
    {
        return $this->potencia;
    }

    public function setPotencia(int $potencia): self
    {
        $this->potencia = $potencia;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPersonaje(): ?Personaje
    {
        return $this->personaje;
    }

    public function setPersonaje(?Personaje $personaje): self
    {
        $this->personaje = $personaje;

        return $this;
    }
}
