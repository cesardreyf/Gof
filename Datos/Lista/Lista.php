<?php

namespace Gof\Datos\Lista;

use Gof\Interfaz\Lista as ILista;

class Lista implements ILista
{
    /**
     *  @var array Lista interna
     */
    private $lista = [];

    /**
     *  Crea una instancia de Lista
     *
     *  Lista simple e inutil. Ignorarlo (de momento).
     *
     *  @param array $lista Lista de elementos
     */
    public function __construct(array $lista)
    {
        $this->lista = $lista;
    }

    public function lista(): array
    {
        return $this->lista;
    }

}
