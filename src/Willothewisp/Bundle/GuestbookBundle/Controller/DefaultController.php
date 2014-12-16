<?php

namespace Willothewisp\Bundle\GuestbookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WillothewispGuestbookBundle:Default:index.html.twig', array('name' => $name));
    }
}
