<?php

namespace App\Form;

use App\Entity\PanoramaUser;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class PanoramaSendType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $auth;
    /**
     * @var Security
     */
    protected $security;

    public function __construct(AuthorizationCheckerInterface $auth, Security $security)
    {
        $this->auth = $auth;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /**->add('customers', EntityType::class, [
                'class' => Users::class,
                'choice_label' => function(Users $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'query_builder' => function (UsersRepository $er) {
                    if ($this->auth->isGranted('ROLE_ADMIN')) {
                        return $er->createQueryBuilder('u')
                            ->andWhere('u.status = :role')
                            ->setParameter('role', 'ROLE_USER' );
                    } elseif ($this->auth->isGranted('ROLE_TECHNICIAN')) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.status', 'ASC')
                            ->andWhere('u.technician = :technician')
                            ->setParameter('technician', $this->security->getUser()->getId() );
                    }
                },
                'label'     => 'Envoyer à :',
                'expanded'  => true,
                'multiple'  => true,
            ])**/
            ->add('display_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'mapped' => false,
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'readonly' => true
                ],
                'label' => 'Date d\'envoi',
                'help' => 'Remplir uniquement en cas d\'envoi différé.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms'
        ]);
    }
}
