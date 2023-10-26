<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/books', name: 'app_get_books')]
    public function getAll(ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getRepository(Book::class);
        $books=$em->findAll();
        return $this->render('book/books.html.twig', [
            'books' => $books,
        ]);
    }
    #[Route('/bookForm', name: 'app_form_book')]
    public function bookForm(ManagerRegistry $doctrine, Request  $req): Response
    {
        $book=new Book();
        
        $form=$this->createForm(BookType::class,$book);

        $form->handleRequest($req);

        if($form->isSubmitted()){
            // $book->setPublished(1);
            $em=$doctrine->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_get_books');
        }

        return $this->renderForm('book/index.html.twig', [
            'myform' => $form,
        ]);
    }
    #[Route('/updateBook/{id}', name: 'app_appDate_book')]
    public function upDateBook(ManagerRegistry $doctrine, Request $req,$id): Response
    {
       
        $rep=$doctrine->getRepository(Book::class);
        $book=$rep->find($id);
        $form=$this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        $em=$doctrine->getManager();
        if ($form->isSubmitted())
         
        {
        if($book->published==1){
            return new Response("déja publié");
           }
           else{
           $em->persist($book);
           $em->flush();
           return $this->redirectToRoute('app_get_books');}
          } 

       
          return $this->renderForm('book/index.html.twig', [
            'myform' => $form,
        ]);
    }
}
