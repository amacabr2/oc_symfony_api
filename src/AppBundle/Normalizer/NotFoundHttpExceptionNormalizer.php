<?php
/**
 * Created by PhpStorm.
 * User: amacabr2
 * Date: 26/06/17
 * Time: 11:10
 */

namespace AppBundle\Normalizer;


use Symfony\Component\HttpFoundation\Response;

class NotFoundHttpExceptionNormalizer extends AbstractNormalizer {

    /**
     * @param \Exception $exception
     * @return mixed
     */
    public function normalize(\Exception $exception) {
        $result['code'] = Response::HTTP_NOT_FOUND;
        $result['body'] = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $exception->getMessage()
        ];
        return $result;
    }

}