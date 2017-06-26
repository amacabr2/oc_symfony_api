<?php
/**
 * Created by PhpStorm.
 * User: amacabr2
 * Date: 26/06/17
 * Time: 11:06
 */

namespace AppBundle\Normalizer;


abstract class AbstractNormalizer implements NormalzerInterface {

    /**
     * @var array
     */
    protected $exceptionTypes;

    /**
     * AbstractNormalizer constructor.
     * @param array $exceptionTypes
     */
    public function __construct(array $exceptionTypes) {
        $this->exceptionTypes = $exceptionTypes;
    }

    public function supports(\Exception $exception) {
        return in_array(get_class($exception), $this->exceptionTypes);
    }

}