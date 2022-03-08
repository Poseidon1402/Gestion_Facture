<?php

namespace App\Controller;

use App\Entity\ProductHistory;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProductHistoryRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/product/add', name: 'product_create', methods: ['GET', 'POST'])]
    public function createpro(ProductHistoryRepository $rep, Request $req, EntityManagerInterface $em):Response
    {
        $produit = new Produit;
        $history = new ProductHistory;

        # get the last occurence on the client table
        $last = $rep->findAll();

        if( count($last) === 0){
            $produit->setNumPro('PRO01');
        }else{
            #filter its identifier so we can get the number after 'PRO'          
            $produit->setNumPro(count($last)<9? 'PRO0'.count($last)+1:'PRO'.count($last)+1);
            
        }
         
        $form = $this->createForm(ProduitType::class ,$produit);
 
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $history->setDesign($produit->getDesign());
            $em->persist($history);
            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Le produit a été ajouté avec succès !');

            return $this->redirectToRoute('product_list');
        }
         

        return $this->render('produit/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * set allowed method as PATCH/GET
     * 
     * @return a Response object
    */
    #[Route('/product/modify/{id}', name: 'product_edit', methods: ['GET', 'PATCH'])]
    public function modify( Produit $produit, Request $req, EntityManagerInterface $em):Response
    {   
        
        $form = $this->createForm(ProduitType::class, $produit,['method' => 'PATCH']);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', 'Le produit a été modifié avec succès !');

            return $this->redirectToRoute('product_list');
        }
        return $this->render('produit/modify.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
        ]);
    }
    /**
     * render all clients from the database
     * 
     * @return a Response Object
    */
    #[Route('/product', name: 'product_list', methods: ['GET'])]
    public function list(ProduitRepository $rep, PaginatorInterface $paginator, Request $req): Response
    {
        $produits = $rep->findAll();

        $pagination = $paginator->paginate(
            $produits,
            $req->query->getInt('page', 1),
            10
        );

        $form = $this->createFormBuilder()
            ->add('search', TextType::class, [
                'attr' => [
                    'placeholder' => "Tapez le nom ou l'identifiant du client"
                ],
                'required' => false
            ])
            ->getForm();
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $specificClients = $rep->search($form->getData()['search']);
            $pagination = $paginator->paginate(
                $specificClients,
                $req->query->getInt('page', 1),
                10
            );

            if($form->getData()['search'] !== null)
                return $this->render('produit/list.html.twig', [
                    'pagination' => $pagination,
                    'form' => $form->createView()
                ]); 
        }
        

        return $this->render('produit/list.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * set allowed method as DELETE.
     * 
     * DELETE is among the HTTP method which is used for deletion
     * 
     * @return a Response object
    */
    #[Route('/product/delete/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(Produit $produit, Request $req, EntityManagerInterface $em): Response
    {
        //csrf protection
        if($this->isCsrfTokenValid('product_deletion_'.$produit->getNumPro(), $req->request->get('csrf_token'))){
            $em->remove($produit);
            $em->flush();

            $this->addFlash('success', 'Le produit a été supprimé avec succès !');
        }

        return $this->redirectToRoute('product_list');
    }    
}
