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

use App\Entity\User;


#[Route('/cars')]
final class CarsController extends AbstractController
{
    #[Route(name: 'app_cars_index', methods: ['GET'])]
    public function index(CarsRepository $carsRepository): Response
    {
//        phpinfo();
        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findAll(),
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
            //dd('form VALID');

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

            //$options = new Options();

            $optArray = $form->get('options')->getData();
            if($optArray){
                foreach($optArray as $opt){
                    //$opt->addCar($car);
                    //$entityManager->persist($opt);

                }

                //ADD ID in options_car
                //ADD ID in options_car
                //ADD ID in options_car
                //ADD ID in options_car
            }


            //dd($car);
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
    public function show(Cars $car): Response
    {

        //dump($car->getPhotos()); die;
        return $this->render('cars/show.html.twig', [
            'car' => $car,
            'photo' => $car->getPhotos(),

        ]);
    }

    #[Route('/{id}/edit', name: 'app_cars_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarsType::class, $car);
        $form->handleRequest($request);
        $brands = $entityManager->getRepository(Brands::class)->findAll();
        $carType = $entityManager->getRepository(CarType::class)->findAll();


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cars/edit.html.twig', [
            'car' => $car,
            'types' => $carType,
            'brands' => $brands,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
