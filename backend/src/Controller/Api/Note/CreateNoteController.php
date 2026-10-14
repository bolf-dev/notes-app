<?php

declare(strict_types=1);

namespace App\Controller\Api\Note;

use App\Dto\Request\CreateNoteRequest;
use App\Dto\Response\NoteResponse;
use App\Service\NoteService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notes', name: 'api_notes_create', methods: ['POST'], format: 'json')]
#[OA\Tag(name: 'Notes')]
final class CreateNoteController extends AbstractController
{
    public function __construct(
        private readonly NoteService $noteService,
    ) {
    }

    #[OA\Response(response: Response::HTTP_CREATED, description: 'Note created', content: new Model(type: NoteResponse::class))]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Validation failed')]
    public function __invoke(
        #[MapRequestPayload(acceptFormat: 'json')] CreateNoteRequest $request,
    ): JsonResponse {
        $note = $this->noteService->create($request);

        return $this->json(NoteResponse::fromEntity($note), Response::HTTP_CREATED);
    }
}
