<?php

declare(strict_types=1);

namespace App\Controller\Api\Note;

use App\Dto\Request\ListNotesQuery;
use App\Dto\Response\NoteListResponse;
use App\Service\NoteService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notes', name: 'api_notes_list', methods: ['GET'], format: 'json')]
#[OA\Tag(name: 'Notes')]
final class ListNotesController extends AbstractController
{
    public function __construct(
        private readonly NoteService $noteService,
    ) {
    }

    #[OA\Response(response: Response::HTTP_OK, description: 'Notes, newest first', content: new Model(type: NoteListResponse::class))]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Invalid filter')]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY)] ListNotesQuery $query = new ListNotesQuery(),
    ): JsonResponse {
        $notes = $this->noteService->list($query);

        return $this->json(NoteListResponse::fromEntities($notes));
    }
}
