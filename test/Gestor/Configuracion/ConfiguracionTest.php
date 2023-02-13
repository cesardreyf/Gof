<?php

declare(strict_types=1);

use Gof\Contrato\Configuracion\Configuracion as IConfiguracion;
use Gof\Gestor\Configuracion\Configuracion;
use PHPUnit\Framework\TestCase;

class ConfiguracionTest extends TestCase
{

    public function testImplementarLaInterfaz(): void
    {
        $this->assertInstanceOf(IConfiguracion::class, new Configuracion());
    }

    public function testValorPorDefectoAlInstanciar(): void
    {
        $valorPorDefecto = 0;
        $configuracion = new Configuracion();
        $this->assertSame($valorPorDefecto, $configuracion->obtener());
    }

    public function testDiferentesValoresAlInstanciar(): void
    {
        $valor1 = 12345;
        $configuracion1 = new Configuracion($valor1);

        $valor2 = 67890;
        $configuracion2 = new Configuracion($valor2);

        $this->assertSame($valor1, $configuracion1->obtener());
        $this->assertSame($valor2, $configuracion2->obtener());
    }

    public function testActivarBits(): void
    {
        $bits = 2 | 8 | 32;
        $configuracion = new Configuracion();
        $this->assertSame(0, $configuracion->obtener());
        $this->assertSame($bits, $configuracion->activar($bits));

        $masBits = 1 | 4 | 16;
        $mascaraDeBitsEsperado = 1 | 2 | 4 | 8 | 16 | 32;
        $this->assertSame($mascaraDeBitsEsperado, $configuracion->activar($masBits));
    }

    public function testDesactivarBits(): void
    {
        $bitsActivados = 1 | 2 | 4 | 8 | 16;
        $configuracion = new Configuracion($bitsActivados);
        $this->assertSame($bitsActivados, $configuracion->obtener());

        $bitsQueQuieroDesactivar = 2 | 8;
        $configuracion->desactivar($bitsQueQuieroDesactivar);

        $mascaraDeBitsEsperado = 1 | 4 | 16;
        $this->assertSame($mascaraDeBitsEsperado, $configuracion->obtener());
    }

    public function testBitsActivados(): void
    {
        $bitsActivados = 1 | 16 | 32;
        $configuracion = new Configuracion($bitsActivados);

        $this->assertTrue($configuracion->activadas(1));
        $this->assertTrue($configuracion->activadas(16));
        $this->assertTrue($configuracion->activadas(32));

        $this->assertFalse($configuracion->activadas(2));
        $this->assertFalse($configuracion->activadas(4));
        $this->assertFalse($configuracion->activadas(8));
    }

    public function testBitsDesactivados(): void
    {
        $bitsActivados = 1 | 4 | 16;
        $configuracion = new Configuracion($bitsActivados);

        $this->assertTrue($configuracion->desactivadas(2));
        $this->assertTrue($configuracion->desactivadas(8));
        $this->assertTrue($configuracion->desactivadas(32));

        $this->assertFalse($configuracion->desactivadas(1));
        $this->assertFalse($configuracion->desactivadas(4));
        $this->assertFalse($configuracion->desactivadas(16));
    }

    public function testDefinirElValorInterno(): void
    {
        $configuracion = new Configuracion();
        $this->assertSame(0, $configuracion->obtener());

        $valores = 1 | 2 | 4 | 8;
        $configuracion->definir($valores);
        $this->assertSame($valores, $configuracion->obtener());

        $nuevoValor = 16 | 32 | 64 | 128;
        $configuracion->definir($nuevoValor);
        $this->assertNotSame($valores, $configuracion->obtener());
    }

    public function testActivarConVariosArgumentos(): void
    {
        $configuracion = new Configuracion();

        $activarBitN1 = 0b001;
        $activarBitN2 = 0b010;
        $activarBitN3 = 0b100;
        $todosActivos = 0b111;

        $configuracion->activar($activarBitN1, $activarBitN2, $activarBitN3);
        $this->assertSame($todosActivos, $configuracion->obtener());
    }

    public function testDesactivarConVariosArgumentos(): void
    {
        $desactivarBitN1 = 0b001;
        $desactivarBitN2 = 0b010;
        $desactivarBitN3 = 0b100;

        $configuracionInicial = 0b111;
        $configuracionAlFinal = 0b000;

        $configuracion = new Configuracion($configuracionInicial);
        $configuracion->desactivar($desactivarBitN1, $desactivarBitN2, $desactivarBitN3);
        $this->assertSame($configuracionAlFinal, $configuracion->obtener());
    }

    public function testActivadasConVariosArgumentos(): void
    {
        $bitsActivos = 0b0101;

        $bitN1Activo = 0b0001;
        $bitN2Activo = 0b0010;
        $bitN3Activo = 0b0100;
        $bitN4Activo = 0b1000;

        $configuracion = new Configuracion($bitsActivos);
        $this->assertTrue($configuracion->activadas($bitN1Activo, $bitN3Activo));
        $this->assertFalse($configuracion->activadas($bitN2Activo, $bitN4Activo));
    }

    public function testDesactivadasConVariosArgumentos(): void
    {
        $bitsActivos = 0b0101;

        $bitN1Inactivo = 0b0001;
        $bitN2Inactivo = 0b0010;
        $bitN3Inactivo = 0b0100;
        $bitN4Inactivo = 0b1000;

        $configuracion = new Configuracion($bitsActivos);
        $this->assertTrue($configuracion->desactivadas($bitN2Inactivo, $bitN4Inactivo));
        $this->assertFalse($configuracion->desactivadas($bitN1Inactivo, $bitN3Inactivo));
    }

}
