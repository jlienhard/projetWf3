<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    #[Route('/index', name: 'admin_produit_index2', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $rq): Response
    {
        return $this->render('admin/produit/index.html.twig', [
            'produits' => $paginator->paginate($produitRepository->findAll(), $rq->query->get('page', 1), 10)
        ]);
    }

    //-------------------------------------AJAX------------------------------------------------

    #[Route('/liste', name: 'admin_produit_ajax', methods: ['GET'])]
    public function liste(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $rq): Response
    {
        return $this->render('admin/produit/liste.html.twig', [
            'produits' => $paginator->paginate($produitRepository->findAll(), $rq->query->get('page', 1), 10)
        ]);
    }

    //--------------------------------------------------------------------------------------------


    // Si on ajoute un "?" après le nom d'un paramètre de la route, le paramètre devient optionnel
    #[Route('/{page<\d+>?}', name: 'admin_produit_index', methods: ['GET'])]
    public function pagination(ProduitRepository $produitRepository, PaginatorInterface $paginator, $page): Response
    {
        $page = $page ?? 1; // Si $page n'est pas null il vaut $page et sinon il vaut 1 
        return $this->render('admin/produit/index.html.twig', [
            'produits' => $paginator->paginate($produitRepository->findAll(), $page, 10)
        ]);
    }

    #[Route('/new', name: 'admin_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/fiche/{id}', name: 'admin_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'admin_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
