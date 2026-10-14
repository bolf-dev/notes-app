<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Note;
use App\Enum\Priority;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NoteFixtures extends Fixture
{
    private const int NOTES_COUNT = 20;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $faker->seed(1234); // same data on every load

        $priorities = Priority::cases();

        for ($i = 0; $i < self::NOTES_COUNT; ++$i) {
            $note = new Note(
                title: rtrim($faker->sentence(4), '.'),
                content: $faker->paragraphs(2, true),
                priority: $priorities[$i % \count($priorities)],
            );

            $manager->persist($note);
        }

        $manager->flush();
    }
}
