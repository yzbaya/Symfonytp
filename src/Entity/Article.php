<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
/**
 * @ORM\Column(type="string", length=255)
 * @Assert\Length(
 * min = 5,
 * max = 50,
 * minMessage = "Le nom d'un article doit comporter au moins {{ limit }} caractères",
 * maxMessage = "Le nom d'un article doit comporter au plus {{ limit }} caractères"
 * )
 */
    #[ORM\Column(length: 255)]

   /**
 * @ORM\Column(type="decimal", precision=10, scale=0)
 * @Assert\NotEqualTo(
 * value = 0,
 * message = "Le prix d’un article ne doit pas être égal à 0 "
 * )
 */
    private ?string $Nom = null;

    #[ORM\Column]
    private ?int $Prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }
}
