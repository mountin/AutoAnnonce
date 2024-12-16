<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Form\BrandsType;
use App\Repository\BrandsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands')]
final class BrandsController extends AbstractController
{
    #[Route(name: 'app_brands_index', methods: ['GET'])]
    public function index(BrandsRepository $brandsRepository): Response
    {
        return $this->render('brands/index.html.twig', [
            'brands' => $brandsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_brands_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $brand = new Brands();
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('app_brands_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('brands/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_brands_show', methods: ['GET'])]
    public function show(Brands $brand): Response
    {
        return $this->render('brands/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_brands_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brands $brand, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_brands_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('brands/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_brands_delete', methods: ['POST'])]
    public function delete(Request $request, Brands $brand, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_brands_index', [], Response::HTTP_SEE_OTHER);
    }
}
