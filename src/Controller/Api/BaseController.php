<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;

abstract class BaseController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    protected function createJsonResponse(Array $data = [], int $status = 200, Array $headers = [])
    {
        if (empty($data)) {
            $data['ok'] = $status === 200;
        }

        $json = $this->serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, $status, $headers);
    }
}
