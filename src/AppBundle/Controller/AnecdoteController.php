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
     */
    public function categoriesAction()
    {
        $repository = $this
          ->getDoctrine()
          ->getRepository('AppBundle:Category');

        // $categories = $repository->findBy(array(), array('name' => 'ASC'));
        $categories = $repository->getAll();

        dump($categories);

        return $this->render(
          'categories.html.twig',
          array('categories' => $categories)
        );
    }

}
