<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller {

    /**
     * Renvoi un article par rapport à son identifiant
     *
     * @Route("/articles/{id}", name="articles_show")
     * @param Post $post
     * @return Response
     */
    public function showAction(Post $post) {
        $data = $this->get('jms_serializer')->serialize($post, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Reçoit les données d'un article crée et l'ajoute en base de données
     *
     * @param Request $request
     * @Route("/articles", name="article_create")
     * @Method({"POST"})
     * @return Response
     */
    public function createAction(Request $request) {
        $data = $request->getContent();
        $post = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Post', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return new Response('Article crée', Response::HTTP_CREATED);
    }

}
