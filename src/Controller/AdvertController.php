<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;

class AdvertController extends AbstractController
{
    /**
     * @Route("/advert", name="advert")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('advert/index.html.twig', [
            'controller_name' => 'AdvertController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home() {

        return $this->render('advert/home.html.twig', [
            'title' => "Bienvenue les amis",
            'age' => "31"
        ]);
    }


    /**
     * @Route("/advert/new", name="article_create")
     * @Route("/advert/{id}/edit", name="article_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager) {

        if(!$article) {
            $article = new Article();
        }

        // $form = $this->createFormbuilder($article)
        //                 ->add('title')
        //                 ->add('content')
        //                 ->add('image')
        //                 ->getForm();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
                $article->setCreatedAt(new \Datetime());
            }
            $manager->persist($article);
            $manager->flush();

            return $this-> redirectToRoute('article', ['id' => $article->getId()]);
        }

        return $this->render('advert/create.html.twig', [
            'formArticle' => $form->createView(),
            'editForm' => $article->getId() !== null
        ]);
    }


    /**
     * @Route("/advert/{id}", name="article")
     */
    public function article(Article $article) {

        return $this->render('advert/article.html.twig', [
            'article' => $article
        ]);
    }

}
