<?php
/**
 * Userdata type.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserdataType.
 */
class UserDataType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstname', TextType::class, [
            'label' => 'label_new_firstname',
            'required' => true,
            'attr' => ['max_length' => 64],
        ]);
        $builder->add('lastname', TextType::class, [
            'label' => 'label_new_lastname',
            'required' => true,
            'attr' => ['max_length' => 255],
        ]);
        $builder->add('email', EmailType::class, [
            'label' => 'label_new_email',
            'required' => true,
            'attr' => ['max_length' => 255],
        ]);
        $builder->add('newPassword', RepeatedType::class, [
            'mapped' => false,
            'required' => false,
            'type' => PasswordType::class,
            'first_options' => ['label' => 'label_new_password'],
            'second_options' => ['label' => 'label_new_password_repeat'],
        ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'users';
    }
}
