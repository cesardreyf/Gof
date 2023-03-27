<?php

declare(strict_types=1);

namespace Test\Gestor\Acciones;

use Gof\Gestor\Acciones\AccionadorSimple;
use Gof\Gestor\Acciones\Interfaz\Accion;
use PHPUnit\Framework\TestCase;

class AccionadorSimpleTest extends TestCase
{
    private Accion $accionar;
    private AccionadorSimple $accionador;

    public function setUp(): void
    {
        $this->accionar = $this->getMockBuilder(Accion::class)->setMethods(['accionar'])->getMock();
        $this->accionador = new AccionadorSimple($this->accionar);
    }

    /**
     * @dataProvider dataElementosIdentificadoresYSusResultados
     */
    public function testAccionarSobreUnElemento(mixed $elemento, string $identificador, mixed $resultado): void
    {
        $this->accionar->expects($this->once())
                       ->method('accionar')
                       ->with($elemento, $identificador)
                       ->willReturn($resultado);

        $this->assertSame($resultado, $this->accionador->accionarEn($elemento, $identificador));
    }

    public function dataElementosIdentificadoresYSusResultados(): array
    {
        return [
            [123,     'entero', true],
            ['asado', 'comida', 420]
        ];
    }

}
