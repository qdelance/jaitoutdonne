<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Anecdote;
use AppBundle\Form\AnecdoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// FIXME Should become a per page parameter one day with a dedicated select
define('NB_PER_PAGE', 25);

/**
 * Class AnecdoteController
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @package AppBundle\Controller
 */

class AnecdoteController extends Controller
{
    /**
     * @Route("/", name="anecdote_list", defaults={"page" = 1})
     * @Route("/anecdotes/{page}", defaults={"page" = 1}, name="anecdote_list_paginated", requirements={"page": "\d+" })
     * @Method("GET")
     */
    public function anecdotesAction(Request $request, $page)
    {
        $repository = $this
          ->getDoctrine()
          ->getRepository('AppBundle:Anecdote');

        $anecdotes = $repository->getAll(array(), $page, NB_PER_PAGE);

        $nbPages = ceil(count($anecdotes) / NB_PER_PAGE);

        return $this->render(
          'anecdote/anecdote_list.html.twig',
          array('anecdotes' => $anecdotes, 'page' => $page, 'nbPages' => $nbPages)
        );
    }

    /**
     * @Route("/add", name="anecdote_add")
     */
    public function anecdoteAddAction(Request $request)
    {
        $anecdote = new Anecdote();

        $form = $this->createForm(AnecdoteType::class, $anecdote);
        // $form = $this->get('form.factory')->create(new AdvertType(), $advert);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($anecdote);
            $em->flush();

            $this->addFlash('info', 'Anecdote added');

            return $this->redirect($this->generateUrl('anecdote_view', array('id' => $anecdote->getId())));
        }

        return $this->render('anecdote/anecdote_edit.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/anecdote/{id}/view", name="anecdote_view")
     * @Method("GET")
     */
    public function anecdoteViewAction(Request $request, $id)
    {
        $repository = $this
          ->getDoctrine()
          ->getRepository('AppBundle:Anecdote');

        $anecdote = $repository->find($id);

        if ($anecdote === null) {
            throw $this->createNotFoundException('No anecdote found for id '.$id);
        }

        return $this->render('anecdote/anecdote_view.html.twig', array('anecdote' => $anecdote));
    }

    /**
     * @Route("/anecdote/{id}/delete", name="anecdote_delete")
     */
    public function anecdoteDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $anecdote = $em->getRepository('AppBundle:Anecdote')->find($id);
        if (null === $anecdote) {
            throw new NotFoundHttpException('No anecdote for id '.$id);
        }
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $em->remove($anecdote);
            $em->flush();
            $this->addFlash('info', 'Anecdote deleted');

            return $this->redirect($this->generateUrl('anecdote_list'));
        }

        // confirm page
        return $this->render('anecdote/anecdote_delete.html.twig', array('form' => $form->createView(), 'anecdote' => $anecdote));
    }

    /**
     * @Route("/anecdote/{id}/edit", name="anecdote_edit")
     */
    public function anecdoteEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $anecdote = $em
          ->getRepository('AppBundle:Anecdote')
          ->find($id);

        if (null === $anecdote) {
            throw new NotFoundHttpException('No anecdote for id '.$id);
        }

        $form = $this->createForm(AnecdoteType::class, $anecdote);
        // $form = $this->get('form.factory')->create(new AdvertType, $advert);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($anecdote);
            $em->flush();

            $this->addFlash('info', 'Anecdote saved');

            return $this->redirect($this->generateUrl('anecdote_view', array('id' => $anecdote->getId())));
        }

        return $this->render('anecdote/anecdote_edit.html.twig', array('form' => $form->createView(), 'anecdote' => $anecdote));
    }

}
