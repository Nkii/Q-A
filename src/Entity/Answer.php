<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Answer
 * @package App\Entity
 */
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeInterface $createdAt;

    /**
     * Content.
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $content;

    /**
     * Best answer.
     */
    #[ORM\Column(type: 'integer')]
    private int $bestAnswer = 0;

    /**
     * Question..
     */
    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    /**
     * Author.
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author;

    /**
     * Getter for id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for created at.
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for content.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for best answer.
     */
    public function getBestAnswer(): ?int
    {
        return $this->bestAnswer;
    }

    /**
     * Setter for best answer.
     */
    public function setBestAnswer(int $bestAnswer): self
    {
        $this->bestAnswer = $bestAnswer;

        return $this;
    }

    /**
     * Getter for question.
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * Setter for question.
     */
    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Getter for author.
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
