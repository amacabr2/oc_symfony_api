<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepositiory extends EntityRepository {

    protected function paginate(QueryBuilder $qb, $limit = 20, $offset = 0) {
        if ($limit == 0 or $offset = 0) {
            throw new \LogicException("$limit & $offset must be greater than 0");
        }
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $currentPage = ceil(($offset + 1) / $limit);
        $pager->setCurrentPage($currentPage);
        $pager->setMaxPerPage((int)$limit);
        return $pager;
    }

}