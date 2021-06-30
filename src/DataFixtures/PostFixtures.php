<?php /** @noinspection PhpUnusedParameterInspection */

/**
 * Post fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixtures.
 */
class PostFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     *
     * @noinspection PhpParamsInspection
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'posts', function ($i) {
            $post = new Post();
            $post->setContent($this->faker->text);
            $post->setTitle($this->faker->word);
            $post->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $post->setCategory($this->getRandomReference('categories'));
            $post->setAuthor($this->getRandomReference('admins'));

            return $post;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
