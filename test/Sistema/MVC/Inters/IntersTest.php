<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Inters;

use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Inters\Inters;
use PHPUnit\Framework\TestCase;

class IntersTest extends TestCase
{

    public function testMetodoAgregarAgregaUnProcesoDePrioridadMedia(): void
    {
        $procesos = $this->createMock(Procesos::class);
        $inters = new Inters($procesos);
        $instanciaDelInter = $this->createMock(Ejecutable::class);
        $procesos
            ->expects($this->once())
            ->method('agregar')
            ->with($instanciaDelInter, Prioridad::Media);
        $inters->agregar($instanciaDelInter);
    }

}
