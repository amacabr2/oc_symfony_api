<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthorController extends FOSRestController {

    /**
     * Renvoi un auteur
     *
     * @Rest\Get(path="/auteurs/{id}", name="author_show", requirements={"id"="\d+"})
     * @param Author $author
     * @Rest\View)
     * @return Author
     */
    public function showAction(Author $author) {
        return $author;
    }

    /**
     * Reçoit les données d'un auteur pour le crée
     *
     * @Rest\Post(path="/auteurs", name="author_create")
     * @Rest\View(StatusCode=201)
     * @ParamConverter("author", converter="fos_rest.request_body")
     * @param Author $author
     * @return Response
     */
    public function createAction(Author $author) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();
        return $this->view(
            $author,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('author_show', ['id' => $author->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * Renvoi tous les auteurs
     *
     * @Route("/auteurs", name="author_list")
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
