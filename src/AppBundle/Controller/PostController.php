<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller {

    /**
     * Renvoi un article
     *
     * @Get(path="/articles/{id}", name="post_show", requirements={"id"="\d+"})
     * @param Post $post
     * @View
     * @return Post
     */
    public function showAction(Post $post) {
//        $data = $this->get('jms_serializer')->serialize($post, 'json', SerializationContext::create()->setGroups(['detail']));
//        $response = new Response($data);
//        $response->headers->set('Content-Type', 'application/json');
        return $post;
    }

    /**
     * Reçoit les données d'un article pour le crée
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

    /**
     * Renvoi tous les articles
     *
     * @Route("/articles", name="post_list")
     * @Method({"GET"})
     * @return Response
     */
    public function listAction() {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        $data = $this->get('jms_serializer')->serialize($posts, 'json', SerializationContext::create()->setGroups(['list']));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
