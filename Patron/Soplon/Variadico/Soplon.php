<?php

namespace Gof\Patron\Soplon\Variadico;

/**
 * Patrón Soplón Variádico
 *
 * A diferencia del patrón soplón simple este patrón proporciona una
 * característica que permite pasar datos a los agentes.
 *
 * Al momento de avisar a los agentes es posible pasar un número variable de
 * argumentos a la función avisar(). Estos datos serán pasados a los agentes de
 * igual manera.
 *
 * @package Gof\Patron\Soplon\Variadico
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
     * Llama al método avisar() de todos los agentes registrados y les pasa por
     * parámetro todos los argumentos recibidos en esta función.
     *
     * @param mixed ...$informe Datos a pasar a los agentes.
     */
    public function avisar(mixed ...$informe)
    {
        $lista = $this->agentes->lista();
        array_walk($lista, function(Agente $agente) use ($informe) {
            $agente->avisar(...$informe);
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
