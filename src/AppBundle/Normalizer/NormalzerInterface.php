<?php

namespace AppBundle\Normalizer;


interface NormalzerInterface {

    public function normalize(\Exception $exception);

    public function supports(\Exception $exception);

}