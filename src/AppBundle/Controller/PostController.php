<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
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

class PostController extends FOSRestController {

    /**
     * Renvoi un article
     *
     * @Rest\Get(path="/articles/{id}", name="post_show", requirements={"id"="\d+"})
     * @param Post $post
     * @Rest\View)
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
     * @Rest\Post(path="/articles", name="post_create")
     * @Rest\View(StatusCode=201)
     * @ParamConverter("post", converter="fos_rest.request_body")
     * @param Post $post
     * @return Response
     */
    public function createAction(Post $post) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return $this->view(
            $post,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('post_show', ['id' => $post->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
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
