<?php

declare(strict_types=1);

namespace Test\Sistema\Reportes\Gestor;

use Gof\Contrato\Registro\Registro;
use Gof\Sistema\Reportes\Gestor\Reporte;
use Gof\Sistema\Reportes\Interfaz\Plantilla;
use PHPUnit\Framework\TestCase;

class ReporteTest extends TestCase
{
    protected $plantilla;
    protected $reportero;
    protected $registro;

    public function setUp(): void
    {
        $this->registro = $this->getMockBuilder(Registro::class)->getMock();
        $this->plantilla = $this->getMockBuilder(Plantilla::class)->getMock();
        $this->reportero = new Reporte($this->registro, $this->plantilla);
    }

    /**
     *  @dataProvider dataArrayConLetras
     *  @dataProvider dataArrayConDigitos
     */
    public function testLlamadasAlReportarExitosamente(array $datos): void
    {
        $datosTraducidos = implode(', ', $datos);

        $this->plantilla->expects($this->once())
                        ->method('traducir')
                        ->with($datos)
                        ->willReturn(true);

        $this->plantilla->expects($this->once())
                        ->method('mensaje')
                        ->willReturn($datosTraducidos);

        $this->registro->expects($this->once())
                       ->method('registrar')
                       ->with($datosTraducidos);

        $this->registro->expects($this->once())
                       ->method('volcar')
                       ->willReturn(true);

        $this->assertTrue($this->reportero->reportar($datos));
    }

    public function testConfigurarImpresionDeMensajes(): void
    {
        $this->assertFalse($this->reportero->imprimir());

        $this->reportero->imprimir(true);
        $this->assertTrue($this->reportero->imprimir());

        $this->reportero->imprimir(false);
        $this->assertFalse($this->reportero->imprimir());

        $this->assertFalse($this->reportero->imprimir(false));
        $this->assertTrue($this->reportero->imprimir(true));
    }

    /**
     *  @dataProvider dataArrayConLetras
     *  @dataProvider dataArrayConDigitos
     *  @depends testLlamadasAlReportarExitosamente
     */
    public function testErrorAlTraducir(array $datos): void
    {
        $this->plantilla->expects($this->once())
                        ->method('traducir')
                        ->willReturn(false);

        $this->plantilla->expects($this->never())
                        ->method('mensaje');

        $this->registro->expects($this->never())
                        ->method('registrar');

        $this->registro->expects($this->never())
                        ->method('volcar');

        $this->assertFalse($this->reportero->reportar($datos));
    }

    /**
     *  @dataProvider dataArrayConLetras
     *  @dataProvider dataArrayConDigitos
     *  @depends testLlamadasAlReportarExitosamente
     */
    public function testErrorAlVolcarElRegistro(array $datos): void
    {
        $this->plantilla->expects($this->once())
                        ->method('traducir')
                        ->with($datos)
                        ->willReturn(true);

        $this->registro->expects($this->once())
                        ->method('volcar')
                        ->willReturn(false);

        $this->assertFalse($this->reportero->reportar($datos));
    }

    /**
     *  @dataProvider dataArrayConLetras
     *  @dataProvider dataArrayConDigitos
     *  @depends testConfigurarImpresionDeMensajes
     *  @depends testLlamadasAlReportarExitosamente
     */
    public function testImpresionDelMensaje(array $datos): void
    {
        $datosTraducidos = implode(', ', $datos);

        $this->plantilla->expects($this->once())
                        ->method('traducir')
                        ->willReturn(true);

        $this->plantilla->expects($this->once())
                        ->method('mensaje')
                        ->willReturn($datosTraducidos);

        $this->registro->expects($this->once())
                       ->method('volcar')
                       ->willReturn(true);

        $this->reportero->imprimir(true);

        $this->assertTrue(ob_start());
        $this->assertTrue($this->reportero->reportar($datos));
        $this->assertNotEmpty(ob_get_clean());
    }

    public function testMetodoPlantillaDevuelveLaMismaInstanciaPasadaPorElConstructor(): void
    {
        $this->assertSame($this->plantilla, $this->reportero->plantilla());
    }

    public function testMetodoRegistroDevuelveLaMismaInstanciaPasadaPorElConstructor(): void
    {
        $this->assertSame($this->registro, $this->reportero->registro());
    }

    public function testMetodoPlantillaRetornaUnaInstanciaDePlantilla(): void
    {
        $this->assertInstanceOf(Plantilla::class, $this->reportero->plantilla());
    }

    public function testMetodoRegistroRetornaUnaInstanciaDeRegistro(): void
    {
        $this->assertInstanceOf(Registro::class, $this->reportero->registro());
    }

    public function dataArrayConDigitos(): array
    {
        return [
            [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9]]
        ];
    }

    public function dataArrayConLetras(): array
    {
        return [
            [['a', 'b', 'c', 'd', 'e', 'f']]
        ];
    }

}
