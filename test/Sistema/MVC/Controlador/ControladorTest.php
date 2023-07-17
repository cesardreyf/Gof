<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Controlador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\DAP\N1 as DAP;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Controlador\Controlador;
use Gof\Sistema\MVC\Controlador\Ejecutor;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use PHPUnit\Framework\TestCase;


class ControladorTest extends TestCase
{

    public function testEjecutarAgregaUnProcesoDePrioridadBaja(): void
    {
        $autoload = $this->createMock(Autoload::class);
        $procesos = $this->createMock(Procesos::class);
        $modulo   = new Controlador($autoload, $procesos);
        $dap      = new DAP();
        $procesos
            ->expects($this->once())
            ->method('agregar')
            ->with($this->isInstanceOf(Ejecutor::class), Prioridad::Baja);
        $modulo->ejecutar($dap);
    }

}
