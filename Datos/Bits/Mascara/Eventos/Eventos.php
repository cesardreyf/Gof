<?php

namespace Gof\Datos\Bits\Mascara\Eventos;

class Eventos
{
    /**
     *  @var int Condicion que se cumple al llamarse al método MascaraDeBits::activar()
     */
    const AL_ACTIVAR = 0;

    /**
     *  @var int Condicion que se cumple al llamarse al método MascaraDeBits::desactivar()
     */
    const AL_DESACTIVAR = 1;

    /**
     *  @var int Condicion que se cumple al llamarse al método MascaraDeBits::definir()
     */
    const AL_DEFINIR = 2;

    /**
     *  @var int Condicion que se cumple al llamarse al método MascaraDeBits::obtener()
     */
    const AL_OBTENER = 3;

    /**
     *  @var array Lista de eventos
     */
    private $eventos = [];

    /**
     *  Devuelve una Acción a cumplirse cuando se llame al método MascaraDeBits::activar()
     *
     *  @param int $bits Máscara de bits que activarán el evento
     *
     *  @see MascaraDeBits::activar()
     *
     *  @return Accion Devuelve una Accion
     */
    public function activar(int $bits, int ...$otros): Accion
    {
        return $this->accion(self::AL_ACTIVAR, $bits, ...$otros);
    }

    /**
     *  Devuelve una Acción a cumplirse cuando se llame al método MascaraDeBits::desactivar()
     *
     *  @param int $bits Máscara de bits que activarán el evento
     *
     *  @return Accion Devuelve una Accion
     *
     *  @see MascaraDeBits::desactivar()
     */
    public function desactivar(int $bits, int ...$otros): Accion
    {
        return $this->accion(self::AL_DESACTIVAR, $bits, ...$otros);
    }

    /**
     *  Devuelve una Acción a cumplirse cuando se llame al método MascaraDeBits::definir()
     *
     *  @param int $valor Valor que activará el evento
     *
     *  @return Accion Devuelve una Accion
     *
     *  @see MascaraDeBits::definir()
     */
    public function definir(int $valor): Accion
    {
        return $this->accion(self::AL_DEFINIR, $valor);
    }

    /**
     *  Devuelve una Acción a cumplirse cuando se llame al método MascaraDeBits::obtener()
     *
     *  @return Accion Devuelve una Accion
     *
     *  @see MascaraDeBits::obtener()
     */
    public function obtener(): Accion
    {
        return $this->accion(self::AL_OBTENER, 0);
    }

    /**
     *  Obtiene la lista de eventos
     *
     *  @return array Devuelve un array con todos los eventos
     */
    public function lista(): array
    {
        return $this->eventos;
    }

    /**
     *  Obtiene una lista de eventos que cumplan con la condición
     *
     *  @param int $condicion Condición, o índice, de la lista de eventos
     *
     *  @return array Devuelve una lista de eventos que cumplen con la condición
     */
    public function traer(int $condicion): array
    {
        if( empty($this->eventos[$condicion]) ) {
            return [];
        }

        return $this->eventos[$condicion];
    }

    /**
     *  Crea una instancia de Accion para un evento
     *
     *  @param int $indice Condición del evento
     *  @param int $bits   Máscara de bits que desencadenará el evento
     *
     *  @return Accion Devuelve una instancia de Accion para el evento
     *
     *  @access private
     */
    private function accion(int $indice, int $bits, int ...$otros): Accion
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return new Accion($indice, $bits, $this->eventos);
    }

}
