<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showAuther/{name}', name: 'app_show_author')]
    public function showAuther ($name): Response
    {
        return $this->render('author/show.html.twig', [
            'controller_name' =>$name,
        ]);
    }
    #[Route('/list', name: 'app_list_author')]
    public function afficher (): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com','nb_books' => 300),
        );
        
        return $this->render('author/list.html.twig', [
            'authors' =>$authors,
        ]);
    }

    #[Route('/details/{id}', name: 'app_details_author')]
    public function auhtorDetails ($id): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/victor-hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com','nb_books' => 300),
        );
        $author1=array();
        foreach ($authors as $author){
            if ($author['id'] == $id){
             $author1=$author;}
        }
       

        return $this->render('author/details.html.twig', [
            'id' =>$id, 'author'=>$author
        ]);
    }
    #[Route('/addAuther', name: 'app_add_author')]
    public function addAuther (ManagerRegistry $doctrine): Response
    {   //1
        $author1=new Author();
        $author1->setUsername("wafa");
        $author1->setEmail("wafa@fhjl.com");
        //2
        $em=$doctrine->getManager();
        //3
        $em->persist($author1);
        //4
        $em->flush();
        return $this->render('author/add.html.twig', []);
    }
    #[Route('/getall', name: 'app_affiche_author')]
    public function getAll (ManagerRegistry $doctrine): Response
    {   
        //1
        $repo=$doctrine->getRepository(Author::class);
        //2
        $authors=$repo->findAll();

        return $this->render('author/list.html.twig', ['authors' =>$authors]);
    }
    #[Route('/form', name: 'app_form_author')]
    public function addForm (Request $req,ManagerRegistry $doctrine): Response
    {   //1
        $author=new Author();
      //2
        $form=$this->createForm(AuthorType::class, $author );
       //3
        $form->handleRequest($req); // detecte action , ajouter les donnes dans author
        //4
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            //3
            $em->persist($author);
            //4
            $em->flush();
            return $this->redirectToRoute("app_affiche_author");
        }
        //5
        return $this->renderForm("author/form.html.twig",['myform'=>$form]);
    }

}
