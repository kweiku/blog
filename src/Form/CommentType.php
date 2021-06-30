<?php
/**
 * Comment type.
 */

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType.
 */
class CommentType extends AbstractType
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
        $builder
            ->add(
                'nick',
                TextType::class,
                [
                    'label' => 'label_nick',
                    'required' => true,
                    'attr' => ['max_length' => 32],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'label_email',
                    'required' => true,
                    'attr' => ['max_length' => 128],
                ]
            )
            ->add(
                'content',
                TextType::class,
                [
                    'label' => 'label_content',
                    'required' => true,
                    'attr' => ['max_length' => 255],
                ]
            )
            ->add(
                'post',
                EntityType::class,
                [
                    'class' => Post::class,
                    'choice_label' => function ($post) {
                        return $post->getTitle();
                    },
                    'label' => 'label_post',
                    'required' => true,
                    'multiple' => false,
                ]
            );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Comment::class]);
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
        return 'comment';
    }
}
