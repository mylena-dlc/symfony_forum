<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Topic;
use App\Form\PostType;
use App\Form\TopicType;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    private $em;

    private $categoryRepository;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository) {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }
    
    /* Lister le détail d'une catégorie */ 

    #[Route('/category/{categoryId<\d+>}', name: 'app_category')]
    public function index(int $categoryId, Request $request): Response
    {
        $category = $this->em->getRepository(Category::class)->find($categoryId);

        $topics = $this->em->getRepository(Topic::class)->findBy(['category' => $categoryId]);

        $topicPosts = [];
        $numberPosts = [];


        foreach ($topics as $topic) {
            $posts = $this->em->getRepository(Post::class)->findBy(
                ['topic' => $topic], 
                ['dateCreationPost' => 'ASC'],
                3
            );
            $topicPosts[$topic->getId()] = $posts;

             // Compter le nombre de posts pour chaque topic
            $numberPosts[$topic->getId()] = $this->em->getRepository(Post::class)->count(['topic' => $topic]);
        }

        // Création d'un sujet
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user = $this->em->getRepository(User::class)->find($userId);
                
                // on récupère l'id de l'utilisateur connecté et son email
                $user = $this->getUser();
            
                $topic = $form->getData();
                $topic->setCategory($category); 
                $topic->setUser($user);
                $this->em->persist($topic);
                $this->em->flush();
                return $this->redirectToRoute('app_category', ['categoryId' => $categoryId]);
        }
   
        return $this->render('forum/index.html.twig', [
            'category' => $category,
            'topics' => $topics,
            'topicPosts' => $topicPosts,
            'numberPosts' => $numberPosts,  
            'formAddTopic' => $form->createView(),
        ]);
    }


    /* Lister le détail d'un topic */ 

    #[Route('/category/{categoryId<\d+>}/topic/{topicId<\d+>}', name: 'app_topic')]
    public function showTopic(int $categoryId, int $topicId, Request $request): Response
    {
        $category = $this->em->getRepository(Category::class)->find($categoryId);
        $topic = $this->em->getRepository(Topic::class)->find($topicId);
        $posts = $this->em->getRepository(Post::class)->findBy(['topic' => $topicId]);

        // Création d'un commentaire
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post->setUser($user);
            $post->setTopic($topic);
           
            $this->em->persist($post);
            $this->em->flush();
            
            return $this->redirectToRoute('app_topic', ['categoryId' => $categoryId, 'topicId' => $topicId]);
        }

        return $this->render('forum/topic.html.twig', [
            'category' => $category,
            'topic' => $topic,
            'posts' => $posts,
            'formAddPost' => $form->createView(),
        ]);
    }


        /* Supprimer un topic */ 

        #[Route('/category/{categoryId<\d+>}/delete/{topicId<\d+>}', name: 'delete_topic')]
        public function deleteTopic(int $categoryId, int $topicId,)
        {
             
            $category = $this->em->getRepository(Category::class)->find($categoryId);
            $topic = $this->em->getRepository(Topic::class)->find($topicId);

            $posts = $topic->getPosts();

             // Supprimer chaque post
             foreach ($posts as $post) {
                $this->em->remove($post);
            }

            $this->em->remove($topic);
            $this->em->flush();
    
            return $this->redirectToRoute('app_category', ['categoryId' => $category->getId()]);

        }

}