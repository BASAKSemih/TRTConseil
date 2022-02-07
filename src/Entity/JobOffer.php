<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Recruiter\Recruiter;
use App\Repository\Recruiter\JobOfferRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $jobName;

    #[ORM\Column(type: 'text')]
    private string $workplace;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $salary;

    #[ORM\Column(type: 'text')]
    private string $schedule;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Recruiter::class, inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private Recruiter $recruiter;

    /**
     * @var Collection<PostJobOffer>
     */
    #[ORM\OneToMany(mappedBy: 'jobOffer', targetEntity: PostJobOffer::class)]
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

    public function getJobName(): ?string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getWorkplace(): ?string
    {
        return $this->workplace;
    }

    public function setWorkplace(string $workplace): self
    {
        $this->workplace = $workplace;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): self
    {
        $this->schedule = $schedule;

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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRecruiter(): ?Recruiter
    {
        return $this->recruiter;
    }

    public function setRecruiter(Recruiter $recruiter): self
    {
        $this->recruiter = $recruiter;

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
            $postJobOffer->setJobOffer($this);
        }

        return $this;
    }

    public function removePostJobOffer(PostJobOffer $postJobOffer): self
    {
        if ($this->postJobOffers->removeElement($postJobOffer)) {
            // set the owning side to null (unless already changed)
            if ($postJobOffer->getJobOffer() === $this) {
                $postJobOffer->setJobOffer(null);
            }
        }

        return $this;
    }
}
