<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\Category;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ChartBuilderInterface $chartBuilder,
    ) {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        return $this->render('admin/my-dashboard.html.twig', [
            'chart' => $chart,
        ]);
        // return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony Forum')
                // you can include HTML contents too (e.g. to link to an image)

                // by default EasyAdmin displays a black square as its default favicon;
                // use this method to display a custom favicon: the given path is passed
                // "as is" to the Twig asset() function:
                // <link rel="shortcut icon" href="{{ asset('...') }}">
                ->setFaviconPath('favicon.svg')
    
                // the domain used by default is 'messages'
                ->setTranslationDomain('my-custom-domain')
    
                // there's no need to define the "text direction" explicitly because
                // its default value is inferred dynamically from the user locale
                ->setTextDirection('ltr')
    
                // set this option if you prefer the page content to span the entire
                // browser width, instead of the default design which sets a max width
                ->renderContentMaximized()
    
                // set this option if you prefer the sidebar (which contains the main menu)
                // to be displayed as a narrow column instead of the default expanded design
                ->renderSidebarMinimized()
    
                // by default, users can select between a "light" and "dark" mode for the
                // backend interface. Call this method if you prefer to disable the "dark"
                // mode for any reason (e.g. if your interface customizations are not ready for it)
                ->disableDarkMode()
    
                // by default, all backend URLs are generated as absolute URLs. If you
                // need to generate relative URLs instead, call this method
                ->generateRelativeUrls()
    
            ;
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-layer-group', Category::class);
        yield MenuItem::linkToCrud('Topics', 'fas fa-list', Topic::class);
        yield MenuItem::linkToCrud('Posts', 'fa-regular fa-message', Post::class);



        
    }
}
