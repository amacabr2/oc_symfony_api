<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Representation\Posts;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class PostController extends FOSRestController {

    /**
     * Renvoi un article
     *
     * @Rest\Get(path="/articles/{id}", name="post_show", requirements={"id"="\d+"})
     * @param Post $post
     * @Rest\View
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
     * @param ConstraintViolationList $violations
     * @return \FOS\RestBundle\View\View
     */
    public function createAction(Post $post, ConstraintViolationList $violations, Request $request) {
        if (count($violations) != 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('AppBundle:Author')->findOneById($request->get('author'));
        $post->setAuthor($author);
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
     * @Rest\Get(path="/articles", name="post_list")
     * @Rest\QueryParam(name="keyword", requirements="[a-zA-Z0-9]", nullable=true, description="The keyword to search for")
     * @Rest\QueryParam(name="order", requirements="asc|desc", default="asc", description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="15", description="Max number of movies page")
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", description="The paginate offset")
     * @Rest\View()
     * @param ParamFetcherInterface $paramFetcher
     * @return Posts
     */
    public function listAction(ParamFetcherInterface $paramFetcher) {
        $pager = $this->getDoctrine()->getRepository('AppBundle:Post')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        return new Posts($pager);
    }

    /**
     * Supprime un article
     *
     * @Rest\Delete(path="/articles/{id}", name="post_remove", requirements={"id"="\d+"})
     * @Rest\View(StatusCode=204)
     * @param Post $post
     */
    public function removeAction(Post $post) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
    }

    /**
     * Modifie un article
     *
     * @Rest\Put(path="articles/{id}", name="post_update", requirements={"id"="\d+"})
     * @Rest\View(StatusCode=200)
     * @param Request $request
     * @param ConstraintViolationList $violations
     * @return \FOS\RestBundle\View\View
     */
    public function updateAction(Request $request, ConstraintViolationList $violations) {
        if (count($violations) != 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['id' => $request->get('id')]);
        if ($request->get('title') != null) {
            $post->setTitle($request->get('title'));
        }
        if ($request->get('content') != null) {
            $post->setContent($request->get('content'));
        }
        $em->merge($post);
        $em->flush();
    }

}
