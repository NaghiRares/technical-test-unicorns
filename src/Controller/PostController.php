<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Entity\User;
use App\Services\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;


class PostController extends AbstractController
{
    #[Route('/posts', name: 'get_post', methods: Request::METHOD_GET)]
    #[OA\Response(
        response: 200,
        description: 'Returns the existent posts',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Post::class))
        )
    )]
    public function get(PostRepository $postRepository): JsonResponse
    {
        return $this->json($postRepository->getAll());
    }


    #[Route('/posts', name: 'create_post', methods: Request::METHOD_POST)]
    #[OA\Response(
        response: 200,
        description: 'Insert a new post in system',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Post::class))
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
                    new OA\Property(property: "unicorn", type: "integer")
                ]
            )
        )]
    )]
    public function create(
        EntityManagerInterface $em,
        Request $request,
        ValidatorService $validator,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $post = $serializer->deserialize(
            $request->getContent(),
            Post::class, JsonEncoder::FORMAT,
            [AbstractObjectNormalizer::GROUPS => ['post_get'], AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true],
        );

        $requestParameter = json_decode($request->getContent(),true);
        $user = $post->getUser();

        if (!$user) {
            if (isset($requestParameter['email'])) {
                $user = $em->getRepository(User::class)->findOneBy(['email' => $requestParameter['email']]);
                if (!$user) {
                    $user = new User();
                    $user->setName('Rares Naghi');
                    $user->setEmail($requestParameter['email']);
                }
            } else {
                return $this->json('Please provide user email or user ID', 404);
            }
        }

        $validator->validate($user);

        $em->persist($user);
        $post->setUser($user);

        $validator->validate($post);

        $em->persist($post);
        $em->flush();

        return $this->json($post, 200, context: [AbstractObjectNormalizer::GROUPS => ['post_get']]);
    }


    #[Route('/posts/{id}', name: 'update_post', methods: [Request::METHOD_PUT, Request::METHOD_PATCH])]
    #[OA\Response(
        response: 200,
        description: 'The update of the post was made successfully',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Post::class))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request! The validation of one field failed',
    )]
    #[OA\Response(
        response: 404,
        description: 'No post found'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The field used to know what post should be updated',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        content: [new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: "content", type: "string"),
                ]
            )
        )]
    )]
    public function update(
        PostRepository $postRepository,
        ?Post $post,
        Request $request,
        ValidatorService $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        if (!$post) {
            return $this->json('No post found ', 404);
        }

        $post = $serializer->deserialize(
            $request->getContent(),
            Post::class, JsonEncoder::FORMAT,
            [AbstractNormalizer::OBJECT_TO_POPULATE => $post,
            AbstractObjectNormalizer::GROUPS => ['post_update']]
        );
        $validator->validate($post);

        $postRepository->save($post,true);

        return $this->json($post, 200, context: [AbstractObjectNormalizer::GROUPS => ['post_get']]);
    }

    #[Route('/posts/{id}', name: 'delete_post', methods: Request::METHOD_DELETE)]
    #[OA\Response(
        response: 200,
        description: 'The delete of the post was made successfully',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Post::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'No post found'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The field used to know what post should be deleted',
        schema: new OA\Schema(type: 'integer')
    )]
    public function delete(?Post $post, PostRepository $postRepository): JsonResponse
    {
        if (!$post) {
            return $this->json('No post found', 404);
        }

        $postRepository->remove($post,true);

        return $this->json($post, 200, context: [AbstractObjectNormalizer::GROUPS => ['post_get']]);
    }
}
