<?php

namespace App\Form;

use App\Entity\Civility;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username')
                ->add('firstname')
                ->add('lastname')
                ->add('telephoneNumber')
                ->add('enabled')
                ->add('confirmationUrl', UrlType::class)
                ->add('civility', EntityType::class,
                        ['class' => Civility::class, 'choice_label' => 'name'])
                ->add('groups', EntityType::class, [
                    'multiple'     => true,
                    'expanded'     => true,
                    'class'        => Group::class,
                    'choices'      => $options['groups'],
                    'choice_label' => 'name',
                    'required'     => false,
                    ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'       => 'App\Entity\User',
            'group_names'      => [],
            'passwordRequired' => false,
            'groups'           => null,
        ]);
    }
}
