<?php

declare(strict_types=1);

namespace Test\Gestor\Registro\Mensajes;

use Gof\Contrato\Registro\Mensajes\MensajeVinculante as IMensajeVinculante;
use Gof\Contrato\Registro\Mensajes\RegistroDeMensajes as IRegistroDeMensajes;
use Gof\Gestor\Registro\Mensajes\Excepcion\SubregistroExistente;
use Gof\Gestor\Registro\Mensajes\RegistroDeMensajes;
use PHPUnit\Framework\TestCase;

class RegistroDeMensajesTest extends TestCase
{

    public function setUp(): void
    {
        $this->registro = new RegistroDeMensajes();
    }

    public function testImplementarContrato(): void
    {
        $this->assertInstanceOf(IRegistroDeMensajes::class, $this->registro);
    }

    public function testAgregarMensajeYObtenerlo(): void
    {
        $mensaje = 'Hola mundo';
        $this->assertEmpty($this->registro->lista());
        $this->registro->agregarMensaje($mensaje);
        $this->assertCount(1, $this->registro->lista());
        $this->assertContains($mensaje, $this->registro->lista());
    }

    /**
     * @dataProvider dataArrayDeMensajes
     */
    public function testAgregarVariosMensajes(array $mensajes): void
    {
        $this->assertEmpty($this->registro->lista());
        foreach( $mensajes as $mensaje ) {
            $this->registro->agregarMensaje($mensaje);
        }
        $this->assertSame($mensajes, $this->registro->lista());
    }

    public function dataArrayDeMensajes(): array
    {
        return [
            [['hola', 'mundo']],
        ];
    }

    public function testAgregarUnMismoMensajeDosVecesAgregaDosMensajesIguales(): void
    {
        $mismoMensaje = 'El mismo mensaje';
        $this->assertCount(0, $this->registro->lista());
        $this->registro->agregarMensaje($mismoMensaje);
        $this->assertCount(1, $this->registro->lista());
        $this->registro->agregarMensaje($mismoMensaje);
        $this->assertCount(2, $this->registro->lista());
    }

    public function testCrearSubregistro(): void
    {
        $registroDeErrores = $this->registro->crearSubregistro('errores');
        $registroDeInformes = $this->registro->crearSubregistro('informes');
        $this->assertInstanceOf(IRegistroDeMensajes::class, $registroDeErrores);
        $this->assertInstanceOf(IRegistroDeMensajes::class, $registroDeInformes);
        $this->assertNotSame($registroDeErrores, $registroDeInformes);
    }

    public function testCrearDosVecesElMismoSubregistroLanzaUnaExcepcion(): void
    {
        $nombreDelSubregistro = 'subregistro';
        $this->registro->crearSubregistro($nombreDelSubregistro);
        $this->expectException(SubregistroExistente::class);
        $this->registro->crearSubregistro($nombreDelSubregistro);
    }

    public function testMetodoPrepararDevuelveUnaInstanciaDeMensajeVinculante(): void
    {
        $this->assertInstanceOf(IMensajeVinculante::class, $this->registro->preparar('Un mensaje'));
    }

    public function testMetodoVacio(): void
    {
        $this->assertTrue($this->registro->vacio());
        $this->registro->agregarMensaje('Un mensaje');
        $this->assertFalse($this->registro->vacio());
        $this->registro->limpiar();
        $this->assertTrue($this->registro->vacio());
    }

    public function testLimpiarRegistroDeMensajes(): void
    {
        $mensaje = 'Un mensaje cualquiera';
        $this->assertEmpty($this->registro->lista());
        $this->registro->agregarMensaje($mensaje);
        $this->registro->limpiar();
        $this->assertEmpty($this->registro->lista());
    }

    public function testMetodoGuardarPersisteLosMensajesDeLosSubregistrosEnElRegistroPadre(): void
    {
        $mensaje = 'Un mensaje cualquiera';
        $nombreDelSubregistro = 'subregistro';
        $subregistro = $this->registro->crearSubregistro($nombreDelSubregistro);

        $this->assertEmpty($this->registro->lista());
        $subregistro->agregarMensaje($mensaje);

        $this->assertEmpty($this->registro->lista());
        $this->assertTrue($this->registro->guardar());

        $this->assertCount(1, $this->registro->lista());
        $this->assertContains($mensaje, $this->registro->lista()[$nombreDelSubregistro]);
    }

    public function testMetodoVacioDevuelveFalseSiUnoDeSusSubregistrosTieneMensajesRegistrados(): void
    {
        $subregistro = $this->registro->crearSubregistro('da igual');
        $this->assertTrue($this->registro->vacio());
        $subregistro->agregarMensaje('un mensaje cualquiera');
        $this->assertFalse($this->registro->vacio());
    }

    public function testMetodoHayValidaLaExistenciaDeMensajesRegistradosYSiempreEsElInversoDelMetodoVacio(): void
    {
        $this->assertFalse($this->registro->hay());
        $this->assertTrue($this->registro->vacio());
        $this->registro->agregarMensaje('da igual');
        $this->assertTrue($this->registro->hay());
        $this->assertFalse($this->registro->vacio());
        $this->registro->limpiar();
        $this->assertFalse($this->registro->hay());
        $this->assertTrue($this->registro->vacio());

        $subregistro = $this->registro->crearSubregistro('da igual');
        $subregistro->agregarMensaje('un mensaje cualquiera');
        $this->assertTrue($this->registro->hay());
        $this->assertFalse($this->registro->vacio());
        $subregistro->limpiar();
        $this->assertFalse($this->registro->hay());
        $this->assertTrue($this->registro->vacio());
    }

}
