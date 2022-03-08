<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CommandType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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
    #[Route('/command/add', name: 'commande_create', methods: ['GET', 'POST'])]
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
    #[Route('/command/modify/{id}', name: 'commande_edit', methods: ['GET', 'PATCH'])]
    public function modify(Commande $commande, ProduitRepository $rep, Request $req, EntityManagerInterface $em): Response
    {
        $qte = $commande->getQte();
        $stock = $commande->getProduits()->getStock();

        #get the product which is linked with the command
        $prod = $rep->find($commande->getProduits()->getNumPro());

        #re-add the stock
        $commande->getProduits()->setStock($stock+$qte);
        
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
    #[Route('/command', name: 'commande_list', methods: ['GET', 'POST'])]
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
    #[Route('/command/delete/{id}', name: 'commande_delete', methods: ['DELETE'])]
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
    
    #[Route(path:'/purchase/history', name: 'purchase_history', methods: ['GET', 'POST'])]
    public function history(CommandeRepository $rep, Request $req): Response
    {
        $histories = $rep->findPurchaseHistoryPerProductByYear();

        $form = $this->createFormBuilder(['method' => 'GET'])
            ->add('year', SearchType::class, [
                'required' => false,
                'attr' => [
                    'id' => 'year'
                ]
            ])
            ->add('beginningDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'id' => 'date'
                ]
            ])
            ->add('lastDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'id' => 'date'
                ]
            ])
            ->getForm()
        ;

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            if($form->getData()['year'] !== null){
                $histories = $rep->findPurchaseHistoryPerProductByYear($form->getData()['year']);
            }

            if($form->getData()['beginningDate'] !== null){
                $histories = $rep->findPurchaseHistoryPerProductBetweenToDate(
                    $form->getData()['beginningDate'], $form->getData()['lastDate']??new DateTimeImmutable);
            }
        
        }

        return $this->render('produit/history.html.twig', [
            'histories' => $histories,
            'form' => $form->createView()
        ]);
    }
}
