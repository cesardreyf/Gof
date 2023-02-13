<?php

namespace Gof\Gestor\Configuracion;

use Gof\Contrato\Configuracion\Configuracion as IConfiguracion;

class Configuracion implements IConfiguracion
{
    /**
     *  @var int Máscara de bits con la configuración
     */
    private $marcas;

    /**
     *  Crea una instancia de Configuracion
     *
     *  @param int $estadoInicial Valor que tendrá la máscara de bits interna
     */
    public function __construct(int $estadoInicial = 0)
    {
        $this->marcas = $estadoInicial;
    }

    /**
     *  Activa los bits pasados por el argumento
     *
     *  @param int $bits Bits a activarse en la máscara de bits interna
     *
     *  @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function activar(int $bits, int ...$otros): int
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return $this->marcas |= $bits;
    }

    /**
     *  Desactiva los bits pasados por el argumento
     *
     *  @param int $bits Bits a desactivarse en la máscara de bits interna
     *
     *  @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function desactivar(int $bits, int ...$otros): int
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return $this->marcas &= ~$bits;
    }

    /**
     *  Valida si los bits pasados por argumento están activos
     *
     *  @param int $bits Bit o bits a validar si están activos
     *
     *  @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function activados(int $bits, int ...$otros): bool
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return ($this->marcas & $bits) === $bits;
    }

    /**
     *  Valida si los bits pasados por argumento están desactivados
     *
     *  @param int $bits Bit o bits a validar si están desactivados
     *
     *  @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function desactivados(int $bits, int ...$otros): bool
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return ($this->marcas & $bits) === 0;
    }

    /**
     *  Define el valor de la máscara de bits
     *
     *  @param int $valor Nuevo valor que tendrá la máscara de bits
     *
     *  @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function definir(int $valor): int
    {
        return $this->marcas = $valor;
    }

    /**
     *  Obitene el valor de la máscara de bits
     *
     *  @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function obtener(): int
    {
        return $this->marcas;
    }

}
