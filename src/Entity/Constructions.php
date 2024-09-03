<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConstructionsRepository;

#[ORM\Entity(repositoryClass: ConstructionsRepository::class)]
#[ORM\Table(name: "constructions")]
#[ORM\Index(name: "idx_name", columns: ["name"])]
#[ORM\Index(name: "idx_location", columns: ["location"])]
#[ORM\Index(name: "idx_stage", columns: ["stage"])]
#[ORM\Index(name: "idx_category", columns: ["category"])]
class Constructions
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 6, unique: true)]
    private string $id;

    #[ORM\Column(length: 200)]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $location;

    #[ORM\Column(type: 'string', enumType: Stages::class)]
    private Stages $stage;

    #[ORM\Column(type: 'string', enumType: Categories::class)]
    private Categories $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $otherCategory = null;

    #[ORM\Column(type: 'date')]
    private DateTime $startDate;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(length: 255)]
    private string $creatorId;

    public function __construct()
    {
        $this->id = $this->generateId();
        $this->creatorId = "user";
    }

    private function generateId(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getStage(): Stages
    {
        return $this->stage;
    }

    public function setStage(Stages $stage): static
    {
        $this->stage = $stage;
        return $this;
    }

    public function getCategory(): Categories
    {
        return $this->category;
    }

    public function setCategory(Categories $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getOtherCategory(): ?string
    {
        return $this->otherCategory;
    }

    public function setOtherCategory(?string $otherCategory): static
    {
        $this->otherCategory = $otherCategory;
        return $this;
    }

    public function isOthersSelected(): bool
    {
        return $this->category === Categories::OTHERS;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCreatorId(): string
    {
        return $this->creatorId;
    }

    public function setCreatorId(string $creatorId): static
    {
        $this->creatorId = $creatorId;
        return $this;
    }
}
