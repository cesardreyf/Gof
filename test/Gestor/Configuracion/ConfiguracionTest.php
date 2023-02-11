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

}
