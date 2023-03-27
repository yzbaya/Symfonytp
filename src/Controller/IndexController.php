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
use App\Form\ArticleType;
use App\Entity\Category;
use App\Form\CategoryType;
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
 
public function new(Request $request): Response {
 $article = new Article();
$form = $this->createForm(ArticleType::class,$article);
 
 $form->handleRequest($request);
 if($form->isSubmitted() && $form->isValid()) {
 $article = $form->getData();

 return $this->redirectToRoute('article_list');
 }
 return $this->render('articles/new.html.twig',['form' => $form->createView()]);

 }

 /**
 * @Route("/article/{id}", name="article_show")
 */

public function show(EntityManagerInterface $entityManager,int $id): Response
    {
      $article = $entityManager->getRepository(article::class)->find($id);
      return $this->render('articles/show.html.twig',array('article' => $article));
 }

/**
 * @Route("/article/edit/{id}", name="edit_article")
 * Method({"GET", "POST"})
 */
public function edit(EntityManagerInterface $entityManager,int $id,Request $request): Response
    {
      $article = new Article();
      $article =$entityManager->getRepository(Article::class)->find($id);
      $form = $this->createForm(ArticleType::class,$article);
       
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('article_list');   
         }
        return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }

 /**
 * @Route("/article/delete/{id}",name="delete_article")
 * @Method({"DELETE"})*/
 public function delete(EntityManagerInterface $entityManager):Response{
    $article = $entityManager->getRepository(Article::class);
    $entityManager->remove($article);
    $entityManager->flush();
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('article_list');
 }

 /**
 * @Route("/category/newCat", name="new_category")
 * Method({"GET", "POST"})
 */
 public function newCategory(Request $request,EntityManagerInterface $entityManager) {
      $category = new Category();
      $form = $this->createForm(CategoryType::class,$category);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();
      $entityManager->persist($category);
      $entityManager->flush();
 }
return $this->render('articles/newCategory.html.twig',['form'=>$form->createView()]);
 }

 }