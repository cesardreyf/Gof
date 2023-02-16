<?php

declare(strict_types=1);

namespace Test\Sistema\Reportes\Plantilla;

use Exception;
use Gof\Sistema\Reportes\Interfaz\Plantilla;
use Gof\Sistema\Reportes\Plantilla\Excepciones;
use PHPUnit\Framework\TestCase;
use stdClass;

class ExcepcionesTest extends TestCase
{
    protected $plantilla;

    public function setUp(): void
    {
        $this->plantilla = new Excepciones();
    }

    public function testImplementaInterfaz(): void
    {
        $this->assertInstanceOf(Plantilla::class, $this->plantilla);
    }

    public function testDatosDiferentesDeExceptionDevuelvenFalse(): void
    {
        $this->assertFalse($this->plantilla->traducir(new stdClass()));
        $this->assertFalse($this->plantilla->traducir(123456789));
        $this->assertFalse($this->plantilla->traducir(array()));
        $this->assertFalse($this->plantilla->traducir('hola'));
        $this->assertFalse($this->plantilla->traducir(1.234));
    }

    public function testDatosTipoExceptionDevuelveTrue(): void
    {
        $this->assertTrue($this->plantilla->traducir(new Exception()));
    }

    public function testTraduccionCorrectaDeLosDatosDeExceptionEnMensaje(): void
    {
        $mensaje   = 'Bug';
        $codigo    = 1234567;
        $archivo   = __FILE__;
        $linea     = __LINE__ + 1;
        $excepcion = new Exception($mensaje, $codigo);

        $this->plantilla->traducir($excepcion);
        $mensaje = $this->plantilla->mensaje();

        $this->assertStringContainsString($mensaje,        $mensaje);
        $this->assertStringContainsString($archivo,        $mensaje); 
        $this->assertStringContainsString((string)$codigo, $mensaje);
        $this->assertStringContainsString((string)$linea,  $mensaje);
    }

}
