`$ composer require tamasholecz/user-bundle

config/packages/ht_user.yaml
```yaml
ht_user:
    user_class: App\Entity\User
#    profile_form: App\Form\ProfileType
#    registration_form: App\Form\RegistrationType
#    change_password_form: App\Form\ChangePasswordType
```

config/security.yaml
```yaml
security:
    encoders:
        App\Entity\User:
            algorithm: auto
    providers:
        app_user_provider:
            entity:
                class: App\Entity\User
                property: username
    firewalls:
        main:
            anonymous: ~
            logout:
                path: logout
                target: /
            guard:
                authenticators:
                    - ht_user.security.login_form_authenticator
```

config/routes/ht_user.yaml
```yaml
ht_user:
    resource: '@HTUserBundle/Resources/config/routing/all.yaml'
    prefix: /{_locale}
    requirements:
        _locale: '%locales%'
```

src/Entity/User.php
```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use HT\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser implements TimestampableInterface
{
	use TimestampableTrait;
}
```

src/Repository/UserRepository.php
```php
<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, User::class);
	}

	/**
	 * Used to upgrade (rehash) the user's password automatically over time.
	 */
	public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
	{
		if (!$user instanceof User) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
		}

		$user->setPassword($newEncodedPassword);
		$this->_em->persist($user);
		$this->_em->flush();
	}
}
```
