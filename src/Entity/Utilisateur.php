<?php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface , PasswordAuthenticatedUserInterface , TwoFactorInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUtilisateur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Nom obligatoire!')]
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Prénom obligatoire!')]
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Email obligatoire!')]
    #[Assert\Email(message: 'Email Invalide!')]
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mot_de_passe", type="string", length=255, nullable=false)
     */
    private $motDePasse;

    /**
     * @var int
     *
     * @ORM\Column(name="num_telephone", type="integer", nullable=false)
     */
    #[Assert\NotBlank(message: 'Numéro de téléphone obligatoire!')]
    #[Assert\Length(8)]
    private $numTelephone;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", nullable=false)
     */
    #[Assert\NotBlank(message: 'Role obligatoire!')]
    private $role;

       /**
     * @var json|null
     *
     * @ORM\Column(name="roles", type="text", length=0, nullable=true)
     */
    private $roles=[];

        /**
     * @var string|null
     *
     * @ORM\Column(name="resetToken", type="text", length=0, nullable=false)
     */
    private $resetToken;

    
        /**
     * @var string|null
     *
     * @ORM\Column(name="authCode", type="text", length=0, nullable=false)
     */
    private $authCode;

        /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastConnection", type="datetime", nullable=true)
     */
    private $lastconnection;


    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getNumTelephone(): ?int
    {
        return $this->numTelephone;
    }

    public function setNumTelephone(int $numTelephone): self
    {
        $this->numTelephone = $numTelephone;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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
        $roles = is_array($this->roles) ? $this->roles : [$this->roles];
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
        }

    function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;   
     }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->motDePasse;
    }

    public function setPassword(string $password): self
    {
        $this->motDePasse = $password;

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
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

     /**
     * Return true if the user should do two-factor authentication.
     */
    public function isEmailAuthEnabled(): bool{
        return true;
    }

    /**
     * Return user email address.
     */
    public function getEmailAuthRecipient(): string{
        return $this->email;
    }

    /**
     * Return the authentication code.
     */
    public function getEmailAuthCode(): string{
        if (null == $this->authCode){
            throw new \LogicException('The emailauthentification was not set');
        }
        return $this->authCode;
    }

    /**
     * Set the authentication code.
     */
    public function setEmailAuthCode(string $authCode): void{
        $this->authCode = $authCode;
    }

    public function setLastConnection(\DateTimeInterface $lastConnection): self
    {
        $this->lastconnection = $lastConnection;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->lastconnection;
    }

}
