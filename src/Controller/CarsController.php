<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Entity\Photos;
use App\Form\CarsType;
use App\Repository\CarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Brands;
use App\Entity\CarType;
use App\Entity\Options;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Security\Voter\CarsVoter;


use App\Entity\User;


#[Route('/cars')]
final class CarsController extends AbstractController
{
    #[Route(name: 'app_cars_index', methods: ['GET'])]
    public function index(CarsRepository $carsRepository): Response
    {

        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findAll(),
        ]);
    }

    #[Route('/owncars', name: 'app_cars_ownindex', methods: ['GET'])]
    public function ownindex(CarsRepository $carsRepository, UserInterface $user): Response
    {


        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findBy(['user' => $user->getId()]),
        ]);
    }

    #[Route('/search', name: 'app_cars_search', methods: ['GET', 'POST'])]
    public function search(Request $request, CarsRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $brands = $entityManager->getRepository(Brands::class)->findAll();
        $carType = $entityManager->getRepository(CarType::class)->findAll();
        $options = $entityManager->getRepository(Options::class)->findAll();

        // Create the search form
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);

        $products = [];

        if ($form->isSubmitted() && $form->isValid()) {
            // Get search data
            $searchData = $form->getData();

            //dd($searchData);

            // Query the database using custom repository method
            $products = $productRepository->findBySearchCriteria($searchData);
//            dd($products);
        }

        return $this->render('cars/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'count' => count($products),
            'types' => $carType,
            'brands' => $brands,
            'options' => $options,
        ]);
    }

    #[Route('/new', name: 'app_cars_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $car = new Cars();
        $photo = new Photos();

        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);
        $car->setUser($user);

        $brands = $entityManager->getRepository(Brands::class)->findAll();
        $carType = $entityManager->getRepository(CarType::class)->findAll();
        $options = $entityManager->getRepository(Options::class)->findAll();


        if ($form->isSubmitted() && $form->isValid()) {


            // Ensure the image file is unique
            $imageFile = $car->getImageFile();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $photo->setName($newFilename);
                $photo->setCarId($car);
            }

            $optArray = $form->get('options')->getData();
            if($optArray){
                foreach($optArray as $opt){
                    $opt->addCar($car);
                    $car->addOption($opt);
                    $entityManager->persist($opt);

                }

                //ADD ID in options_car
                //ADD ID in options_car
                //ADD ID in options_car
                //ADD ID in options_car
            }


            $entityManager->persist($photo);
            $entityManager->persist($car);
            $entityManager->flush();


            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }else{
            //dd('form  NOT VALID');
        }


        return $this->render('cars/new.html.twig', [
            'car' => $car,
            'types' => $carType,
            'brands' => $brands,
            'options' => $options,
            'form' => $form,
        ]);


    }

    #[Route('/{id}', name: 'app_cars_show', methods: ['GET'])]
//    #[IsGranted(CarsVoter::EDIT, 'cars')]
    #[IsGranted('POST_VIEW', 'cars', 'Item not found', 404)]
    public function show(Cars $car, EntityManagerInterface $entityManager): Response
    {

        // check for "view" access: calls all voters
//        $this->denyAccessUnlessGranted(CarsVoter::VIEW, $car);
        //$this->denyAccessUnlessGranted('view', $car);
        $options = $car->getOptions();

        return $this->render('cars/show.html.twig', [
            'car' => $car,
            'photo' => $car->getPhotos(),
            'options' => $options,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_cars_edit', methods: ['GET', 'POST'])]
    #[IsGranted(CarsVoter::EDIT, 'car')]
    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {


        $this->denyAccessUnlessGranted(CarsVoter::EDIT, $car);

        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);
        $brands = $entityManager->getRepository(Brands::class)->findAll();
        $carType = $entityManager->getRepository(CarType::class)->findAll();
        $options = $car->getOptions();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cars/edit.html.twig', [
            'car' => $car,
            'types' => $carType,
            'brands' => $brands,
            'form' => $form,
            'options' => $options,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
//    #[IsGranted(CarsVoter::EDIT, 'cars')]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(CarsVoter::EDIT, $car);

        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
