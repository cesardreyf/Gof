<?php

namespace Gof\Gestor\Configuracion;

use Gof\Contrato\Configuracion\Configuracion as IConfiguracion;

class Configuracion implements IConfiguracion
{
    /**
     *  @var int Máscara de bits con la configuración
     */
    private $marcas;

    public function __construct(int $estadoInicial = 0)
    {
        $this->marcas = $estadoInicial;
    }

    public function activar(int $bits, int ...$otros): int
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return $this->marcas |= $bits;
    }

    public function desactivar(int $bits, int ...$otros): int
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return $this->marcas &= ~$bits;
    }

    public function activadas(int $bits, int ...$otros): bool
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return ($this->marcas & $bits) === $bits;
    }

    public function desactivadas(int $bits, int ...$otros): bool
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return ($this->marcas & $bits) === 0;
    }

    public function definir(int $valor): int
    {
        return $this->marcas = $valor;
    }

    public function obtener(): int
    {
        return $this->marcas;
    }

}
