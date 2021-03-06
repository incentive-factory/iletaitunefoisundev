<?php

declare(strict_types=1);

namespace App\Adventure\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Adventure\Doctrine\Repository\RegionRepository;
use App\Adventure\UseCase\GetMapByRegion\GetMapByRegion;
use App\Adventure\UseCase\GetMapByRegion\Map;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: [
        'get',
        'map' => [
            'method' => 'GET',
            'controller' => GetMapByRegion::class,
            'output' => Map::class,
            'path' => '/regions/{id}/map',
            'normalization_context' => ['groups' => ['map']],
        ],
    ],
    normalizationContext: ['groups' => ['read']],
    routePrefix: '/adventure',
)]
#[Entity(repositoryClass: RegionRepository::class)]
class Region implements Stringable
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    #[Groups(['adventure', 'map'])]
    private ?int $id = null;

    #[Column(type: Types::STRING)]
    #[Groups(['adventure', 'map'])]
    private string $name;

    #[ManyToOne(targetEntity: Continent::class, inversedBy: 'regions')]
    #[JoinColumn(nullable: false)]
    #[Groups('map')]
    private Continent $continent;

    /**
     * @var Collection<int, Quest>
     */
    #[OneToMany(mappedBy: 'region', targetEntity: Quest::class)]
    private Collection $quests;

    #[OneToOne(targetEntity: Quest::class)]
    private ?Quest $firstQuest = null;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getContinent(): Continent
    {
        return $this->continent;
    }

    public function setContinent(Continent $continent): void
    {
        $this->continent = $continent;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function getFirstQuest(): ?Quest
    {
        return $this->firstQuest;
    }

    public function setFirstQuest(?Quest $firstQuest): void
    {
        $this->firstQuest = $firstQuest;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
