<?php

namespace Gof\Gestor\Registro\Mensajes;

use Gof\Contrato\Registro\Mensajes\MensajeVinculante as IMensajeVinculante;

/**
 * Clase que gestiona un mensaje al que se le pueden vincular datos
 *
 * @package Gof\Gestor\Registro\Mensajes
 */
class MensajeVinculante implements IMensajeVinculante
{
    /**
     * @var ?string
     */
    private ?string $cache = null;

    /**
     * @var array
     */
    private array $vinculos = [];

    /**
     * Constructor
     *
     * @param array &$almacen Referencia al array donde se almacenará el mensaje
     * @param string $mensaje Mensaje con el que se trabajará
     */
    public function __construct(private array &$almacen, private readonly string $mensaje)
    {
    }

    /**
     * Vincula un identificador a un mensaje
     *
     * Al encontrarse un identificador en el mensaje original este será
     * reemplazado por el mensaje pasado aquí. Por ejemplo: si el mensaje
     * original es 'Hola \1' y se vincula el identificador numérico 1 al
     * mensaje 'mundo' al guardar el mensaje este se guardará como 'Hola
     * mundo'.
     *
     * Si en el mensaje existe un identificador (\n) pero no se vinculó ningún
     * mensaje al identificador este pasará como tal al mensaje final. De igual
     * modo pasará si se utiliza la doble barra invertida '\\n'.
     *
     * @param int    $identificador Identificador numérico
     * @param string $mensaje       Mensaje con el cual será reemplazado
     */
    public function vincular(int $identificador, string $mensaje)
    {
        if( !isset($this->vinculos[$identificador]) ) {
            $this->vinculos[$identificador] = $mensaje;
        }
    }

    /**
     * Guarda el mensaje en el registro de mensajes que creó esta instancia
     *
     * @return bool Devuelve el estado de la operación
     */
    public function guardar(): bool
    {
        if( is_null($this->cache) ) {
            $this->almacen[] = '';
            $this->cache =& $this->almacen[count($this->almacen) - 1];
        }

        $this->cache = preg_replace_callback('/(?<!\\\\)(\\\\)?\\\\(\d+)/', function($coincidencia) {
            if( !empty($coincidencia[1]) ) {
                return "\\{$coincidencia[2]}";
            }
            $indice = intval($coincidencia[2]);
            return $this->vinculos[$indice] ?? $coincidencia[0];
        }, $this->obtenerMensajeOriginal());
        return true;
    }

    /**
     * Obtiene el mensaje original
     *
     * @return string
     */
    public function obtenerMensajeOriginal(): string
    {
        return $this->mensaje;
    }

    /**
     * Obtiene el mensaje ya convertido
     *
     * @return string
     */
    public function obtenerMensajeConvertido(): string
    {
        return $this->cache ?? '';
    }

}
