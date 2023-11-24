<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

readonly class CsrfValidator
{

    public function __construct(private CsrfTokenManagerInterface $csrfTokenManager)
    {
    }

    public function validate(string $tokenId, Request $request): bool
    {
        $token = $request->request->get('token');

        if ($token === null) {
            return false;
        }
        $request->request->remove('token');

        return $this->csrfTokenManager->isTokenValid(new CsrfToken($tokenId, (string)$token));
    }
}