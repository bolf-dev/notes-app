<?php

declare(strict_types=1);

namespace App\Controller\Api\Note;

use App\Dto\Response\NoteResponse;
use App\Service\NoteService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notes/{id}', name: 'api_notes_get', requirements: ['id' => '\d+'], methods: ['GET'], format: 'json')]
#[OA\Tag(name: 'Notes')]
final class GetNoteController extends AbstractController
{
    public function __construct(
        private readonly NoteService $noteService,
    ) {
    }

    #[OA\Response(response: Response::HTTP_OK, description: 'Note detail', content: new Model(type: NoteResponse::class))]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Note not found')]
    public function __invoke(int $id): JsonResponse
    {
        $note = $this->noteService->get($id);

        return $this->json(NoteResponse::fromEntity($note));
    }
}
