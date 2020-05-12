<?php

namespace App\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MappedSuperclass
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @ORM\EntityListeners({"App\UserBundle\EventListener\UserPasswordUpgrader"})
 */
abstract class User implements UserInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="UUID")
	 * @ORM\Column(type="guid")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\Email()
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=50, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	private $plainPassword;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $lastLogin;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $enabled = false;

	/**
	 * @ORM\Column(type="string", length=180, unique=true, nullable=true)
	 */
	private $confirmationToken;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $passwordRequestedAt;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	protected $phone;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $remark;

	public function __toString()
	{
		return $this->getName() ? $this->getName() : $this->getUsername();
	}

	public function getId(): ?string
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
		if (!$this->getUsername()) $this->setUsername($email);

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string
	{
		return (string) $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
	{
		return (string) $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}

	public function setPlainPassword(?string $password): self
	{
		$this->plainPassword = $password;
		$this->updatedAt = new \DateTimeImmutable();

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
	{
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		$this->plainPassword = null;
	}

	public function getLastLogin(): ?\DateTimeInterface
	{
		return $this->lastLogin;
	}

	public function setLastLogin(?\DateTimeInterface $lastLogin): self
	{
		$this->lastLogin = $lastLogin;

		return $this;
	}

	public function getEnabled(): ?bool
	{
		return $this->enabled;
	}

	public function setEnabled(bool $enabled): self
	{
		$this->enabled = $enabled;

		return $this;
	}

	public function getConfirmationToken(): ?string
	{
		return $this->confirmationToken;
	}

	public function setConfirmationToken(?string $confirmationToken): self
	{
		$this->confirmationToken = $confirmationToken;

		return $this;
	}

	public function getPasswordRequestedAt(): ?\DateTimeInterface
	{
		return $this->passwordRequestedAt;
	}

	public function setPasswordRequestedAt(?\DateTimeInterface $passwordRequestedAt): self
	{
		$this->passwordRequestedAt = $passwordRequestedAt;

		return $this;
	}

	public function setName(?string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setPhone(?string $phone): self
	{
		$this->phone = $phone;

		return $this;
	}

	public function getPhone(): ?string
	{
		return $this->phone;
	}

	public function setRemark(?string $remark): self
	{
		$this->remark = $remark;

		return $this;
	}

	public function getRemark(): ?string
	{
		return $this->remark;
	}
}
