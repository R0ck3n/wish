<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use DateTime;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish')]
class WishController extends AbstractController
{
    #[Route('/liste', name: 'wish_liste')]
    public function liste(WishRepository $repo): Response
    {
        $wishes = $repo->findBy(["isPublished" => true], ["createdAt" => "DESC"]);
       
        return $this->render('wish/liste.html.twig', [
            'wishes' =>$wishes,
        ]);
    }

    // #[Route('/ajouter', name: 'wish_ajouter')]
    // public function ajouter(): Response
    // {
    //    // ajouter
    // //    $wish = new Wish();
    // //    $wish->setTitle('Aller à Tours');
    // //    $wish->setDescription('lorem');
    // //    $wish->setAuthor('Bob');
    // //    $wish->setCreatedAt(new \DateTime());
    // //    $wish->setPublished(true);
    // //    $em->persist($wish);
    // //    $em->flush();

    // //    dd($wish);

    //    return $this->render('wish/add.html.twig', [
    //     'titre' => 'Ajouter un wish'

    // ]);
      
    //}
    #[Route('/details/{id}', name: 'wish_detail')]
    public function detail(Wish $wish): Response
    {
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }
    
}
