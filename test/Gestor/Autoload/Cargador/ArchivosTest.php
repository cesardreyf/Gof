<?php

declare(strict_types=1);

namespace Test\Gestor\Autoload\Cargador;

use Gof\Gestor\Autoload\Cargador\Archivos;
use Gof\Gestor\Autoload\Excepcion\ArchivoInaccesible;
use Gof\Gestor\Autoload\Excepcion\ArchivoInexistente;
use Gof\Gestor\Autoload\Interfaz\Cargador;
use Gof\Interfaz\Bits\Mascara;
use PHPUnit\Framework\TestCase;

class ArchivosTest extends TestCase
{
    private $cargador;

    public function setUp(): void
    {
        $this->cargador = new Archivos();
    }

    public function testImplementacionDeLaInterfaz(): void
    {
        $this->assertInstanceOf(Cargador::class, $this->cargador);
    }

    public function testSinErroresAlInstanciar(): void
    {
        $sinErrores = 0;
        $this->assertSame($sinErrores, $this->cargador->error());
    }

    public function testConfiguracionInterna(): void
    {
        $this->assertInstanceOf(Mascara::class, $this->cargador->configuracion());
    }

    public function testConfiguracionPorDefecto(): void
    {
        $configuracionEsperada = Archivos::LANZAR_EXCEPCIONES;
        $this->assertSame($configuracionEsperada, Archivos::CONFIGURACION_POR_DEFECTO);
        $this->assertSame($configuracionEsperada, $this->cargador->configuracion()->obtener());
    }

    public function testCargarArchivoCorrectamente(): void
    {
        $rutaDelArchivo = __DIR__ . '/Ignorame.php';
        $this->assertTrue($this->cargador->cargar($rutaDelArchivo));

        $rutaDelArchivo = __DIR__ . '/Ignorame';
        $this->cargador->configuracion()->activar(Archivos::INCLUIR_EXTENSION);
        $this->assertTrue($this->cargador->cargar($rutaDelArchivo));
    }

    public function testErrorAlCargarCuandoElArchivoNoExiste(): void
    {
        $ruta = 'ruta_inexistente';
        $this->cargador->configuracion()->desactivar(Archivos::LANZAR_EXCEPCIONES);

        $this->assertFalse($this->cargador->cargar($ruta));
        $this->assertSame(Archivos::ERROR_ARCHIVO_INEXISTENTE, $this->cargador->error());

        $this->cargador->configuracion()->activar(Archivos::LANZAR_EXCEPCIONES);
        $this->expectException(ArchivoInexistente::class);
        $this->cargador->cargar($ruta);
    }

    public function testErrorAlCargarCuandoElArchivoNoEsLegible(): void
    {
        $rutaDeUnArchivoIlegible = __DIR__ . '/Ilegible';
        $this->assertFalse(is_readable($rutaDeUnArchivoIlegible));
        $this->cargador->configuracion()->desactivar(Archivos::LANZAR_EXCEPCIONES);
        $this->assertFalse($this->cargador->cargar($rutaDeUnArchivoIlegible));

        $this->cargador->configuracion()->activar(Archivos::LANZAR_EXCEPCIONES);
        $this->expectException(ArchivoInaccesible::class);
        $this->cargador->cargar($rutaDeUnArchivoIlegible);
    }

}
