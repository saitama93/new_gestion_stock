<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/account", name="AdminAccount.index")
     */
    public function index()
    {
        return $this->render('admin/account/index.html.twig');
    }
}
