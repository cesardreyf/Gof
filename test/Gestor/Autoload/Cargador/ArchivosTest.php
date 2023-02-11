<?php

declare(strict_types=1);

use Gof\Gestor\Autoload\Cargador\Archivos;
use Gof\Gestor\Autoload\Excepcion\ArchivoInaccesible;
use Gof\Gestor\Autoload\Excepcion\ArchivoInexistente;
use Gof\Gestor\Autoload\Interfaz\Cargador;
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

    public function testPasarConfiguracionPorElConstructor(): void
    {
        $configuracion1 = 12345;
        $cargador1 = new Archivos($configuracion1);

        $configuracion2 = 67890;
        $cargador2 = new ARchivos($configuracion2);

        $this->assertSame($configuracion1, $cargador1->configuracion());
        $this->assertSame($configuracion2, $cargador2->configuracion());
    }

    public function testConfiguracionPorDefecto(): void
    {
        $configuracionEsperada = Archivos::LANZAR_EXCEPCIONES;
        $this->assertSame($configuracionEsperada, $this->cargador->configuracion());
        $this->assertSame($configuracionEsperada, Archivos::CONFIGURACION_POR_DEFECTO);
    }

    public function testCargarArchivoCorrectamente(): void
    {
        $rutaDelArchivo = __DIR__ . '/Ignorame.php';
        $this->assertTrue($this->cargador->cargar($rutaDelArchivo));

        $rutaDelArchivo = __DIR__ . '/Ignorame';
        $this->cargador->configuracion($this->cargador->configuracion() | Archivos::INCLUIR_EXTENSION);
        $this->assertTrue($this->cargador->cargar($rutaDelArchivo));
    }

    public function testErrorAlCargarCuandoElArchivoNoExiste(): void
    {
        $ruta = 'ruta_inexistente';
        $configuracion = $this->cargador->configuracion();
        $this->cargador->configuracion($configuracion & ~Archivos::LANZAR_EXCEPCIONES);

        $this->assertFalse($this->cargador->cargar($ruta));
        $this->assertSame(Archivos::ERROR_ARCHIVO_INEXISTENTE, $this->cargador->error());

        $this->cargador->configuracion($configuracion);
        $this->expectException(ArchivoInexistente::class);
        $this->cargador->cargar($ruta);
    }

    public function testErrorAlCargarCuandoElArchivoNoEsLegible(): void
    {
        $rutaDeUnArchivoIlegible = __DIR__ . '/Ilegible';
        $this->assertFalse(is_readable($rutaDeUnArchivoIlegible));

        // Desactivar momentaneamente el lanzamiento de excepciones
        $configuracion = $this->cargador->configuracion();
        $this->cargador->configuracion($configuracion & ~Archivos::LANZAR_EXCEPCIONES);
        $this->assertFalse($this->cargador->cargar($rutaDeUnArchivoIlegible));

        // Reactivando los lanzamientos de excepciones
        $this->cargador->configuracion($configuracion);
        $this->expectException(ArchivoInaccesible::class);
        $this->cargador->cargar($rutaDeUnArchivoIlegible);
    }

}
