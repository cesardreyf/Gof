<?php

namespace Gof\Datos\Bits\Mascara\Eventos;

use Gof\Datos\Bits\Mascara\MascaraDeBits as IMascaraDeBits;

class MascaraDeBits extends IMascaraDeBits
{
    /**
     *  @var Eventos Lista de eventos
     */
    private $eventos;

    public function __construct(int $bitsIniciales = 0)
    {
        $this->eventos = new Eventos();
        parent::__construct($bitsIniciales);
    }

    /**
     *  Obtiene el gestor de eventos
     *
     *  @return Eventos Devuelve el gestor de eventos
     */
    public function eventos(): Eventos
    {
        return $this->eventos;
    }

    /**
     *  Crea una condición para un evento
     *
     *  @return Eventos Devuelve una instancia del gestor de eventos
     */
    public function al(): Eventos
    {
        return $this->eventos;
    }

    /**
     *  Activa los bits indicados y ejecuta todos los eventos asociados
     *
     *  @param int $bits Bits a ser activados
     *
     *  @return int Devuelve es valor actual de la máscara de bits
     */
    public function activar(int $bits, int ...$otros): int
    {
        $this->llamar(Eventos::AL_ACTIVAR, $bits, ...$otros);
        return parent::activar($bits, ...$otros);
    }

    /**
     *  Desactiva los bits indicados y ejecuta todos los eventos asociados
     *
     *  @param int $bits Bits a ser desactivados
     *
     *  @return int Devuelve es valor actual de la máscara de bits
     */
    public function desactivar(int $bits, int ...$otros): int
    {
        $this->llamar(Eventos::AL_DESACTIVAR, $bits, ...$otros);
        return parent::desactivar($bits, ...$otros);
    }

    /**
     *  Define el valor de la máscara de bits y ejecuta todos los eventos asociados
     *
     *  @param int $valor Nuevo valor
     *
     *  @return int Devuelve es valor actual de la máscara de bits
     */
    public function definir(int $valor): int
    {
        $this->llamar(Eventos::AL_DEFINIR, $valor);
        return parent::definir($valor);
    }

    /**
     *  Obtiene el valor de la máscara de bits y ejecuta todos los eventos asociados
     *
     *  @return int Devuelve es valor actual de la máscara de bits
     */
    public function obtener(): int
    {
        $this->llamar(Eventos::AL_OBTENER, 0);
        return parent::obtener();
    }

    /**
     *  Llama a los eventos asociados a la condición si coindicen la máscara de bit
     *
     *  Llama a las funciones anónimas almacenadas en la lista de eventos siempre que se cumpla
     *  la condición y coincidan la máscara de bits. La función anónima puede recibir un entero
     *  con los bits.
     *
     *  @param int $indice Condición o índice de la lista de eventos
     *  @param int $bits   Máscara de bits
     */
    public function llamar(int $indice, int $bits, int ...$otros)
    {
        $condicion = $bits;
        foreach( $otros as $masBits ) {
            $condicion |= $masBits;
        }

        foreach( $this->eventos->traer($indice) as $registrado => $eventos ) {
            if( $condicion === $registrado ) {
                foreach( $eventos as $evento ) {
                    // Se llama a la función y
                    // se le pasan los bits
                    $evento($condicion);
                }
            }
        }
    }

}
