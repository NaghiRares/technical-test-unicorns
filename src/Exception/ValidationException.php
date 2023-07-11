<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends HttpException
{
    public function __construct(private ConstraintViolationList $violations, int $statusCode = 400, string $message = '', \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getViolations(): ConstraintViolationList
    {
        return $this->violations;
    }
}