<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user/list/{page<\d+>?1}", name="AdminUser.index")
     */
    public function index($page, PaginationService $paginator)
    {
        $paginator->setEntityClass(User::class)
            ->setCurrentPage($page)
            ->setLimit(10);

        return $this->render('admin/user/index.html.twig', [
            'paginator' => $paginator,
            'present' => true
        ]);
    }

    /**
     * Affiche la liste des utilisateurs
     * 
     * @Route("/admin/user/add", name="AdminUser.add")
     */
    public function add(Request $request,  UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPasswd = $user->getPassword();
            $user->setPlainPassword($plainPasswd);

            $password = $passwordEncoder->encodePassword($user, $plainPasswd);
            $user->setPassword($password);

            $user->setPresent(1);
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                'Compte créé et un mail vous a été envoyé avec vos identifiants'
            );

            return $this->redirectToRoute('AdminUser.index');
        }

        return $this->render('admin/user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
