<?php

namespace Test\Gestor\Registro\Mensajes;

use Gof\Contrato\Registro\Mensajes\MensajeVinculante as IMensajeVinculante;
use Gof\Gestor\Registro\Mensajes\MensajeVinculante;
use PHPUnit\Framework\TestCase;

class MensajeVinculanteTest extends TestCase
{

    public function testObtenerMensajeConElQueSeConstruyoElObjeto(): void
    {
        $almacen = [];
        $mensajeOriginal = 'Hola \1';
        $mensajeVinculante = new MensajeVinculante($almacen, $mensajeOriginal);
        $this->assertSame($mensajeOriginal, $mensajeVinculante->obtenerMensajeOriginal());
    }

    public function testGuardarPersisteElMensajeEnElAlmacenReferenciado(): void
    {
        $almacen = [];
        $mensaje = 'Chau \1';
        $mensajeVinculante = new MensajeVinculante($almacen, $mensaje);
        $this->assertEmpty($almacen);
        $this->assertTrue($mensajeVinculante->guardar());
        $this->assertCount(1, $almacen);
        $this->assertContains($mensajeVinculante->obtenerMensajeConvertido(), $almacen);
    }

    /**
     * @dataProvider dataMensajesYVinculos
     */
    public function testVincularMensajes(string $mensajeEsperado, string $mensaje, array $vinculos): void
    {
        $almacen = [];
        $mensajeVinculante = new MensajeVinculante($almacen, $mensaje);
        foreach( $vinculos as $identificador => $reemplazo ) {
            $mensajeVinculante->vincular($identificador, $reemplazo);
        }
        $this->assertTrue($mensajeVinculante->guardar());
        $this->assertSame($mensaje, $mensajeVinculante->obtenerMensajeOriginal());
        $this->assertSame($mensajeEsperado, $mensajeVinculante->obtenerMensajeConvertido());
    }

    public function dataMensajesYVinculos(): array
    {
        return [
            ['Mensaje esperado', 'Mensaje \1', [1 => 'esperado']],
            ['Hola Cesar, y chau Cesar.', 'Hola \1, y chau \1.', [1 => 'Cesar']],
        ];
    }

}
