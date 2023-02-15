<?php

declare(strict_types=1);

use Gof\Datos\Bits\Mascara\Eventos\MascaraDeBits as MascaraDeBitsConEventos;
use Gof\Datos\Bits\Mascara\Eventos\Eventos;
use Gof\DAtos\Bits\Mascara\MascaraDeBits;
use PHPUnit\Framework\TestCase;

class MascaraDeBitsTest extends TestCase
{
    private $mascara;
    private $funcion;

    public function setUp(): void
    {
        $this->mascara = new MascaraDeBitsConEventos();
        $this->funcion = $this->getMockBuilder(stdClass::class)
                              ->setMethods(['__invoke'])
                              ->getMock();
    }

    public function testExtiendeDeMascaraDeBits(): void
    {
        $this->assertInstanceOf(MascaraDeBits::class, $this->mascara);
    }

    public function testMetodoAlDevuelveUnaInstanceDeEventos(): void
    {
        $this->assertInstanceOf(Eventos::class, $this->mascara->al());
    }

    public function testAliasDeAl(): void
    {
        $this->assertInstanceOf(Eventos::class, $this->mascara->eventos());
    }

    /**
     *  @dataProvider dataMascaraDeBitsCualquiera
     */
    public function testCrearEventoAlActivarBits(int $bitsRequeridos): void
    {
        $this->mascara->al()->activar($bitsRequeridos)->haz($this->funcion);
        $this->funcion->expects($this->once())->method('__invoke');
        $this->mascara->activar($bitsRequeridos);
    }

    /**
     *  @dataProvider dataMascaraDeBitsCualquiera
     */
    public function testCrearEventoAlDesactivarBits(int $bitsRequeridos): void
    {
        $this->mascara->al()->desactivar($bitsRequeridos)->haz($this->funcion);
        $this->funcion->expects($this->once())->method('__invoke');
        $this->mascara->desactivar($bitsRequeridos);
    }

    /**
     *  @dataProvider dataMascaraDeBitsCualquiera
     */
    public function testCrearEventoAlDefinir(int $bitsRequeridos): void
    {
        $this->mascara->al()->definir($bitsRequeridos)->haz($this->funcion);
        $this->funcion->expects($this->once())->method('__invoke');
        $this->mascara->definir($bitsRequeridos);
    }

    public function testCrearEventoAlObtener(): void
    {
        $this->mascara->al()->obtener()->haz($this->funcion);
        $this->funcion->expects($this->once())->method('__invoke');
        $this->mascara->obtener();
    }

    public function dataMascaraDeBitsCualquiera(): array
    {
        return [[0b1010101]];
    }

}
