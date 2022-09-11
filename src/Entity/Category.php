<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields: ['name'])]
class Category
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Name.
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $name;

    /**
     * Code.
     */
    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\Type('string')]
    #[Gedmo\Slug(fields: ['name'])]
    private $code;

    /**
     * Question.
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Question::class, orphanRemoval: true)]
    private $questions;

    #[Pure]
    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * Getter for id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for code.
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Setter for code.
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setCategory($this);
        }

        return $this;
    }

    /**
     * Remove question.
     */
    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCategory() === $this) {
                $question->setCategory(null);
            }
        }

        return $this;
    }
}
