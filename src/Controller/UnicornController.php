<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UnicornRepository;
use App\Entity\Unicorn;
use App\Services\ValidatorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class UnicornController extends AbstractController
{
    #[Route('/unicorns', name: 'get_unicorns', methods: Request::METHOD_GET)]
    #[OA\Response(
        response: 200,
        description: 'Returns the existent unicorns',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Unicorn::class))
        )
    )]
    public function get(UnicornRepository $unicornRepository): JsonResponse
    {
        return $this->json($unicornRepository->getAll());
    }


    #[Route('/unicorns', name: 'create_unicorns', methods: Request::METHOD_POST)]
    #[OA\Response(
        response: 200,
        description: 'Insert a new unicorn in system',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Unicorn::class))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request! The validation of one field failed',
    )]
    #[OA\RequestBody(
        content: [new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: "content", type: "string"),
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "price", type: "integer"),
                    new OA\Property(property: "status", type: "integer"),
                ]
            )
        )]
    )]
    public function create(
        UnicornRepository $unicornRepository,
        Request $request,
        ValidatorService $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        $unicorn = $serializer->deserialize(
            $request->getContent(),
            Unicorn::class, JsonEncoder::FORMAT,
            [AbstractObjectNormalizer::GROUPS => ['post_get'], AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true],
        );

        $validator->validate($unicorn);

        $unicornRepository->save($unicorn,true);

        return $this->json($unicorn, 200, context: [AbstractObjectNormalizer::GROUPS => ['post_get']]);
    }


    #[Route('/unicorns/{id}', name: 'update_unicorns', methods: [Request::METHOD_PUT, Request::METHOD_PATCH])]
    #[OA\Response(
        response: 200,
        description: 'The update of the unicorn was made successfully',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Unicorn::class))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request! The validation of one field failed',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The field used to know what unicorn should be updated',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        content: [new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: "content", type: "string"),
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "price", type: "integer"),
                    new OA\Property(property: "status", type: "integer"),
                ]
            )
        )]
    )]
    public function update(
        UnicornRepository $unicornRepository,
        ?Unicorn $unicorn,
        Request $request,
        ValidatorService $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        if (!$unicorn) {
            return $this->json('No unicorn found ', 404);
        }

        $unicorn = $serializer->deserialize(
            $request->getContent(),
            Unicorn::class, JsonEncoder::FORMAT,
            [AbstractNormalizer::OBJECT_TO_POPULATE => $unicorn,
            AbstractObjectNormalizer::GROUPS => ['post_update']]
        );
        $validator->validate($unicorn);

        $unicornRepository->save($unicorn,true);

        return $this->json($unicorn, 200, context: [AbstractObjectNormalizer::GROUPS => ['post_get']]);
    }


    #[Route('/unicorns/{id}', name: 'delete_unicorn', methods: Request::METHOD_DELETE)]
    #[OA\Response(
        response: 200,
        description: 'The delete of the unicorn was made successfully',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Unicorn::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'No unicorn found'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The field used to know what unicorn should be deleted',
        schema: new OA\Schema(type: 'integer')
    )]
    public function delete(?Unicorn $unicorn, UnicornRepository $unicornRepository): JsonResponse
    {
        if (!$unicorn) {
            return $this->json('No unicorn found', 404);
        }

        $unicornRepository->remove($unicorn,true);

        return $this->json($unicorn, 200, context: [AbstractObjectNormalizer::GROUPS => ['unicorn_get']]);
    }
}
