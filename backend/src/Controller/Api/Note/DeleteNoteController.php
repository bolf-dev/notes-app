<?php

declare(strict_types=1);

namespace App\Controller\Api\Note;

use App\Service\NoteService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notes/{id}', name: 'api_notes_delete', requirements: ['id' => '\d+'], methods: ['DELETE'], format: 'json')]
#[OA\Tag(name: 'Notes')]
final class DeleteNoteController extends AbstractController
{
    public function __construct(
        private readonly NoteService $noteService,
    ) {
    }

    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Note deleted')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Note not found')]
    public function __invoke(int $id): Response
    {
        $this->noteService->delete($id);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
