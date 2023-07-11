<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }


    public function validate(object $objectEntity): void
    {
        $errors = $this->validator->validate($objectEntity);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}