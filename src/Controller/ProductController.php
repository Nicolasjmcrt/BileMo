<?php

namespace App\Controller;

use App\Entity\Product;
use OpenApi\Annotations as OA;
use App\Repository\ProductRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractFOSRestController
{

   /**
     * Return products list
     *
     * @Route("/api/products", name="products", methods={"GET"})
     *
     * @QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="Offset."
     * )
     * @QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="5",
     *     description="Maximum product per page."
     * )
     *
     * @OA\Tag(name="Product")
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="offset",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Return the list of the products.",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @OA\Response(
     *     response=401,
     *     description="The JWT Token is invalid."
     * )
     */
    public function list(ProductRepository $productRepository, ParamFetcher $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        return $this->json($productRepository->findBy([],['name' => 'ASC'], $limit, $offset), 200, []);
    }

    /**
     * @Route("/api/products/{id}", name="show_product", methods={"GET"})
     *
     * @OA\Tag(name="Product")
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Product ID.",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Productr detail",
     *     @OA\JsonContent(ref=@Model(type=Product::class)),
     * )
     * @OA\Response(
     *     response=401,
     *     description="Invalid JWT Token "
     * )
     * @OA\Response(
     *     response=404,
     *     description="The product was not found."
     * )
     */
    public function show($id, ProductRepository $productRepository)
    {
        return $this->json($productRepository->findOneBy(['id' => $id]), 200, []);
    }
}