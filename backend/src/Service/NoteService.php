<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\CreateNoteRequest;
use App\Dto\Request\ListNotesQuery;
use App\Dto\Request\UpdateNoteRequest;
use App\Entity\Note;
use App\Enum\Priority;
use App\Exception\NoteNotFoundException;
use App\Repository\NoteRepository;

final readonly class NoteService
{
    public function __construct(
        private NoteRepository $noteRepository,
    ) {
    }

    /**
     * Expects an already validated query.
     *
     * @return list<Note>
     */
    public function list(ListNotesQuery $query): array
    {
        $priority = null !== $query->priority ? Priority::fromLabel($query->priority) : null;

        return $this->noteRepository->findByPriority($priority);
    }

    /**
     * @throws NoteNotFoundException
     */
    public function get(int $id): Note
    {
        return $this->noteRepository->find($id) ?? throw NoteNotFoundException::withId($id);
    }

    /**
     * Expects an already validated request.
     */
    public function create(CreateNoteRequest $request): Note
    {
        $note = new Note(
            title: trim($request->title),
            content: trim($request->content),
            priority: Priority::fromLabel($request->priority),
        );

        $this->noteRepository->save($note);

        return $note;
    }

    /**
     * Expects an already validated request. Fields that are null are left unchanged.
     *
     * @throws NoteNotFoundException
     */
    public function update(int $id, UpdateNoteRequest $request): Note
    {
        $note = $this->get($id);

        if (null !== $request->title) {
            $note->setTitle(trim($request->title));
        }

        if (null !== $request->content) {
            $note->setContent(trim($request->content));
        }

        if (null !== $request->priority) {
            $note->setPriority(Priority::fromLabel($request->priority));
        }

        $this->noteRepository->save($note);

        return $note;
    }

    /**
     * @throws NoteNotFoundException
     */
    public function delete(int $id): void
    {
        $this->noteRepository->remove($this->get($id));
    }
}
