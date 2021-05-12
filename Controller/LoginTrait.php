<?php

namespace Pd\ApiBundle\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

trait LoginTrait
{
    /**
     * @OA\Tag(name="Authorization")
     * @OA\POST(
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user credentials",
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", format="username", example="demo@demo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123123")
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Get JWT Token & User Profile",
     *     @OA\XmlContent(
     *         type="object",
     *         @OA\Xml(name="response"),
     *         @OA\Property(property="token", type="string", example="2IUYC487CR34785CB378CEBY2IU2H"),
     *         @OA\Property(property="data", type="object", example={"id": 1})
     *     ),
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="token", type="string", example="2IUYC487CR34785CB378CEBY2IU2H"),
     *         @OA\Property(property="data", type="object", example={"id": 1})
     *     )
     * )
     */
    #[Route("/auth/login", name: 'api.login', methods: ['POST'])]
    public function attemptLogin(JWTTokenManagerInterface $tokenManager)
    {
        return [
            'token' => $tokenManager->create($this->getUser()),
            'data' => $this->getUser()
        ];
    }
}
