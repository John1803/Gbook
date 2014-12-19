<?php

namespace Willothewisp\Bundle\GuestbookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    public function listAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('WillothewispGuestbookBundle:Post');
        $posts = $repository->findAll();

        return $this->render('WillothewispGuestbookBundle:Post:list.html.twig', array('posts' => $posts));
    }
}