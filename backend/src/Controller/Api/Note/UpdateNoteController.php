<?php

declare(strict_types=1);

namespace App\Controller\Api\Note;

use App\Dto\Request\UpdateNoteRequest;
use App\Dto\Response\NoteResponse;
use App\Service\NoteService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notes/{id}', name: 'api_notes_update', requirements: ['id' => '\d+'], methods: ['PATCH'], format: 'json')]
#[OA\Tag(name: 'Notes')]
final class UpdateNoteController extends AbstractController
{
    public function __construct(
        private readonly NoteService $noteService,
    ) {
    }

    #[OA\Response(response: Response::HTTP_OK, description: 'Note updated', content: new Model(type: NoteResponse::class))]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Note not found')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Validation failed')]
    public function __invoke(
        int $id,
        #[MapRequestPayload(acceptFormat: 'json')] UpdateNoteRequest $request,
    ): JsonResponse {
        $note = $this->noteService->update($id, $request);

        return $this->json(NoteResponse::fromEntity($note));
    }
}
