<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="products", methods={"GET"})
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
