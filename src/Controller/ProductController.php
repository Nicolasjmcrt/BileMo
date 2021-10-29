<?php

namespace App\Controller;

use OpenApi\Annotations as OA;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/{user}/rewards", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Reward::class, groups={"full"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="rewards")
     * @Security(name="Bearer")
     */
    public function list(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        return $this->json($productRepository->findAll(), 200, []);
    }

    /**
     * @Route("/api/products/{id}", name="show_product", methods={"GET"})
     */
    public function show($id, ProductRepository $productRepository)
    {
        return $this->json($productRepository->findOneBy(['id' => $id]), 200, []);
    }
}
