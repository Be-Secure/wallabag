<?php

namespace App\Controller\Api;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ConfigRestController extends WallabagRestController
{
    /**
     * Retrieve configuration for current user.
     *
     * @Operation(
     *     tags={"Config"},
     *     summary="Retrieve configuration for current user.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     *
     * @Route("/api/config.{_format}", methods={"GET"}, name="api_get_config", defaults={"_format": "json"})
     *
     * @return JsonResponse
     */
    public function getConfigAction(SerializerInterface $serializer)
    {
        $this->validateAuthentication();

        $json = $serializer->serialize(
            $this->getUser()->getConfig(),
            'json',
            SerializationContext::create()->setGroups(['config_api'])
        );

        return (new JsonResponse())
            ->setJson($json)
            ->setStatusCode(JsonResponse::HTTP_OK);
    }
}
