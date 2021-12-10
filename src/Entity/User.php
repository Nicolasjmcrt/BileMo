<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Hateoas\Relation(
 *      name = "self",
 *      href = @Hateoas\Route(
 *          "customer_user_details",
 *          parameters = {
 *              "id" = "expr(object.getCustomer().getId())", "user_id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *      ),
 *      attributes = {"actions": { "read": "GET", "post": "POST", "delete": "DELETE" }},
 *      exclusion = @Hateoas\Exclusion(groups = {"SHOW_USER", "LIST_USER"})
 * )
 * @Hateoas\Relation(
 *      name = "all",
 *      href = @Hateoas\Route(
 *          "users_list",
 *          parameters = {
 *              "id" = "expr(object.getCustomer().getId())"
 *          },
 *          absolute = true
 *      ),
 *      attributes = {"actions": { "read": "GET" }},
 *      exclusion = @Hateoas\Exclusion(groups = {"SHOW_USER"})
 * )
 * @Hateoas\Relation(
 *      "customer",
 *      embedded = @Hateoas\Embedded("expr(object.getCustomer())"),
 *      exclusion = @Hateoas\Exclusion(groups = {"SHOW_USER", "LIST_USER"})
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"SHOW_USER", "LIST_USER"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Serializer\Groups({"SHOW_USER", "LIST_USER"})
     * @Assert\NotBlank(message="Le username est obligatoire")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le password est obligatoire")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"SHOW_USER"})
     * @Assert\NotBlank(message="Le firstname est obligatoire")
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"SHOW_USER"})
     * @Assert\NotBlank(message="Le lastname est obligatoire")
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"SHOW_USER"})
     * @Assert\NotBlank(message="L'email est obligatoire")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @Serializer\Exclude
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Le customer est obligatoire")
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"SHOW_USER"})
     */
    private $creation_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }
}
