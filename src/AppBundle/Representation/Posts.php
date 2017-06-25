<?php

namespace AppBundle\Representation;


use Pagerfanta\Pagerfanta;
use JMS\Serializer\Annotation\Type;

class Posts {

    /**
     * @var Pagerfanta
     * @Type("array<AppBundle\Entity\Post>")
     */
    private $data;

    /**
     * @var mixed
     */
    private $meta;

    /**
     * Posts constructor.
     * @param Pagerfanta $data
     */
    public function __construct(Pagerfanta $data) {
        $this->data = $data;
        $this->addMeta('limit', $data->getMaxPerPage());
        $this->addMeta('current_items', count($data->getCurrentPageResults()));
        $this->addMeta('total_items', $data->getNbResults());
        $this->addMeta('offset', $data->getCurrentPageOffsetStart());
    }

    /**
     * @param $name
     * @param $value
     */
    public function addMeta($name, $value) {
        if (isset($this->meta[$name])) {
            throw new \LogicException(sprintf("This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta", $name));
        }
        $this->setMeta($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @internal param mixed $meta
     */
    public function setMeta($name, $value) {
        $this->meta[$name] = $value;
    }

    /**
     * @return mixed
     */
    public function getMeta() {
        return $this->meta;
    }

    /**
     * @return Pagerfanta
     */
    public function getData() {
        return $this->data;
    }

}