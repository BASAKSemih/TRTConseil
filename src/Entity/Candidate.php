<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\Candidate\CandidateRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[NotBlank]
    #[Email]
    private string $email;

    /**
     * @var array<array-key, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = ['NEED_VERIFIED'];

    #[ORM\Column(type: 'string')]
    #[NotBlank]
    private string $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    private string $lastName;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private string $cvPath;

    /**
     * @var Collection<PostJobOffer>
     */
    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: PostJobOffer::class)]
    private Collection $postJobOffers;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->postJobOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** @phpstan-ignore-next-line  */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getCvPath(): ?string
    {
        return $this->cvPath;
    }

    public function setCvPath(string $cvPath): self
    {
        $this->cvPath = $cvPath;

        return $this;
    }

    /**
     * @return Collection|PostJobOffer[]
     */
    public function getPostJobOffers(): Collection
    {
        return $this->postJobOffers;
    }

    public function addPostJobOffer(PostJobOffer $postJobOffer): self
    {
        if (!$this->postJobOffers->contains($postJobOffer)) {
            $this->postJobOffers[] = $postJobOffer;
            $postJobOffer->setCandidate($this);
        }

        return $this;
    }

    public function removePostJobOffer(PostJobOffer $postJobOffer): self
    {
        if ($this->postJobOffers->removeElement($postJobOffer)) {
            // set the owning side to null (unless already changed)
            if ($postJobOffer->getCandidate() === $this) {
                $postJobOffer->setCandidate(null);
            }
        }

        return $this;
    }
}
