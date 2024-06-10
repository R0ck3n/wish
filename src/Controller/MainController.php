<?php 

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(EntityManagerInterface $em): Response
    {
        $queryBuilder = $em->getRepository(Wish::class)->createQueryBuilder('w');

        $queryBuilder->select('w', 'c')
            ->leftJoin('w.category', 'c')
            ->where('w.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->orderBy('c.id', 'ASC')
            ->addOrderBy('w.createdAt', 'DESC');

        $wishesRaw = $queryBuilder->getQuery()->getResult();

        // Organiser les résultats par catégorie
        $wishes = [];
        foreach ($wishesRaw as $wish) {
            $categoryName = $wish->getCategory()->getName();
            if (!isset($wishes[$categoryName])) {
                $wishes[$categoryName] = [];
            }
            $wishes[$categoryName][] = $wish;
        }
        return $this->render('wish/liste.html.twig', [
            'titre' => 'Home',
            'wishes' => $wishes,
        ]);
    }

    #[Route('/wish/{id}', name: 'main_film')]
    public function wish(Wish $wish): Response
    {
        return $this->render('main/wish.html.twig', [
            'titre' => 'Details wish :',
            'wish' => $wish,
        ]);
    }

    #[Route('/about', name: 'main_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [
            'titre' => 'About us',
        ]);
    }

    #[Route('/ajouter', name: 'main_ajouter')]
    public function ajouter(EntityManagerInterface $em, Request $request): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setPublished(true);
            $em->persist($wish);
            $em->flush();
            return $this->redirectToRoute("main_home");
        }

        return $this->render('main/ajouter.html.twig', [
            'titre' => 'Ajouter',
            'wishForm' => $wishForm->createView(),
        ]);
    }

    #[Route('/contact', name: 'main_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig', [
            'titre' => 'Contact',
        ]);
    }
}
