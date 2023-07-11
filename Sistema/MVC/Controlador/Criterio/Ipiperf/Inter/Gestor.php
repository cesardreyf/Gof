<?php

namespace Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter;

use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Datos\Registros;

/**
 * Gestiona los inters
 *
 * Almacena y gestiona los inters de los controladores del
 * criterio Ipiperf.
 *
 * @package Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter
 */
class Gestor implements Lista
{
    /**
     * Límite máximo de ciclos para un inter
     *
     * Si un inter altera el registro MIENTRAS, este valor determina el número
     * máximo de ciclos que puede tener antes de que el gestor finalice el
     * ciclo de vida del inter.
     *
     * @var int
     *
     * @see DAP::$mientras
     */
    public const MIENTRAS_LIMITE = 100;

    /**
     * Lista de inters
     *
     * @var array
     */
    private array $lista = [];

    /**
     * Datos de acceso público para todos los inters
     *
     * @var DAP
     */
    private DAP $dap;

    /**
     * Constructor
     */
    public function __construct(Registros $registros)
    {
        $this->dap = new DAP($registros);
    }

    /**
     * Ejecuta todos los inters registrados
     *
     * Recorre la lista interna de inters y llama al método ejecutar pasándole
     * el DAP como argumento.
     *
     * Si el registro ROMPER del DAP está activo el bucle se rompe y no se
     * continúa la ejecución de los demás inters.
     *
     * Si el registro SALTAR del DAP es mayor a cero se ignorará el inter y
     * continuará con el siguiente hasta que el valor del registro sea
     * nuevamente cero.
     *
     * El inter será ejecutado una sola vez o hasta que el registro MIENTRAS
     * del DAP sea igual a **false**. En este último caso es responsabilidad
     * del Inter colocar el estado a **false**, de lo contrario se creará un
     * bucle infinito.
     *
     * El registro MIENTRAS tiene un límite máximo de ciclos posibles. Superado
     * el límite se detendrá la ejecución del inter y continuará.
     */
    public function ejecutar()
    {
        foreach( $this->lista as $inter ) {
            if( $this->dap->romper ) {
                break;
            }

            if( $this->dap->saltar > 0 ) {
                $this->dap->saltar -= 1;
                continue;
            }

            // Por si las moscas...
            $seguroDeVida = self::MIENTRAS_LIMITE;

            do {
                $inter->ejecutar($this->dap);
            } while( $this->dap->mientras && --$seguroDeVida );
        }
    }

    /**
     * Agrega un inter
     *
     * @param Ejecutable $inter Instancia del inter
     */
    public function agregar(Ejecutable $inter)
    {
        $this->lista[] = $inter;
    }

    /**
     * Agrega una lista de inters
     *
     * @param Lista $inters
     */
    public function agregarLista(Lista $inters)
    {
        $listaDeInters = $inters->lista();
        array_walk($listaDeInters, function(Ejecutable $inter) {
            $this->agregar($inter);
        });
    }

    /**
     * Obtiene la lista de inters
     *
     * @return array
     */
    public function lista(): array
    {
        return $this->lista;
    }

}
