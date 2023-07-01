<?php

namespace Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Interfaz;

use Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Datos\Registros;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Controlador as IControlador;

/**
 * Interfaz que deben implementar los controladores para ser ejecutados por el criterio Ipiperf
 *
 * @package Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Interfaz
 */
interface Controlador extends IControlador
{
    /**
     * Registros
     *
     * Registros importantes que determinan el funcionamiento del controlador.
     *
     * @return Registro
     */
    public function registros(): Registros;

    /**
     * Inicializa el controlador
     *
     * Primer método llamado por el criterio.
     */
    public function iniciar();

    /**
     * Función que se ejecuta antes del indice
     */
    public function preindice();

    /**
     * Método principal de cada controlador
     *
     * Es llamado por la aplicación solo si el registro Error es **false**.
     * Caso contrario solo será llamado después del método **error** del
     * controlador si el registro Continuar es **true**.
     */
    public function indice();

    /**
     * Función que se ejecuta después del índice
     *
     * Es llamado después del método **indice**, si es que se ejecutó, y si el
     * registro Error es igual a **false**. De lo contrario será ejecutado por
     * la aplicación después de llamar al método **error** solo si el registro
     * Continuar es igual a **true**.
     */
    public function posindice();

    /**
     * Método que se llama cada vez que ocurra un error
     */
    public function error();

    /**
     * Renderiza la plantilla para la vista
     */
    public function renderizar();

    /**
     * Finaliza el controlador
     */
    public function finalizar();
}
