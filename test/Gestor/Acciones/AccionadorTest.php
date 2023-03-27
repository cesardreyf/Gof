<?php

declare(strict_types=1);

namespace Test\Gestor\Acciones;

use Gof\Gestor\Acciones\Accionador;
use Gof\Gestor\Acciones\AccionadorSimple;
use Gof\Gestor\Acciones\Interfaz\Accion;
use Gof\Interfaz\Lista;
use PHPUnit\Framework\TestCase;
use stdClass;

class AccionadorTest extends TestCase
{
    private Accion $accionar;
    private Lista $listaDeDatos;
    private Accionador $accionador;

    public function setUp(): void
    {
        $this->accionar = $this->createMock(Accion::class);
        $this->listaDeDatos = $this->createMock(Lista::class);
        $this->accionador = new Accionador($this->listaDeDatos, $this->accionar);
    }

    public function testExtiendeDeAccionadorSimple(): void
    {
        $this->assertInstanceOf(AccionadorSimple::class, $this->accionador);
    }

    /**
     * @dataProvider dataVectorDeDatos
     */
    public function testAccionarRecorreLaListaDeDatos(array $vectorDeDatos): void
    {
        $cantidadDeElementos = count($vectorDeDatos);
        $this->listaDeDatos->method('lista')->willReturn($vectorDeDatos);
        $this->accionar->expects($this->exactly($cantidadDeElementos))->method('accionar');
        $this->assertTrue($this->accionador->accionar());
    }

    public function dataVectorDeDatos(): array
    {
        return [
            [[
                123,
                1.23,
                true,
                false,
                'a' => 'algo',
                'n' => 124567,
                'o' => new stdClass(),
            ]]
        ];
    }

    public function testDatosDevuelveUnaLista(): void
    {
        $this->assertInstanceOf(Lista::class, $this->accionador->datos());
        $this->assertSame($this->listaDeDatos, $this->accionador->datos());
    }

    public function testDefinirListaDeDatosPosInstanciacion(): void
    {
        $nuevaListaDeDatos = $this->createMock(Lista::class);
        $this->assertSame($nuevaListaDeDatos, $this->accionador->datos($nuevaListaDeDatos));
        $this->assertNotSame($this->listaDeDatos, $this->accionador->datos());
    }

    public function testDefinirListaDeDatosPosInstanciacionNoDebeFusionarLosDatos(): void
    {
        $datosViejos = [0, 1, 2, 3, 4];
        $datosNuevos = [5, 6, 7, 8, 9];

        $this->listaDeDatos->method('lista')->willReturn($datosViejos);
        $nuevaListaDeDatos = $this->createMock(Lista::class);
        $nuevaListaDeDatos->method('lista')->willReturn($datosNuevos);

        $this->assertSame($nuevaListaDeDatos, $this->accionador->datos($nuevaListaDeDatos));

        foreach( $datosViejos as $elementoDelViejoDato ) {
            $this->assertNotContains($elementoDelViejoDato, $this->accionador->datos()->lista());
        }
    }

}
