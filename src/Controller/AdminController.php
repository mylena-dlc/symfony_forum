<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $em;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository) {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;

    }
    
    // #[Route('/admin', name: 'app_admin')]
    // public function index(): Response
    // {
    //     $categories = $this->em->getRepository(Category::class)->findAll();

    //     return $this->render('admin/index.html.twig', [
    //         'categories' => $categories,
    //     ]);
    // }

    /* Ajouter une catégorie */

    #[Route('admin/category/new', name: 'add_category')]
    public function addCategory(Request $request, SluggerInterface $slugger): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('An error occurred while uploading the file.');
                }
                $category->setPicture($newFilename);

            }
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/newcategory.html.twig', [
            'formAddCategory' => $form->createView(),
            'category' => $category,
        ]);
    
    }

    /* Supprimer une catégorie */ 

    #[Route('admin/category/{id}/delete', name: 'delete_category')]
    public function deleteCategory(Category $category)
    {
       // Récupérer tous les topics associés à la catégorie
        $topics = $category->getTopics();

        foreach ($topics as $topic) {
            // Récupérer tous les posts associés au topic
            $posts = $topic->getPosts();

            // Supprimer chaque post
            foreach ($posts as $post) {
                $this->em->remove($post);
            }
            // Supprimer le topic
            $this->em->remove($topic);
        }

        // Supprimer la catégorie
        $this->em->remove($category);

        // Exécuter les changements
        $this->em->flush();

        return $this->redirectToRoute('app_admin');
    }



    


}