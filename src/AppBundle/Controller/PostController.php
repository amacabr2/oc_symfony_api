<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller {

    /**
     * Renvoi un article par rapport à son identifiant
     *
     * @Route("/articles/{id}", name="articles_show")
     */
    public function showAction() {
        $article = new Post();
        $article
            ->setTitle('Mon premier article')
            ->setContent('Le contenu de mon premier article');
        $data = $this->get('jms_serializer')->serialize($article, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
