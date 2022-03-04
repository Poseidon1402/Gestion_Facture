<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CommandType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * set allowed method as POST/GET
     * 
     * We apply this method for adding a new client into our database
     * 
     * @return a Response object
    */
    #[Route('/commande/add', name: 'commande_create', methods: ['GET', 'POST'])]
    public function create(Request $req, EntityManagerInterface $em, FlashyNotifier $flashy): Response
    {
        $commande = new Commande;

        $form = $this->createForm(CommandType::class, $commande);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            if($commande->getProduits()->getStock() < $form->getData()->getQte()){
                return $this->redirectToRoute('commande_create');
            }

            $commande->getProduits()->setStock($commande->getProduits()->getStock()-
                $form->getData()->getQte());
            
            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('commande_list');
        }
        return $this->render('command/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * set allowed method as PATCH/GET
     * 
     * @return a Response object
    */
    #[Route('/commande/modify/{id}', name: 'commande_edit', methods: ['GET', 'PATCH'])]
    public function modify(Commande $commande, Request $req, EntityManagerInterface $em): Response
    {
        $qte = $commande->getQte();
        $stock = $commande->getProduits()->getStock();

        $prod = $commande->getProduits();

        $commande->getProduits()->setStock($commande->getProduits()->getStock()+$qte);
        $prod = $commande->getProduits();

        $form = $this->createForm(CommandType::class, $commande, [
            'method' => 'PATCH'
        ]);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            if($commande->getProduits()->getStock() < $form->getData()->getQte()){
                $prod->setStock($stock);
                $commande->setProduits($prod);
                $commande->setQte($qte);

                $em->flush();

                return $this->redirectToRoute('commande_edit', ['id' => $commande->getId()]);
            }
            $commande->getProduits()->setStock($commande->getProduits()->getStock() - $form->getData()->getQte());

            $em->flush();

            return $this->redirectToRoute('commande_list');
        }

        return $this->render('command/modify.html.twig', [
            'commande' => $commande,
            'form' => $form->createView()
        ]);
    }

    /**
     * render all clients from the database
     * 
     * @return a Response Object
    */
    #[Route('/commande', name: 'commande_list', methods: ['GET', 'POST'])]
    public function show(CommandeRepository $rep): Response
    {
        $commandes = $rep->findAll();
        
        return $this->render('command/list.html.twig', compact('commandes'));
    }

    /**
     * set allowed method as DELETE.
     * 
     * DELETE is among the HTTP method which is used for deletion
     * 
     * @return a Response object
    */
    #[Route('/commande/delete/{id}', name: 'commande_delete', methods: ['DELETE'])]
    public function delete(Commande $commande, Request $req, EntityManagerInterface $em): Response
    {
        //csrf protection
        if($this->isCsrfTokenValid('command_deletion_'.$commande->getId(), $req->request->get('csrf_token'))){
            $prod = $commande->getProduits();
            $prod->setStock($commande->getQte()+$prod->getStock());
            $em->remove($commande);
            $em->persist($prod);
            $em->flush();
        }

        return $this->redirectToRoute('commande_list');
    }    
}
