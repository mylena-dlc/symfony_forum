<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ApiController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params) {

        $this->params = $params;
    }

    #[Route('/api/config', name: 'app_api')]
    public function getApiMeteo(): Response
    {
        $api_secret_key = $this->params->get('api_secret_key');
         
        if (!$api_secret_key) {
            return new JsonResponse(['error' => 'API key not found'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['api_secret_key' => $api_secret_key]);

    }
}
