<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Address\StoreAddressCase;
use App\Validation\Address\AddressValidationRequest;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class SiteController extends AbstractController
{

    /**
     * @throws Exception
     */
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @throws Throwable
     * @throws JsonException
     */
    #[Route('/store', name: 'store', methods: 'POST')]
    public function store(Request $request, AddressValidationRequest $validationRequest, StoreAddressCase $storeAddressCase): Response
    {
        $requestValidateResult = $validationRequest->handle($request);
        if (is_array($requestValidateResult) && count($requestValidateResult) > 0) {
            foreach ($requestValidateResult as $requestValidateErrors) {
                $this->addFlash('error', $requestValidateErrors);
            }

            return $this->redirect('/');
        }

        $storeAddressCase->handle($request->get('address'));

        $this->addFlash('success', 'Адресс успешно добавлен');

        return $this->redirect('/');
    }
}