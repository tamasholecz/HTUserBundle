<?php

namespace HT\UserBundle\Form;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserRolesType extends AbstractType
{
    private $security;
    private $hierarchyRoles;

    public function __construct(Security $security, ParameterBagInterface $parameterBag)
    {
        $this->security = $security;
        $this->hierarchyRoles = $parameterBag->get('security.role_hierarchy.roles');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->getAvailableRoles(),
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getAvailableRoles(): array
    {
        $roles = [];
        foreach ($this->hierarchyRoles as $role => $hierarchy) {
            if ($this->security->isGranted($role)) $roles[$role] = $role;
        }

        return $roles;
    }
}
