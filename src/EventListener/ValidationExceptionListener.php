<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;


#[AsEventListener('kernel.exception')]
class ValidationExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if(!$exception instanceof ValidationException) {
            return;
        }

        $errors = [];
        foreach ($exception->getViolations() as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        $event->setResponse(new JsonResponse(['errors' => $errors]));
    }
}