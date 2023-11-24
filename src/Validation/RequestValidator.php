<?php

declare(strict_types=1);

namespace App\Validation;

use App\Interface\ValidationServiceInterface;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RequestValidator implements ValidationServiceInterface
{

    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @return  array<string, string|Stringable> $errors
     *
     */
    public function validate(array $requestParams, array $rules): array
    {
        $constraints = new Assert\Collection($rules);
        $violations = $this->validator->validate($requestParams, $constraints);
        $errors = [];

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $propertyPath = $violation->getPropertyPath();
                $message = $violation->getMessage();
                $errors[$propertyPath] = $message;
            }

            return $errors;
        }

        return $errors;
    }
}