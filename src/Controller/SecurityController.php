<?php


namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractFOSRestController
{
    /**
     * Return a Bearer Token.
     *
     * @Route("/api/login", name="login", methods={"POST"})
     *
     * @OA\Tag(name="Security")
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Bearer Token",
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="token",
     *             type="string"
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=401,
     *     description="Invalid credentials"
     * )
     */
    public function login()
    {
        throw new \Exception('This should never be reached.');
    }
}