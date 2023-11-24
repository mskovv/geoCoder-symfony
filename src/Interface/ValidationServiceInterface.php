<?php

declare(strict_types=1);

namespace App\Interface;

use Symfony\Component\Validator\Constraint;

interface ValidationServiceInterface
{
    /**
     * @param array<string, string> $requestParams
     * @param array<string, Constraint> $rules
     *
     * @return  array<string, string> $rules
     */
    public function validate(array $requestParams, array $rules): array;
}