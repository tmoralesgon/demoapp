<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

final class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/products', name: 'product_index')]
    public function index(): Response
    {
        //$products = $this->productRepository->findAll();
        //dump($products);
        //dd($products);//quick debugging (dump and die)

        return $this->render('product/index.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }
    #[Route('product/{id<\d+>}')]
    public function show($id): Response
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);
        //$product = $this->productRepository->findOneById($id);
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

}
