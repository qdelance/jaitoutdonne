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

}
