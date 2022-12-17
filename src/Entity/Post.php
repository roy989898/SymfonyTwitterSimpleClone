<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Length(min: 1, max: 255)]
    #[ORM\Column(length: 10000)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $create_date_time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $edit_date_time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreateDateTime(): ?\DateTimeInterface
    {
        return $this->create_date_time;
    }

    public function setCreateDateTime(\DateTimeInterface $create_date_time): self
    {
        $this->create_date_time = $create_date_time;

        return $this;
    }

    public function getEditDateTime(): ?\DateTimeInterface
    {
        return $this->edit_date_time;
    }

    public function setEditDateTime(\DateTimeInterface $edit_date_time): self
    {
        $this->edit_date_time = $edit_date_time;

        return $this;
    }
}
