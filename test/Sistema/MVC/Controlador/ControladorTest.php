<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Controlador;

use Gof\Sistema\MVC\Controlador\Controlador;
use PHPUnit\Framework\TestCase;
use stdClass;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInexistente;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Controlador\Interfaz\Controlador as IControlador;
use Gof\Sistema\MVC\Controlador\Interfaz\Criterio;
use Gof\Sistema\MVC\Datos\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;


class ControladorTest extends TestCase
{
    private Controlador $controlador;
    private Autoload $autoload;
    private Procesos $procesos;
    private DAP $dap;

    public function setUp(): void
    {
        $this->dap = $this->createMock(DAP::class);
        $this->autoload = $this->createMock(Autoload::class);
        $this->procesos = $this->createMock(Procesos::class);
        $this->controlador = new Controlador($this->dap, $this->autoload, $this->procesos);
    }

    public function testImplementarEjecutable(): void
    {
        $this->assertInstanceOf(Ejecutable::class, $this->controlador);
    }

    /**
     * @dataProvider dataDapConDatosDelControlador
     */
    public function testEjecutarCreaUnaInstanciaTeniendoEnCuentaElDAP(DAP $dap): void
    {
        $this->dap = $dap;
        $nombreDeLaClase = $dap->edn . $dap->controlador;
        $instanciaDelControlador = $this->createMock(IControlador::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->with($nombreDeLaClase, ...$dap->argumentos)
            ->willReturn($instanciaDelControlador);
        $this->controlador->ejecutar();
        $this->assertSame($instanciaDelControlador, $this->controlador->instancia());
    }

    public function dataDapConDatosDelControlador(): array
    {
        $dap = new DAP();
        $dap->edn = 'Controlador\\';
        $dap->controlador = 'Index';
        $dap->argumentos = [PHP_INT_MAX, PHP_FLOAT_MIN, 'parametro3'];
        $dap->parametros = ['nombre_de_usuario', 'clave_bancaria', 'adn'];
        return [[$dap]];
    }

    public function testLanzarExcepcionAlEjecutarSiNoExisteElControlador(): void
    {
        $this->expectException(ControladorInexistente::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn(null);
        $this->controlador->ejecutar();
    }

    public function testLanzarExcepcionAlEjecutarSiLaInstanciaNoImplementaLaInterfazRequerida(): void
    {
        $this->expectException(ControladorInvalido::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn(new stdClass());
        $this->controlador->ejecutar();
    }

    public function testAlmacenarLaInstanciaAlEjecutarCorrectamente(): void
    {
        $instanciaDelControlador = $this->createMock(IControlador::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($instanciaDelControlador);
        $this->controlador->ejecutar();
        $this->assertSame($instanciaDelControlador, $this->controlador->instancia());
    }

    public function testPasarLosParametrosDelDapAlControladorInstanciado(): void
    {
        $instanciaDelControlador = $this->createMock(IControlador::class);
        $this->dap->parametros = ['parametro1', 'parametros2', 'parametros3'];
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($instanciaDelControlador);
        $instanciaDelControlador
            ->expects($this->once())
            ->method('parametros')
            ->with($this->dap->parametros);
        $this->controlador->ejecutar();
    }

    public function testEjecutarPasaLaInstanciaDelControladorAlCriterio(): void
    {
        $criterio = $this->createMock(Criterio::class);
        $instanciaDelControlador = $this->createMock(IControlador::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($instanciaDelControlador);
        $criterio
            ->expects($this->once())
            ->method('controlador')
            ->with($instanciaDelControlador);
        $this->controlador->criterio($criterio);
        $this->controlador->ejecutar();
    }

    public function testEjecutarAgregaElCriterioALaListadeProcesos(): void
    {
        $criterio = $this->createMock(Criterio::class);
        $instanciaDelControlador = $this->createMock(IControlador::class);
        $this->autoload
            ->expects($this->once())
            ->method('instanciar')
            ->willReturn($instanciaDelControlador);
        $this->procesos
            ->expects($this->once())
            ->method('agregar')
            ->with($criterio, Prioridad::Baja);
        $this->controlador->criterio($criterio);
        $this->controlador->ejecutar();
    }

    public function testMetodoEspacioDeNombreObtieneOModificaElRegistroEdnDelDap(): void
    {
        $ednAntes = 'namespace_antes';
        $ednDespues = 'namespace_despues';
        $this->dap->edn = $ednAntes;
        $this->assertSame($ednAntes, $this->controlador->espacioDeNombre());
        $this->assertSame($ednDespues, $this->controlador->espacioDeNombre($ednDespues));
        $this->assertNotSame($ednAntes, $this->controlador->espacioDeNombre());
        $this->assertSame($ednDespues, $this->controlador->espacioDeNombre());
    }

}
