<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Anecdote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnecdoteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function anecdotesAction()
    {
        $repository = $this
          ->getDoctrine()
          ->getRepository('AppBundle:Anecdote');

        $anecdotes = $repository->findBy(array(), array('updated' => 'DESC'), 10);

        return $this->render(
          'home.html.twig',
          array('anecdotes' => $anecdotes)
        );
    }

    /**
     * @Route("/category/{slug}", name="by_category")
     */
    public function anecdotesByCategoryAction($slug)
    {
        $repository = $this
          ->getDoctrine()
          ->getRepository('AppBundle:Anecdote');

        $anecdotes = $repository->getAll(array('category' => $slug), 1, 10);

        return $this->render(
          'home.html.twig',
          array('anecdotes' => $anecdotes)
        );
    }

    /**
     * used by render(controller())
     */
    public function categoriesAction()
    {
        $repository = $this
          ->getDoctrine()
          ->getRepository('AppBundle:Category');

        $categories = $repository->getAll();

        return $this->render(
          'categories.html.twig',
          array('categories' => $categories)
        );
    }

}
