<?php


namespace App\Service;


class PaginatorStep
{
    private $paginatorstep;

    public function __construct($paginatorstep)
    {
        $this->paginatorstep = $paginatorstep;
    }
    public function getStep(){
        return $this->paginatorstep;
    }

}