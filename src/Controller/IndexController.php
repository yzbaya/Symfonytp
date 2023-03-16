<?php 

   namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
  class IndexController extends AbstractController{
    /**
     * @Route("/", name="home")
     */
    public function home(EntityManagerInterface $entityManager):Response{
       // return $this->render('index.html.twig',['name' => $name]);
       //return $this->render('articles/index.html.twig'); 
       //$articles = ['Artcile1', 'Article 2','Article 3'];
      // return $this->render('articles/index.html.twig',['articles' => $articles]); 
      $articles= $entityManager->getRepository(Article::class)->findAll();
    return $this->render('articles/index.html.twig',['articles'=> $articles]);
    }
/**
 * @Route("/article/save")
 */
 public function save(EntityManagerInterface $entityManager):Response {
 $article = new Article();
 $article->setNom('Article 1');
 $article->setPrix(1000);
 $entityManager->persist($article);
 $entityManager->flush();
 return new Response('Article enregisté avec id '.$article->getId());
 }
/**
 * @Route("/article/new", name="new_article")
 * Method({"GET", "POST"})
 */
 

  }