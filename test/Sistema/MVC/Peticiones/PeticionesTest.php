<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Peticiones;

use Gof\Contrato\Peticiones\Peticiones as GestorDePeticiones;
use Gof\Sistema\MVC\Aplicacion\DAP\N1;
use Gof\Sistema\MVC\Aplicacion\DAP\Solicitud\Metodo;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Peticiones\Configuracion;
use Gof\Sistema\MVC\Peticiones\Peticiones;
use PHPUnit\Framework\TestCase;

class PeticionesTest extends TestCase
{

    public function testImplementarEjecutable()
    {
        $modulo = new Peticiones();
        $this->assertInstanceOf(Ejecutable::class, $modulo);
    }

    public function testFuncionamientoEsperado(): void
    {
        $metodo = 'GET';
        $clave = '_clave';
        $cadenaDeLaPeticion = 'recurso1\recurso2';
        $listaDeRecursos = ['recurso1', 'recurso2'];
        $_SERVER['REQUEST_METHOD'] = $metodo;
        $_GET = [$clave => $cadenaDeLaPeticion];

        $peticiones = new Peticiones();
        $peticiones->configuracion = $this->createMock(Configuracion::class);
        $peticiones->configuracion->gestor = $this->createMock(GestorDePeticiones::class);
        $peticiones->configuracion->url = $clave;

        $peticiones->configuracion->gestor
            ->expects($this->once())
            ->method('procesar')
            ->with($cadenaDeLaPeticion)
            ->willReturn(true);
        $peticiones->configuracion->gestor
            ->expects($this->once())
            ->method('lista')
            ->willReturn($listaDeRecursos);

        $dap = new N1();
        $this->assertEmpty($dap->solicitud->cadena);
        $this->assertEmpty($dap->solicitud->lista);
        $peticiones->ejecutar($dap);

        $this->assertSame(Metodo::from($metodo), $dap->solicitud->metodo);
        $this->assertSame($cadenaDeLaPeticion, $dap->solicitud->cadena);
        $this->assertSame($listaDeRecursos, $dap->solicitud->lista);
    }

}
