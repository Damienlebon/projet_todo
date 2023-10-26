<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ListeRepository::class)]
#[Vich\Uploadable]
class Liste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'listes', fileNameProperty: 'pictureName')]
    private ?File $pictureFile = null;

    #[ORM\Column(length: 255)]
    private ?string $pictureName = null;

    #[ORM\OneToMany(mappedBy: 'liste', targetEntity: Tache::class)]
    private Collection $taches;

    public function __construct() {
        $this->taches = new ArrayCollection();
    }

    public function getTaches() {
        return $this->taches;
    }

    public function setPictureFile(?File $pictureFile = null): void
    {
        $this->pictureFile = $pictureFile;
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function setPictureName(?string $pictureName): void
    {
        $this->pictureName = $pictureName;
    }

    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function getId(): ?int
    {
        return $this->id;
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
 

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
    
   /** @return Collection<int, Taches>*/

    public function addTaches(Tache $taches): static
    {
        if (!$this->taches->contains($taches)) {
            $this->taches->add($taches);
            $taches->setListe($this);
        }

        return $this;
    }

    public function removeTaches(Tache $taches): static
    {
        if ($this->taches->removeElement($taches)) {
            // set the owning side to null (unless already changed)
            if ($taches->getListe() === $this) {
                $taches->setListe(null);
            }
        }

        return $this;
    }
}
