<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientController extends AbstractController
{
    /**
     * set allowed method as POST/GET
     * 
     * We apply this method for adding a new client into our database
     * 
     * @return a Response object
    */
    #[Route('/client/add', name: 'client_create', methods: ['GET', 'POST'])]
    public function create(ClientRepository $rep, Request $req, EntityManagerInterface $em): Response
    {
        $client = new Client;

        # get the last occurence on the client table
        $last = $rep->findBy([], ['numcli' => 'DESC'], 1);

        #filter its identifier so we can get the number after 'CL' 
        if(count($last) === 0){
            $client->setNumcli('CL01');
        }else{
            $lastNumCli = (int) filter_var($last[0]->getNumcli(), FILTER_SANITIZE_NUMBER_INT);
        
            $client->setNumcli($lastNumCli<9? 'CL0'.$lastNumCli+1:'CL'.$lastNumCli+1);
        }

        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_list');
        }
        return $this->render('client/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * set allowed method as PATCH/GET
     * 
     * @return a Response object
    */
    #[Route('/client/modify/{id}', name: 'client_edit', methods: ['GET', 'PATCH'])]
    public function modify(Client $client, Request $req, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClientType::class, $client, [
            'method' => 'PATCH'
        ]);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            return $this->redirectToRoute('client_list');
        }

        return $this->render('client/modify.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * render all clients from the database
     * 
     * @return a Response Object
    */
    #[Route('/client', name: 'client_list', methods: ['GET', 'POST'])]
    public function show(ClientRepository $rep, Request $req): Response
    {
        $clients = $rep->findAll();

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

            if($form->getData()['search'] !== null)
                return $this->render('client/list.html.twig', [
                    'clients' => $specificClients,
                    'form' => $form->createView()
                ]); 
        }
        

        return $this->render('client/list.html.twig', [
            'clients' => $clients,
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
    #[Route('/client/delete/{id}', name: 'client_delete', methods: ['DELETE'])]
    public function delete(Client $client, Request $req, EntityManagerInterface $em): Response
    {
        //csrf protection
        if($this->isCsrfTokenValid('client_deletion_'.$client->getNumcli(), $req->request->get('csrf_token'))){
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_list');
    }    
}
