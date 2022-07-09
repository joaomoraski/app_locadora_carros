<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos)
    {
        // a query esta sendo montada
        $this->model = $this->model->with($atributos);
    }

    public function selectWithFilter($filtros)
    {
        // se tiver filtro no parametro  ?filtro=
        // explode em : para separar e passa para o where
        // ex: id:>:5
        // id > 5
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $filter) {
            $fil = explode(':', $filter);
            $this->model = $this->model->where($fil[0], $fil[1], $fil[2]);
        }
    }

    public function selectFields($atributos)
    {
        // se tem oparametro atributos ele explode em , e seleciona apenas aqueles atributos
        $this->model = $this->model->select($atributos);
    }

    public function getResult()
    {
        return $this->model->get();
    }
}
