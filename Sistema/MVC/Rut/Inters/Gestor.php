<?php

namespace Gof\Sistema\MVC\Rut\Inters;

use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;

/**
 * Gestor de inters para rutas del enrutador Rut
 *
 * @package Gof\Sistma\MVC\Rut\Inters
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
    private array $rutas = [];

    /**
     * Puntero al último ID de los inters
     *
     * Apunta a la posición libre para un ID de un inter.
     *
     * @var int
     */
    private int $ptr = 0;

    /**
     * Obtiene un subgestor dedicada para una sola ruta
     *
     * @param int $idDeLaRuta Id de la ruta que hará uso del gestor de inters.
     *
     * @return Subgestor Instancia del subgestor.
     */
    public function segunId(int $idDeLaRuta): Subgestor
    {
        return new Subgestor(
            $idDeLaRuta,
            $this->inters,
            $this->rutas,
            $this->ptr
        );
    }

}
