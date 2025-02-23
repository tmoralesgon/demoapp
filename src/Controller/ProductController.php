<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
    #[Route('product/{id<\d+>}', 'product_show')]
    public function show(Product $product /* $id */): Response
    {
        //$product = $this->productRepository->findOneBy(['id' => $id]);
        //$product = $this->productRepository->find($id);
        //$product = $this->productRepository->findOneById($id);
        /*if($product === null){
            throw $this->createNotFoundException("Product not found!");
        }*/
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($request->request->all());
            $em->persist($product);
            $em->flush();
            //dd($product);
            $this->addFlash(
                'notice',
                'Product created successfully!'
            );
            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }
        return $this->render('product/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('product/{id<\d+>}/edit', 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash(
                'notice',
                'Product modified successfully!'
            );
            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('product/{id<\d+>}/delete', 'product_delete')]
    public function delete(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        if($request->isMethod('POST')){
            $em->remove($product);
            $em->flush();
            $this->addFlash(
                'notice',
                'Product deleted successfully!'
            );
            return $this->redirectToRoute('product_index');
        }
        return $this->render('product/delete.html.twig', [
            'id' => $product->getId()
        ]);
    }
}
