<?php

declare(strict_types=1);

namespace Test\Sistema\MVC;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\Aplicacion;
use Gof\Sistema\MVC\Controlador\Controlador;
use Gof\Sistema\MVC\Peticiones\Peticiones;
use Gof\Sistema\MVC\Registros\Registros;
use Gof\Sistema\MVC\Rutas\Rutas;
use Gof\Sistema\MVC\Sistema;
use PHPUnit\Framework\TestCase;

class SistemaTest extends TestCase
{
    private Sistema $sistema;

    public function setUp(): void
    {
        $this->sistema = new Sistema();
    }

    public function testMetodoRegistrosDevuelveUnaInstanciaDelGestorDeRegistros(): void
    {
        $this->assertInstanceOf(Registros::class, $this->sistema->registros());
    }

    public function testMetodoAutoloadDevuelveUnaInstanciaDelGestorDeAutoload(): void
    {
        $this->assertInstanceOf(Autoload::class, $this->sistema->autoload());
    }

    public function testMetodoRutasDevuelveUnaInstanciaDelGestorDeRutas(): void
    {
        $this->assertInstanceOf(Rutas::class, $this->sistema->rutas());
    }

    public function testMetodoAplicacionDevuelveUnaInstanciaDelGestorDeAplicacion(): void
    {
        $this->assertInstanceOf(Aplicacion::class, $this->sistema->aplicacion());
    }

    public function testMetodoControladorDevuelveUnaInstanciaDelGestorDeControlador(): void
    {
        $this->assertInstanceOf(Controlador::class, $this->sistema->controlador());
    }

    public function testMetodoPeticionesDevuelveUnaInstanciaDelGestorDeControlador(): void
    {
        $this->assertInstanceOf(Peticiones::class, $this->sistema->peticiones());
    }

}
