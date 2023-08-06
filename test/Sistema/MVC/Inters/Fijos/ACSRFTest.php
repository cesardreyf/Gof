<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Inters\Fijos;

use Closure;
use Gof\Contrato\Cookies\Cookies;
use Gof\Contrato\Session\Session;
use Gof\Gestor\Dependencias\Simple\DependenciasMagicas;
use Gof\Sistema\ACSRF\ACSRF as SistemaASCRF;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Aplicacion\DAP\N2;
use Gof\Sistema\MVC\Inters\Fijos\ACSRF;
use PHPUnit\Framework\TestCase;

class ACSRFTest extends TestCase
{
    private ACSRF $acsrf;
    private N2 $dap;

    public function setUp(): void
    {
        $this->acsrf = new ACSRF();
        $this->dap = $this->createMock(N2::class);
    }

    public function testDapN2ExtiendeDelGestorDeDependenciasMagicas(): void
    {
        $this->assertInstanceOf(DependenciasMagicas::class, $this->dap);
    }

    public function testAgregarElSistemaAntiCSRFEnElGestorDeDependenciasAlEjecutar(): void
    {
        $gestorDeSession = $this->createMock(Session::class);
        $gestorDeCookies = $this->createMock(Cookies::class);
        $this->dap
            ->expects($this->exactly(2))
            ->method('obtener')
            ->willReturnMap([
                [Session::class, $gestorDeSession],
                [Cookies::class, $gestorDeCookies],
            ]);
        $this->dap
            ->expects($this->once())
            ->method('agregar')
            ->with(
                $this->equalTo(SistemaASCRF::class),
                $this->isInstanceOf(Closure::class)
            )
            ->willReturnCallback(function(string $clase, Closure $funcion) {
                $this->assertInstanceOf(SistemaASCRF::class, $funcion());
                return true;
            });
        $this->acsrf->ejecutar($this->dap);
    }

    public function testAsociarMetodoAlGestor(): void
    {
        $this->dap
            ->expects($this->once())
            ->method('asociarMetodo')
            ->with(ACSRF::METODO, SistemaASCRF::class)
            ->willReturn(true);
        $this->acsrf->ejecutar($this->dap);
    }

    // public function testLanzarExcepcionSiNoSeAgregaLaDependencia(): void
    // {
    //     $this->dap
    //         ->expects($this->once())
    //         ->method('agregar')
    //         ->willReturn(false);
    //     $this->acsrf->ejecutar($this->dap
    // }

}
