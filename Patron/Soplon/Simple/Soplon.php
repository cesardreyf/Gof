<?php

namespace Gof\Patron\Soplon\Simple;

use Gof\Patron\Soplon\Simple\Interfaz\Agente;

/**
 * Patrón soplón simple
 *
 * Patrón de diseño soplón, comunmente llamado observador.
 *
 * Este clase proporciona las acciones de un soplón que avisará a todos los
 * agentes registrados en una lista cuando se llame a la función **avisar**.
 *
 * Esta clase es simple. Lo único que hará es recorrer una lista de agentes y
 * llamar al método avisar, sin ningún tipo de información extra.
 *
 * @package Gof\Patron\Soplon\Simple
 */
class Soplon
{
    /**
     * Lista de agentes
     *
     * @var Agentes
     */
    private Agentes $agentes;

    /**
     * Constructor
     *
     * @param ?Agentes $agentes Instancia de un gestor de agentes (Opcional).
     */
    public function __construct(?Agentes $agentes = null)
    {
        $this->agentes = $agentes ?? new Agentes();
    }

    /**
     * Avisa a todos los agentes
     *
     * Llama al método avisar() de todos los agentes registrados.
     */
    public function avisar()
    {
        $lista = $this->agentes->lista();
        array_walk($lista, function(Agente $agente) {
            $agente->avisar();
        });
    }

    /**
     * Obtiene el módulo que gestiona la lista de agentes
     *
     * @return Agentes
     */
    public function agentes(): Agentes
    {
        return $this->agentes;
    }

}
