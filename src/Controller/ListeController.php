<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Tache;
use App\Form\ListeType;
use App\Form\TacheType;
use App\Repository\ListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/liste')]
#[IsGranted('ROLE_USER')]
class ListeController extends AbstractController
{
    #[Route('/', name: 'app_liste_index', methods: ['GET'])]
    public function index(ListeRepository $listeRepository ): Response
    {
        $user = $this->getUser();
        if ($user) {
            $liste = $listeRepository->findBy(['user' => $user]);
            return $this->render('liste/index.html.twig', [
                'listes' => $liste
            ]);
        }  
    }

    #[Route('/new', name: 'app_liste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $liste = new Liste();
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $liste->setUser($user);
            $entityManager->persist($liste);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/new.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_liste_show', methods: ['GET', 'POST'])]

    public function show($id, Liste $liste, Request $request, EntityManagerInterface $entityManager, ListeRepository $listeRepository): Response
    {
            $tache = new Tache();
            $form = $this->createForm(TacheType::class, $tache);
    
            $form->handleRequest($request);
            $user = $this->getUser();
    
            if ($user && $form->isSubmitted() && $form->isValid()) {
    
                $tache->setListe($listeRepository->find($id));
                $tache->setCreateAt(new \DateTimeImmutable);
                $entityManager->persist($tache);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_liste_show', ['id' => $liste->getId()]);
            }
    
            return $this->render('liste/show.html.twig', [
                'liste' => $liste,
                'form' => $form->createView(),
            ]);
        }

    #[Route('/{id}/edit', name: 'app_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/edit.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_delete', methods: ['POST'])]
    public function delete(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($liste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
    }
}
