<?php
/**
 * Question fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TaskFixtures.
 */
class QuestionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(50, 'questions', function ($i) {
            $question = new Question();
            $question->setTitle(($this->faker->sentence).'?');
            $question->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $question->setCategory($this->getRandomReference('categories'));

            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0,5)
            );

            foreach ($tags as $tag) {
                $question->addTag($tag);
            }

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $question->setAuthor($author);

            return $question;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: TagFixtures::class, 2: UserFixtures::class}
     *
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}