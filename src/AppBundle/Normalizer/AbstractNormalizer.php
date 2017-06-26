<?php

namespace AppBundle\Normalizer;


abstract class AbstractNormalizer implements NormalizerInterface {

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