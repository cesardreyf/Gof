<?php

namespace Gof\Gestor\Registro\Simple;

use Gof\Contrato\Registro\Registro;
use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Interfaz\Mensajes\Guardable;

/**
 * Gestor de registros de mensajes
 *
 * Clase que gestiona el registro de mensajes y delega la persistencia en otro gestor de guardado.
 *
 * @package Gof\Gestor\Registro\Simple
 */
class RegistroSimple implements Registro
{
    /**
     * @var int Flag que indica limpiar el pila de mensajes cada vez que se llame a la función volcar
     */
    const LIMPIAR_PILA_AL_VOLCAR = 1;

    /**
     * @var int Flag que indica unir todos los mensajes del pila en uno solo y guardarlo
     */
    const UNIR_MENSAJES_AL_VOLCAR = 2;

    /**
     * @var int Flag que indica unir todos los nuevos mensajes con el último
     */
    const UNIR_MENSAJES_AL_REGISTRAR = 4;

    /**
     * @var int Flag que indica que por cada mensaje que se registre llame implícitamente a la función volcar
     */
    const VOLCAR_PILA_AL_REGISTRAR = 8;

    /**
     * @var int Flag que indica que si el gestor de guardado devuelve un error interrumpa el resto de guardados
     */
    const INTERRUMPIR_VOLCADO_SI_HAY_ERRORES = 16;

    /**
     * @var int Máscara de bits con la configuración por defecto
     */
    const CONFIGURACION_POR_DEFECTO = self::LIMPIAR_PILA_AL_VOLCAR;

    /**
     * @var array<int, string> Array de mensajes a ser registrados
     */
    private array $pila;

    /**
     * @var Guardable Gestor encargado de guardar los registros
     */
    private Guardable $gestor;

    /**
     * @var string Separador utilizado para la unión de mensajes
     */
    private string $separador;

    /**
     * @var MascaraDeBits Máscara de bits con la configuración interna del gestor
     */
    private MascaraDeBits $configuracion;

    /**
     * Constructor
     *
     * @param Guardable $gestor        Gestor encargado del guardado de los registros
     * @param int       $configuracion Máscara de bit con la configuración interna del gestor
     */
    public function __construct(Guardable $gestor, int $configuracion = self::CONFIGURACION_POR_DEFECTO)
    {
        $this->pila = [];
        $this->separador = '';
        $this->gestor = $gestor;
        $this->configuracion = new MascaraDeBits($configuracion);
    }

    /**
     * Almacena un mensaje en el pila de mensajes
     *
     * Si está activado la flag UNIR_MENSAJES_AL_REGISTRAR el mensaje se concatena con el
     * último mensaje en la pila de mensajes.
     *
     * Si esta activado la flag VOLCAR_PILA_AL_REGISTRAR la función *volcar* será llamada
     * cada vez que se registre un mensaje. Esta función retornará el valor bool devuelto
     * por la función volcar.
     *
     * @param string $mensaje Mensaje a ser guardado
     *
     * @return bool Devuelve TRUE en caso de éxito o FALSE de lo contrario
     */
    public function registrar(string $mensaje): bool
    {
        if( $this->configuracion()->activados(self::UNIR_MENSAJES_AL_REGISTRAR) ) {
            $indice = count($this->pila) - 1;

            if( $indice < 0 ) {
                $this->pila[++$indice] = '';
            }

            $this->pila[$indice] .= $mensaje;
        } else {
            $this->pila[] = $mensaje;
        }

        if( $this->configuracion()->activados(self::VOLCAR_PILA_AL_REGISTRAR) ) {
            return $this->volcar();
        }

        return true;
    }

    /**
     * Vuelca todos los mensajes de la pila al gestor de guardado
     *
     * Pasa al gestor de guardado todos y cada uno de los mensajes alojados en la pila,
     * uno a la vez. Si cualquiera de estas llamadas falla esta función devolverá FALSE.
     *
     * Si la flag UNIR_MENSAJES_AL_VOLCAR está activo se llamará una sola vez al método
     * guardar del gestor de guardado. A este se le pasará una sola cadena de caracteres
     * con todos los mensajes unidos y separados por un separador.
     *
     * Si la flag LIMPIAR_PILA_AL_VOLCAR está activo la pila de mensajes se limpiará
     * una vez se hayan guardado todos los mensajes.
     *
     * @return bool Devuelve el estado del volcado de los mensajes
     */
    public function volcar(): bool
    {
        $resultado = true;

        if( $this->configuracion()->activados(self::UNIR_MENSAJES_AL_VOLCAR) ) {
            $union = implode($this->separador, $this->pila);
            $resultado = $this->gestor->guardar($union);
        } else {
            foreach( $this->pila as $mensaje ) {
                $guardado = $this->gestor->guardar($mensaje);

                // Si ocurre un solo error el resultado será siempre false
                if( $guardado === false ) {
                    $resultado = false;

                    if( $this->configuracion()->activados(self::INTERRUMPIR_VOLCADO_SI_HAY_ERRORES) ) {
                        break;
                    }
                }
            }
        }

        if( $this->configuracion()->activados(self::LIMPIAR_PILA_AL_VOLCAR) ) {
            $this->pila = [];
        }

        return $resultado;
    }

    /**
     * Obtiene o define el separador que será usado al unir mensajes
     *
     * Si no se recibe ninguna cadena como argumento devuelve el separador actual, de lo
     * contrario se define el separador y la función devuelve el mismo como valor de retorno.
     *
     * @param ?string $separador Cadena de caracteres a usarse como separador de mensajes
     *
     * @return string Devuelve el separador actual
     */
    public function separador(?string $separador = null): string
    {
        return $separador === null ? $this->separador : $this->separador = $separador;
    }

    /**
     * Obtiene la configuracion interna del gestor
     *
     * @return MascaraDeBits Devuelve una MascaraDeBits con la configuración interna
     */
    public function configuracion(): MascaraDeBits
    {
        return $this->configuracion;
    }

    /**
     * Obtiene la pila de mensajes
     *
     * @return array<int, string> Array con todos los mensajes registrados
     */
    public function pila(): array
    {
        return $this->pila;
    }

}
