<?php

namespace Willothewisp\Bundle\GuestbookBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;

use Willothewisp\Bundle\GuestbookBundle\Entity\Post;
use Willothewisp\Bundle\GuestbookBundle\Form\PostType;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{

    /**
     * Lists all Post entities.
     *
     */
    public function indexAction()
    {
        $posts = $this->get('willothewisp_guestbook.post.repository')->findNewest();;

        $form = $this->createCreateForm(new Post());

        return $this->render('WillothewispGuestbookBundle:Post:index.html.twig', array(
            'form' => $form->createView(),
            'posts' => $posts,
        ));
    }

    /**
     * Lists of Post entities sorted by author.
     */

    public function postsByAuthorAction()
    {

    }
    /**
     * Creates a new Post entity.
     *
     */
    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createCreateForm($post);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $result = $this->get('willothewisp_guestbook_post.timer.post.timer')->requestProcess($request);

//            $alreadyExistCookie = $request->cookies->get('name');
//            if ($alreadyExistCookie) {
//
//                $request->getSession()->getFlashBag()->add(
//                    'notice',
//                    'You have already created message! Try again lately!'
//                );
//                return $this->redirect($this->generateUrl('post'));
//            }
//            else {
//                $cookie = new Cookie('name', sha1(1234,5678), time() + 60);
//
//                $response = new RedirectResponse($this->generateUrl('post'));
//                $response->headers->setCookie($cookie);
////
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($post);
//                $em->flush();
//
//                $request->getSession()->getFlashBag()->add(
//                    'notice',
//                    'Congratulations! Your message was saved! You can create new message after 1 minute!'
//                );
//
//                return $response;
//            }
            var_dump($result);
        }

        return $this->render('WillothewispGuestbookBundle:Post:new.html.twig', array(
            'post' => $post,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Post post.
     *
     * @param Post $post The post
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Post $post)
    {
        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('post_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Post post.
     *
     */
    public function newAction()
    {
        $post = new Post();
        $form   = $this->createCreateForm($post);

        return $this->render('WillothewispGuestbookBundle:Post:new.html.twig', array(
            'post' => $post,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Post entity.
     *
     */
    public function showAction($id)
    {
       $post = $this->get('willothewisp_guestbook.post.repository')->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post post.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('WillothewispGuestbookBundle:Post:show.html.twig', array(
            'post'      => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Post entity associated with author.
     */
    public function postsAssociatedWithAuthorAction($author)
    {
        $posts = $this->get('willothewisp_guestbook.post.repository')->findPostsAssociatedWithAuthor($author);

        if (!$posts) {
            throw $this->createNotFoundException('Unable to find Post post.');
        }

        return $this->render('WillothewispGuestbookBundle:Post:postsAssociatedWithAuthor.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Finds and displays a Post entity associated with author.
     */
    public function postsAssociatedWithDomainAction($url)
    {
        $posts = $this->get('willothewisp_guestbook.post.repository')->findPostsAssociatedWithDomain($url);

        if (!$posts) {
            throw $this->createNotFoundException('Unable to find Post post.');
        }

        return $this->render('WillothewispGuestbookBundle:Post:postsAssociatedWithAuthor.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Displays a form to edit an existing Post post.
     *
     */
    public function editAction($id)
    {
        $post = $this->get('willothewisp_guestbook.post.repository')->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post post.');
        }

        $editForm = $this->createEditForm($post);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('WillothewispGuestbookBundle:Post:edit.html.twig', array(
            'post'      => $post,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Post entity.
    *
    * @param Post $post The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Post $post)
    {
        $form = $this->createForm(new PostType(), $post, array(
            'action' => $this->generateUrl('post_update', array('id' => $post->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Post entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $post = $this->get('willothewisp_guestbook.post.repository')->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post post.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($post);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('post_edit', array('id' => $id)));
        }

        return $this->render('WillothewispGuestbookBundle:Post:edit.html.twig', array(
            'post'      => $post,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Post entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $post = $this->get('willothewisp_guestbook.post.repository')->find($id);

            if (!$post) {
                throw $this->createNotFoundException('Unable to find Post post.');
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('post'));
    }

    /**
     * Creates a form to delete a Post entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
