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
use Symfony\Component\Validator\ConstraintViolationList;

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
     * @param ConstraintViolationList $violations
     * @return \FOS\RestBundle\View\View
     */
    public function createAction(Author $author, ConstraintViolationList $violations) {
        if (count($violations) != 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
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
     * @Rest\Get(path="/auteurs", name="author_list")
     * @Rest\View
     */
    public function listAction() {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();
        return $posts;
    }

    /**
     * Supprime un auteur
     *
     * @Rest\Delete(path="/auteurs/{id}", name="author_remove", requirements={"id"="\d+"})
     * @Rest\View(StatusCode=204)
     * @param Author $author
     * @internal param Post $post
     */
    public function removeAction(Author $author) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();
    }

    /**
     * Modifie l'auteur
     *
     * @Rest\Put(path="auteurs/{id}", name="author_update", requirements={"id"="\d+"})
     * @Rest\View(StatusCode=200)
     * @param Request $request
     * @param ConstraintViolationList $violations
     * @return \FOS\RestBundle\View\View
     */
    public function updateAuthor(Request $request, ConstraintViolationList $violations) {
        if (count($violations) != 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('AppBundle:Author')->findOneById($request->get('id'));
        if ($request->get('fullname') != null) {
            $author->setFullName($request->get('fullname'));
        }
        if ($request->get('biography') != null) {
            $author->setBiography($request->get('biography'));
        }
        $em->merge($author);
        $em->flush();
    }

}
