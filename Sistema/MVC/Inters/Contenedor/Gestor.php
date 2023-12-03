<?php

namespace Gof\Sistema\MVC\Inters\Contenedor;

use Gof\Interfaz\Id;

/**
 * Gestor de contenedores de inters
 *
 * Este módulo solo se encarga de gestionar el almacenamiento de los inters y
 * los consumidores.
 *
 * @package Gof\Sistma\MVC\Inters\Contenedor
 */
class Gestor
{

    /**
     * Almacena la lista de inters
     *
     * @var array<string, int>
     */
    private array $inters = [];

    /**
     * Almacena la lista de consumidores de inters
     *
     * @var array<int, int>
     */
    private array $consumidores = [];

    /**
     * Almacena los grupos
     *
     * @var array<string, int[]>
     */
    private array $grupos = [];

    /**
     * Puntero al último ID de los inters
     *
     * Apunta a la posición libre para un ID de un inter.
     *
     * @var int
     */
    private int $ptr = 0;

    /**
     * Obtiene un contenedor dedicado para un consumidor
     *
     * @param Id $consumidor Id del consumidor.
     *
     * @return Contenedor Instancia del contenedor.
     */
    public function segunId(Id $consumidor): Contenedor
    {
        return new Contenedor(
            $consumidor->id(),
            $this->inters,
            $this->consumidores,
            $this->ptr,
            $this->grupos,
        );
    }

}
