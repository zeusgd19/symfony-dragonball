<?php

namespace App\Controller;

use App\Entity\Poderes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
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
    public function show(int $id, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();
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
