<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures
 */
class CategoryFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'categories', function ($i) {
            $category = new Category();
            $category->setTitle($this->faker->word);

            $category->setAuthor($this->getRandomReference('admins'));

            return $category;
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
        return [UserFixtures::class];
    }
}
