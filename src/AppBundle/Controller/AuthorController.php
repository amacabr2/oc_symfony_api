<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller {

    /**
     * Renvoi un auteur
     *
     * @Route("/authors/{id}", name="author_show")
     * @param Author $author
     * @return Response
     */
    public function showAction(Author $author) {
        $data = $this->get('jms_serializer')->serialize($author, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Reçoit les données d'un auteur pour le crée
     *
     * @Route("/authors", name="author_create")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $data = $request->getContent();
        $author = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Author', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();
        return new Response('Auteur crée', Response::HTTP_CREATED);
    }

    /**
     * Renvoi tous les auteurs
     *
     * @Route("/authors", name="author_list")
     * @Method({"GET"})
     * @return Response
     */
    public function listAction() {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();
        $data = $this->get('jms_serializer')->serialize($posts, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
