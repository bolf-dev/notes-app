<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Note;
use App\Enum\Priority;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NoteFixtures extends Fixture
{
    /**
     * Fixed sample notes – the same data on every load.
     *
     * @var list<array{title: string, content: string, priority: Priority}>
     */
    private const array NOTES = [
        [
            'title' => 'Read "Clean Architecture" – chapters 5–8',
            'content' => 'Focus on the dependency rule and boundaries. Write down examples that apply to our current project.',
            'priority' => Priority::Low,
        ],
        [
            'title' => 'Refill allergy medication before spring',
            'content' => 'Only two packs left. Ask for a new e-prescription during the next online consultation.',
            'priority' => Priority::Medium,
        ],
        [
            'title' => 'Review pull request for the notes API',
            'content' => "Check validation of the priority filter and error responses. Make sure the functional tests cover 404 and 422.\n\nLeave comments by Thursday so the PR can be merged before the sprint ends.",
            'priority' => Priority::Medium,
        ],
        [
            'title' => 'Prepare slides for Monday\'s sprint review',
            'content' => 'Demo of the new note editor, list of finished tasks and open risks. Keep it under 15 minutes.',
            'priority' => Priority::High,
        ],
        [
            'title' => 'Book a dental check-up',
            'content' => 'The last check-up was over a year ago. Book a morning slot online, ideally next week.',
            'priority' => Priority::High,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NOTES as $data) {
            $manager->persist(new Note(
                title: $data['title'],
                content: $data['content'],
                priority: $data['priority'],
            ));
        }

        $manager->flush();
    }
}
