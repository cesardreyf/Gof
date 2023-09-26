<?php

namespace Gof\Gestor\Registro\Mensajes;

use Gof\Contrato\Registro\Mensajes\RegistroDeMensajes as IRegistroDeMensajes;
use Gof\Contrato\Registro\Mensajes\MensajeVinculante as IMensajeVinculante;
use Gof\Gestor\Registro\Mensajes\Excepcion\SubregistroExistente;

/**
 * Gestor de registro de mensajes
 *
 * @package Gof\Gestor\Registro\Mensajes
 */
class RegistroDeMensajes implements IRegistroDeMensajes
{

    /**
     * @var array Almacena los mensajes del registro
     */
    protected array $almacen = [];

    /**
     * @var array Almacena los subregistros hijos
     */
    protected array $subregistro = [];

    /**
     * Agrega un mensaje al registro
     *
     * @param string $mensaje Mensaje a agregar al registro
     */
    public function agregarMensaje(string $mensaje)
    {
        $this->almacen[] = $mensaje;
    }

    /**
     * Crea un nuevo subregistro asociado a una clave
     *
     * Crea una nueva instancia de la clase y le pasa un array donde se
     * almacenarán los mensajes. Al ser guardados los mensajes del subregistro
     * estos serán persistidos en el array de mensajes del registro padre y se
     * le asociarán el nombre del subregistro.
     *
     * @param string $nombre Nombre del subregistro
     *
     * @return IRegistroDeMensajes
     *
     * @throws SubregistroExistente si el nombre ya existe
     */
    public function crearSubregistro(string $nombre): IRegistroDeMensajes
    {
        if( isset($this->subregistro[$nombre]) ) {
            throw new SubregistroExistente($nombre);
        }

        return $this->subregistro[$nombre] = new static();
    }

    /**
     * Prepara un mensaje variable
     *
     * Prepara un mensaje al que se le pueden vincular mensajes a identificadores.
     *
     * @param string $mensaje
     *
     * @return IMensajeVinculante
     *
     * @see MensajeVinculante::vincular()
     */
    public function preparar(string $mensaje): IMensajeVinculante
    {
        return new MensajeVinculante($this->almacen, $mensaje);
    }

    /**
     * Verifica si no existen mensajes registrados
     *
     * @return bool Devuelve **true** si está vacio o **false** de lo contrario
     */
    public function vacio(): bool
    {
        if( !empty($this->almacen) ) {
            return false;
        }

        foreach( $this->subregistro as $subregistro ) {
            if( !$subregistro->vacio() ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Limpia la lista de mensajes
     *
     * Limpia la lista de mensajes y de subgestores registrados.
     */
    public function limpiar()
    {
        $this->almacen = [];
        $this->subregistro = [];
    }

    /**
     * Guarda los mensajes registrados
     *
     * Si existen subregistros creados los mensajes almacenados en estos
     * registros serán guardados como un array asociado al nombre del
     * subregistro en cuestión.
     *
     * @return bool Devuelve el estado de la operación
     */
    public function guardar(): bool
    {
        foreach( $this->subregistro as $nombreDelSubregistro => $subregistro ) {
            if( !$subregistro->vacio() ) {
                $this->almacen[$nombreDelSubregistro] = $subregistro->lista();
            }
        }
        return true;
    }

    /**
     * Obtiene la lista de mensajes registrados
     *
     * @return array
     */
    public function lista(): array
    {
        return $this->almacen;
    }

}
