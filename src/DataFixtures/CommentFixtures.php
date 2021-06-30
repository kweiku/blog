<?php /** @noinspection PhpUnused */

/** @noinspection PhpUnusedParameterInspection */

/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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
        $this->createMany(100, 'comments', function ($i) {
            $comment = new Comment();
            $comment->setNick($this->faker->userName);
            $comment->setEmail($this->faker->email);
            $comment->setContent($this->faker->sentence);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $comment->setPost($this->getRandomReference('posts'));

            return $comment;
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
        return [PostFixtures::class];
    }
}
