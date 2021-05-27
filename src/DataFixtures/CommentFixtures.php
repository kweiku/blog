<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CommentFixtures
 */
class CommentFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $comment = new Comment();
            $comment->setNick($this->faker->userName);
            $comment->setEmail($this->faker->email);
            $comment->setContent($this->faker->sentence);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $this->manager->persist($comment);
        }

        $this->manager->flush();
    }
}