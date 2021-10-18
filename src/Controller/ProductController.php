<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function list(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        dd($products);
    }

    /**
     * @Route("/products/{id}", name="show_product")
     */
    public function show($id, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy([
            'id' => $id
        ]);

        dd($product);
    }
}