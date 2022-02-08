<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/product", name="index_product")
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/new", name="new_product")
     */
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreationDate(new \DateTime());
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('index_product');
        }
        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="edit_product")
     */
    public function edit(EntityManagerInterface $manager, Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setDateUpdate(new \DateTime());
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('index_product', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="delete_product")
     */
    public function delete(EntityManagerInterface $manager, Product $product): Response
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('index_product', [], Response::HTTP_SEE_OTHER);
    }

}