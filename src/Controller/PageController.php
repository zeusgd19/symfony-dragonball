<?php

namespace App\Controller;

use App\Entity\Personaje;
use App\Entity\Poderes;
use App\Form\PersonajeFormType;
use App\Form\PoderesFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{


    // Manejar CRUD personaje
    #[Route('/personaje/insertarConPoderes',name:'personaje_insertar')]
    function insertarConPoderes(ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si no tiene el rol, redirige a otra página o muestra un mensaje de error
            return $this->redirectToRoute('portada');
        }
        $entityManager = $doctrine->getManager();
        $poderes = new Poderes();
        $poderes->setNombre('Rasengan');
        $poderes->setPotencia(15000);
        $poderes->setColor('Azul');

        $personaje = new Personaje();
        $personaje->setNombre('Naruto Uzumaki');
        $personaje->setRaza('Humano/Bijuu');
        $poderes->setPersonaje($personaje);
        $entityManager->persist($personaje);
        $entityManager->persist($poderes);

        $entityManager->flush();

        return new Response("Personaje creado con su poder");

    }

    #[Route('/personaje/show/{id}', name: 'show_personaje')]
    public function showPersonaje(ManagerRegistry $doctrine, int $id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $repository = $doctrine->getRepository(Personaje::class);

        $personaje = $repository->find($id);

        return $this->render('personaje.html.twig',['personaje' => $personaje]);
    }

    // Manjar formulario de poderes
    #[Route('/poderes/nuevo', name: 'nuevo')]
    public function nuevo(ManagerRegistry $doctrine, Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si no tiene el rol, redirige a otra página o muestra un mensaje de error
            return $this->redirectToRoute('portada');
        }
        $poder = new Poderes();
        $formulario = $this->createForm(PoderesFormType::class, $poder);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $poder = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($poder);
            $entityManager->flush();
            return $this->redirectToRoute('poderes_show', ['id' => $poder->getId()]);
        }

        return $this->render('formulario.html.twig', array(
            'formulario' => $formulario->createView()

        ));
    }

    #[Route('/poderes/editar/{id}', name: 'editar')]
    public function editar(ManagerRegistry $doctrine, Request $request, int $id): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityRespository = $doctrine->getRepository(Poderes::class);
        $poder = $entityRespository->find($id);

        if($poder){
            $formulario = $this->createForm(PoderesFormType::class, $poder);
            $formulario->handleRequest($request);

            if($formulario->isSubmitted() && $formulario->isValid()){
                $poder = $formulario->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($poder);
                $entityManager->flush();
                return $this->redirectToRoute('portada');
            }
            return $this->render('formulario.html.twig', array(
                'formulario' => $formulario->createView()

            ));
        } else {
            return new Response("No existe ese poder");
        }
    }

    #[Route("/poderes/eliminar/{id}",name:'eliminar_poderes')]
    public function delete(ManagerRegistry $doctrine,int $id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Poderes::class);
        $poder = $repository->find($id);
        $entityManager->remove($poder);
        $entityManager->flush();

        return $this->redirectToRoute('portada');
    }


    // Manjar formulario de Personaje

    #[Route('/personaje/nuevo', name: 'nuevo_personaje')]
    public function personajeNuevo(ManagerRegistry $doctrine, Request $request): Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si no tiene el rol, redirige a otra página o muestra un mensaje de error
            return $this->redirectToRoute('portada');
        }
        $personaje = new Personaje();
        $formulario = $this->createForm(PersonajeFormType::class,$personaje);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $personaje = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($personaje);
            $entityManager->flush();

            return $this->redirectToRoute('show_personaje',['id' => $personaje->getId()]);
        }

        return $this->render('formulario.html.twig',['formulario' => $formulario->createView()]);
    }

    //Manjar CRUD poderes

    #[Route('/poderes/insertar/{nombre}/{potencia}/{color}', name: 'poderes_insertar')]
    public function insertar(string $nombre, int $potencia, string $color, ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si no tiene el rol, redirige a otra página o muestra un mensaje de error
            return $this->redirectToRoute('portada');
        }
        $entityManager = $doctrine->getManager();
        $poder = new Poderes();
        $poder->setNombre($nombre);
        $poder->setPotencia($potencia);
        $poder->setColor($color);

        $entityManager->persist($poder);
        $entityManager->flush();

        return new Response("Poder insertado correctamente");
    }


    #[Route('/poderes/update/{nombre}/{nuevoNombre}', name: 'poderes_update')]
    public function update(string $nombre, string $nuevoNombre, ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si no tiene el rol, redirige a otra página o muestra un mensaje de error
            return $this->redirectToRoute('portada');
        }
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Poderes::class);

        $poder = $repository->findOneBy(['nombre' => $nombre]);
        $poder->setNombre($nuevoNombre);

        $entityManager->persist($poder);
        $entityManager->flush();

        return new Response("Poder actualizado correctamente");

    }

    #[Route('/poderes/show/{id}', name: 'poderes_show')]
    public function show(int $id, ManagerRegistry $doctrine) : Response{
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repository = $doctrine->getRepository(Poderes::class);

        $poder = $repository->find($id);

        return $this->render("poderes.html.twig",['poder' => $poder]);
    }

    #[Route('/', name: 'portada')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Poderes::class);
        $poder = $repository->findAll();
        return $this->render('portada.html.twig', [
            'poderes' => $poder,
        ]);
    }
}
