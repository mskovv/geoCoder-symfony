<?php

declare(strict_types=1);

namespace App\Validation\Address;

use App\Interface\ValidationServiceInterface;
use App\Validation\CsrfValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

readonly class AddressValidationRequest
{
    public function __construct(
        private CsrfValidator $csrfValidator,
        private ValidationServiceInterface $validationService,
    )
    {
    }

    public function handle(Request $request): bool|array
    {
        $csrfValidate = $this->csrfValidator->validate('address-token', $request);
        if ($csrfValidate === false) {
            return ['csrf-token' => 'CSRF токен недействителен'];
        }

        $rules = [
            'address' => [
                new Assert\NotBlank(['message' => 'Поле не должно быть пустым']),
                new Assert\Length([
                    'max' => 225,
                    'maxMessage' => 'Превышено максимально допустимое количество символов',
                ])
            ]
        ];

        $validateResult = $this->validationService->validate($request->request->all(), $rules);

        if ($validateResult !== []) {
            return $validateResult;
        }

        return true;
    }
}