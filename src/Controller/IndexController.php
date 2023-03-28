<?php 

   namespace App\Controller;


use App\Entity\PropertySearch;
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
use App\Entity\CategorySearch;
use App\Form\CategorySearchType;

  class IndexController extends AbstractController{
    /**
     * @Route("/", name="home")
     */
    public function home(EntityManagerInterface $entityManager):Response{
       // return $this->render('index.html.twig',['name' => $name]);
       //return $this->render('articles/index.html.twig'); 
       //$articles = ['Artcile1', 'Article 2','Article 3'];
      // return $this->render('articles/index.html.twig',['articles' => $articles]); 
   //    $articles= $entityManager->getRepository(Article::class)->findAll();
   //  return $this->render('articles/index.html.twig',['articles'=> $articles]);
   $propertySearch = new PropertySearch();
 $form = $this->createForm(PropertySearchType::class,$propertySearch);
 $form->handleRequest($entityManager);
 //initialement le tableau des articles est vide,
 //c.a.d on affiche les articles que lorsque l'utilisateur
 //clique sur le bouton rechercher
 $articles= [];
 
 if($form->isSubmitted() && $form->isValid()) {
 //on récupère le nom d'article tapé dans le formulaire
$nom = $propertySearch->getNom(); 
 if ($nom!="")
 //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
 $articles= $entityManager->getRepository(Article::class)->findBy(['nom' => $nom] );
 else 
 //si si aucun nom n'est fourni on affiche tous les articles
 $articles=$entityManager->getRepository(Article::class)->findAll();
 }
 return $this->render('articles/index.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]); 
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


 /**
 * @Route("/art_cat/", name="article_par_cat")
 * Method({"GET", "POST"})
 */
 public function articlesParCategorie(Request $request,EntityManagerInterface $entityManager) {
 $categorySearch = new CategorySearch();
 $form = $this->createForm(CategorySearchType::class,$categorySearch);
 $form->handleRequest($request);
 $articles= [];
if($form->isSubmitted() && $form->isValid()) {
 $category = $categorySearch->getCategory();
 
 if ($category!="")
$articles= $category->getArticles();
 else 
 $articles= $entityManager->getRepository(Article::class)->findAll();
 }
 
 return $this->render('articles/articlesParCategorie.html.twig',['form' => $form->createView(),'articles' => $articles]);
 }

 }