<?php

declare(strict_types=1);

namespace Test\Gestor\Mensajes\Guardar;

use Gof\Datos\Archivos\Archivo;
use Gof\Gestor\Mensajes\Guardar\GuardarEnArchivo;
use Gof\Interfaz\Bits\Mascara;
use PHPUnit\Framework\TestCase;

class GuardarEnArchivoTest extends TestCase
{
    const RUTA_DEL_ARCHIVO = __DIR__ . '/ignorame';

    private $gestor;

    public function setUp(): void
    {
        $this->gestor = new GuardarEnArchivo(new Archivo(self::RUTA_DEL_ARCHIVO));
    }

    public static function tearDownAfterClass(): void
    {
        file_put_contents(self::RUTA_DEL_ARCHIVO, '', 0);
    }

    public function testInstanciaDeLaConfiguracion(): void
    {
        $this->assertInstanceOf(Mascara::class, $this->gestor->configuracion());
    }

    public function testGuardarDevuelveFalseSiElMensajeEstaVacio(): void
    {
        $mensajeVacio = '';
        $this->assertFalse($this->gestor->guardar($mensajeVacio));
    }

    /**
     *  @dataProvider dataMensajesQueSePuedenConcatenar
     */
    public function testGuardarMensajesConcatenados(array $mensajes): void
    {
        $mensajeFinalSupuestamenteConcatenado = '';
        file_put_contents(self::RUTA_DEL_ARCHIVO, '', 0);
        $this->gestor->configuracion()->activar(GuardarEnArchivo::CONCATENAR);

        foreach( $mensajes as $mensaje ) {
            $this->assertTrue($this->gestor->guardar($mensaje));
            $mensajeFinalSupuestamenteConcatenado .= $mensaje;
        }

        $mensajeGuardadoEnElArchivo = file_get_contents(self::RUTA_DEL_ARCHIVO);
        $this->assertSame($mensajeFinalSupuestamenteConcatenado, $mensajeGuardadoEnElArchivo);
    }

    /**
     *  @dataProvider dataMensajesQueSeReemplazaranEnElArchivo
     */
    public function testGuardarMensajesReemplazandoElContenidoDelArchivo(array $mensajes): void
    {
        $ultimoMensaje = '';
        file_put_contents(self::RUTA_DEL_ARCHIVO, '', 0);
        $this->gestor->configuracion()->desactivar(GuardarEnArchivo::CONCATENAR);

        foreach( $mensajes as $mensaje ) {
            $this->assertTrue($this->gestor->guardar($mensaje));
            $ultimoMensaje = $mensaje;
        }

        $mensajeGuardadoEnElArchivo = file_get_contents(self::RUTA_DEL_ARCHIVO);
        $this->assertSame($mensajeGuardadoEnElArchivo, $ultimoMensaje);
    }

    /**
     *  @dataProvider dataMensajesParaGuardarQueEstanSucios
     */
    public function testGuardarMensajesPeroLimpiandoleUnPoquito(string $mensaje): void
    {
        file_put_contents(self::RUTA_DEL_ARCHIVO, '', 0);
        $this->gestor->configuracion()->activar(GuardarEnArchivo::LIMPIAR_MENSAJE);
        $this->gestor->guardar($mensaje);
        $this->assertSame(trim($mensaje), file_get_contents(self::RUTA_DEL_ARCHIVO));
    }

    public function dataMensajesQueSePuedenConcatenar(): array
    {
        return [
            [['Hola ', 'Mundo']]
        ];
    }

    public function dataMensajesQueSeReemplazaranEnElArchivo(): array
    {
        return [[[
            'Hola',
            'Mundo',
            'Necesito',
            'Mas Bitcoins'
        ]]];
    }

    public function dataMensajesParaGuardarQueEstanSucios(): array
    {
        return [
            ['  Mensaje con espacios al principio y al final   ']
        ];
    }

}
