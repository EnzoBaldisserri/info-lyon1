<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;

abstract class BaseController extends AbstractController
{
    protected function createJsonResponse(Array $data = [], int $status = 200, Array $headers = [])
    {
        if (empty($data)) {
            $data['ok'] = $status === 200;
        }

        $serializer = SerializerBuilder::create()->build();
        $json = $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, $status, $headers);
    }
}
