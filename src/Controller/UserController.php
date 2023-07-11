<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Unicorn;
use App\Entity\User;
use App\Entity\Post;
use App\Enum\Status;
use App\Services\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class UserController extends AbstractController
{
    #[Route('/users/{id}/unicorns/{unicorn_id}/purchase', name: 'purchase_unicorn', methods: Request::METHOD_POST)]
    #[OA\Response(
        response: 200,
        description: 'Congrats, the unicorn is yours now!',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Unicorn::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Something went wrong!
            (maybe the user does not exist or
            the unicorn does not exist or
            the unicorn was already bought)',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The field used to know what user buy the unicorn',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'unicorn_id',
        in: 'path',
        description: 'The field used to know what unicorn will be bought',
        schema: new OA\Schema(type: 'integer')
    )]
    public function send(
        EmailService $emailService,
        ?User $user,
        #[MapEntity(expr: 'repository.find(unicorn_id)')] ?Unicorn $unicorn,
        ?Post $post,
        EntityManagerInterface $em
    ): JsonResponse {
        if(!$unicorn) {
            return $this->json("This unicorn does not exist, so you can not purchase it!", 404);
        }

        if(!$user) {
            return $this->json("This user does not exist, please use an existent user!", 404);
        }

        if($unicorn->getStatus() == Status::UNPURCHASED) {
            $unicorn->setUser($user);

            $emailContent = "The posts are: \n";
            foreach ($unicorn->getPosts() as $post) {
                $emailContent .=  $post->getContent() . "\n";
                $unicorn->removePost($post);
                $em->remove($post);
            }

            $unicorn->setStatus(Status::PURCHASED);
            $em->persist($unicorn);

            $em->flush();
            $emailService->send($emailContent,$user->getEmail(),'Posts:');

            return $this->json("Congrats, the unicorn is yours now!", 200);
        }

        return $this->json("This unicorn was already bought!", 404);
    }
}