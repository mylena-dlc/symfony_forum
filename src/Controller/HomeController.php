<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HomeController extends AbstractController
{
    private $em;

    private $categoryRepository;
    private $topicRepository;
    private $postRepository;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, TopicRepository $topicRepository, PostRepository $postRepository) {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
        $this->topicRepository = $topicRepository;
        $this->postRepository = $postRepository;
    }

    /* Page d'accueil */ 

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $categories = $this->em->getRepository(Category::class)->findAll();

        // Tableau pour stocker le nombre de topics par catégorie
        $nbTopics = [];

        // Pour chaque catégorie, compter le nombre de topics
        foreach ($categories as $category) {
            $topicCount = $this->topicRepository->count(['category' => $category->getId()]);
            $nbTopics[$category->getId()] = $topicCount;
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'nbTopics' => $nbTopics
        ]);
    }

    /* Lister  topics */

    #[Route('/topics', name: 'show_topic')]
    public function displayTopics(): Response
    {
        $topics = $this->em->getRepository(Topic::class)->findAll();

        // Récupérer les catégories pour chaque topic
        $categories = [];
        foreach ($topics as $topic) {
            $categories[$topic->getId()] = $topic->getCategory();
        }

        return $this->render('home/topic.html.twig', [
            'topics' => $topics,
            'categories' => $categories,
        ]);
    }


    /* Lister  Posts */

    #[Route('/posts', name: 'show_post')]
    public function displayPosts(): Response
    {
        $posts = $this->em->getRepository(Post::class)->findAll();

        // // Récupérer les catégories pour chaque topic
        // $categories = [];
        // foreach ($topics as $topic) {
        //     $categories[$topic->getId()] = $topic->getCategory();
        // }

        return $this->render('home/post.html.twig', [
            'posts' => $posts,
        ]);
    }

}
