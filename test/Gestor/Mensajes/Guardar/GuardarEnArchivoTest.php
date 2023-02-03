<?php

declare(strict_types=1);

use Gof\Datos\Archivos\Archivo;
use Gof\Gestor\Mensajes\Guardar\GuardarEnArchivo;
use PHPUnit\Framework\TestCase;

// TAREA
//  Hacer más test

class GuardarEnArchivoTest extends TestCase
{
    protected static $archivo;

    public static function setUpBeforeClass(): void
    {
        self::$archivo = new Archivo(__DIR__ . '/ignorame');
    }

    public function testMensajeVacioDevuelveFalseAlGuardar(): void
    {
        $mensaje = '';
        $gestor = new GuardarEnArchivo(self::$archivo);
        $this->assertFalse($gestor->guardar($mensaje));
    }

    public function testConfiguracionPorDefecto(): void
    {
        $gestor = new GuardarEnArchivo(self::$archivo);
        $configuracionDelGestor = $gestor->configuracion();
        $configuracionEsperada = GuardarEnArchivo::CONCATENAR;

        $this->assertSame($configuracionDelGestor, $configuracionEsperada);
        $this->assertSame($configuracionEsperada, GuardarEnArchivo::CONFIGURACION_POR_DEFECTO);
    }

    /**
     *  @dataProvider dataUnSoloMensaje
     */
    public function testGuardaMensajeCorrectamenteDevolviendoTrue(string $mensaje): void
    {
        $configuracion = 0;
        $gestor = new GuardarEnArchivo(self::$archivo, $configuracion);
        $this->assertTrue($gestor->guardar($mensaje));
    }

    /**
     *  @dataProvider dataUnSoloMensaje
     */
    public function testMensajeGuardadoCorrespondeConElContenidoDelArchivo(string $mensaje): void
    {
        $contenidoDelArchivo = file_get_contents(self::$archivo->ruta());
        $this->assertSame($mensaje, $contenidoDelArchivo);
    }

    /**
     *  @dataProvider dataDosMensajesDiferentesSaludando
     */
    public function testReemplazarContenidoDelArchivoConDosMensajesDiferentes(string $mensajeUno, string $mensajeDos): void
    {
        $configuracion = 0;
        $gestor = new GuardarEnArchivo(self::$archivo, $configuracion);

        $this->assertTrue($gestor->guardar($mensajeUno));
        $this->assertTrue($gestor->guardar($mensajeDos));

        $contenidoDelArchivo = file_get_contents(self::$archivo->ruta());
        $this->assertStringNotContainsString($mensajeUno, $contenidoDelArchivo);
        $this->assertSame($contenidoDelArchivo, $mensajeDos);
    }

    /**
     *  @dataProvider dataDosMensajesDiferentesSaludando
     */
    public function testConcatenarMensajesEnElArchivo(string $mensajeUno, string $mensajeDos): void
    {
        $configuracion = GuardarEnArchivo::CONCATENAR;
        $gestor = new GuardarEnArchivo(self::$archivo, $configuracion);

        file_put_contents(self::$archivo->ruta(), '', 0);
        $this->assertTrue($gestor->guardar($mensajeUno));
        $this->assertTrue($gestor->guardar($mensajeDos));

        $contenidoDelArchivo = file_get_contents(self::$archivo->ruta());
        $this->assertSame($contenidoDelArchivo, $mensajeUno . $mensajeDos);
    }

    /**
     *  @dataProvider dataMensajeSucioParaSerLimpiado
     */
    public function testLimpiarCadenaAntesDeGuardarla(string $mensaje): void
    {
        $configuracion = GuardarEnArchivo::LIMPIAR_MENSAJE;
        $gestor = new GuardarEnArchivo(self::$archivo, $configuracion);

        $this->assertTrue($gestor->guardar($mensaje));
        $contenidoDelArchivo = file_get_contents(self::$archivo->ruta());

        $this->assertNotSame($contenidoDelArchivo, $mensaje);
    }

    public function dataUnSoloMensaje(): array
    {
        return [['Hola mundo']];
    }

    public function dataDosMensajesDiferentesSaludando(): array
    {
        return [
            ['Hola', 'Mundo']
        ];
    }

    public function dataMensajeSucioParaSerLimpiado(): array
    {
        return [
            ['    Hola    mundo,   todo  bien?   ']
        ];
    }

}
