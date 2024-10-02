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
        $repository = $doctrine->getRepository(Personaje::class);

        $personaje = $repository->find($id);

        return $this->render('personaje.html.twig',['personaje' => $personaje]);
    }

    // Manjar formulario de poderes
    #[Route('/poderes/nuevo', name: 'nuevo')]
    public function nuevo(ManagerRegistry $doctrine, Request $request){
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
                return $this->redirectToRoute('poderes_show', ['id' => $poder->getId()]);
            }
            return $this->render('formulario.html.twig', array(
                'formulario' => $formulario->createView()

            ));
        } else {
            return new Response("No existe ese poder");
        }
    }


    // Manjar formulario de Personaje

    #[Route('/personaje/nuevo', name: 'nuevo_personaje')]
    public function personajeNuevo(ManagerRegistry $doctrine, Request $request): Response{
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
        $entityManager = $doctrine->getManager();
        $poder = new Poderes();
        $poder->setNombre($nombre);
        $poder->setPotencia($potencia);
        $poder->setColor($color);

        $entityManager->persist($poder);
        $entityManager->flush();

        return new Response("Poder insertado correctamente");
    }


    #[Route('/poderes/delete/{nombre}', name: 'poderes_delete')]
    public function delete(string $nombre, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Poderes::class);
        $poder = $repository->findOneBy(["nombre" => $nombre]);
        $entityManager->remove($poder);
        $entityManager->flush();

        return new Response("Poder borrado correctamente");
    }

    #[Route('/poderes/update/{nombre}/{nuevoNombre}', name: 'poderes_update')]
    public function update(string $nombre, string $nuevoNombre, ManagerRegistry $doctrine){
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
        $repository = $doctrine->getRepository(Poderes::class);

        $poder = $repository->find($id);

        return $this->render("poderes.html.twig",['poder' => $poder]);
    }

    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
