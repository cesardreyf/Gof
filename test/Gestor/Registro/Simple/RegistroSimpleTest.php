<?php

declare(strict_types=1);

namespace Test\Gestor\Registro\Simple;

use Gof\Contrato\Registro\Registro;
use Gof\Gestor\Registro\Simple\RegistroSimple;
use Gof\Interfaz\Bits\Mascara;
use Gof\Interfaz\Mensajes\Guardable;
use PHPUnit\Framework\TestCase;

class RegistroSimpleTest extends TestCase
{
    private $gestorDeGuardado;

    public function setUp(): void
    {
        $this->gestorDeGuardado = $this->getMockBuilder(Guardable::class)->setMethods(['guardar'])->getMock();
    }

    public function test_implementarContratoRegistro(): void
    {
        $registro = new RegistroSimple($this->gestorDeGuardado);
        $this->assertInstanceOf(Registro::class, $registro);
    }

    public function test_metodoConfiguracionDevuelveUnaInstanciaDeMascaraDeBits(): void
    {
        $registro = new RegistroSimple($this->gestorDeGuardado);
        $this->assertInstanceOf(Mascara::class, $registro->configuracion());
    }

    public function test_guardarCerosRegistros(): void
    {
        $registro = new RegistroSimple($this->gestorDeGuardado);
        $this->gestorDeGuardado->expects($this->never())->method('guardar')->willReturn(true);
        $this->assertTrue($registro->volcar());
    }

    public function test_guardarVariosRegistros(): void
    {
        $bugs = new RegistroSimple($this->gestorDeGuardado);
        $this->gestorDeGuardado->expects($this->atLeast(3))->method('guardar')->willReturn(true);

        $bugs->registrar('Un bug en la máquina');
        $bugs->registrar('Otro registro de bug');
        $bugs->registrar('Alguien arregle esto');

        $this->assertTrue($bugs->volcar());
    }

    public function test_errorAlGuardarDevuelveFalseAlVolcar(): void
    {
        $tareas = new RegistroSimple($this->gestorDeGuardado);
        $this->gestorDeGuardado->expects($this->once())->method('guardar')->willReturn(false);

        $tareas->registrar('Trabajar');

        $this->assertFalse($tareas->volcar());
    }

    public function test_configuracionUnirMensajesAlRegistrarlos(): void
    {
        $mensajes = new RegistroSimple($this->gestorDeGuardado);
        $mensajes->configuracion()->activar(RegistroSimple::UNIR_MENSAJES_AL_VOLCAR);

        $mensajes->registrar('Hola ');
        $mensajes->registrar('mundo');

        $this->gestorDeGuardado->expects($this->once())->method('guardar')->with('Hola mundo')->willReturn(true);
        $this->assertTrue($mensajes->volcar());
    }

    public function test_configuracionVolcarPilaAlRegistrar(): void
    {
        $mensajes = new RegistroSimple($this->gestorDeGuardado);
        $mensajes->configuracion()->activar(RegistroSimple::VOLCAR_PILA_AL_REGISTRAR);
        $this->gestorDeGuardado->expects($this->once())->method('guardar')->with('Hola mundo')->willReturn(true);

        $mensajes->registrar('Hola mundo');
    }

    public function test_obtenerYDefinirSeparador(): void
    {
        $registros = new RegistroSimple($this->gestorDeGuardado);
        $this->assertSame('', $registros->separador());

        $registros->separador("\n");
        $this->assertSame("\n", $registros->separador());

        $this->assertSame(',', $registros->separador(','));
    }

    public function test_configuracionUnirMensajesAlVolcar(): void
    {
        $separador = "\n";
        $mensajeUno = 'Hola mundo';
        $mensajeDos = 'Chau mundo';
        $mensajesUnidos = $mensajeUno . $separador . $mensajeDos;

        $saludos = new RegistroSimple($this->gestorDeGuardado);
        $saludos->configuracion()->activar(RegistroSimple::UNIR_MENSAJES_AL_VOLCAR);
        $this->gestorDeGuardado->expects($this->once())->method('guardar')->with($mensajesUnidos);

        $saludos->registrar($mensajeUno);
        $saludos->registrar($mensajeDos);
        $saludos->separador($separador);
        $saludos->volcar();
    }

    public function test_configuracionLimpiarPilaAlVolcar(): void
    {
        $registro = new RegistroSimple($this->gestorDeGuardado);
        $registro->configuracion()->activar(RegistroSimple::LIMPIAR_PILA_AL_VOLCAR);

        $registro->registrar('algo');
        $registro->registrar('bobo');
        $registro->registrar('cosa');
        $registro->volcar();

        $this->assertSame([], $registro->pila());
    }

    public function test_configuracionNoLimpiarPilaAlVolcar(): void
    {
        $registro = new RegistroSimple($this->gestorDeGuardado);
        $registro->configuracion()->desactivar(RegistroSimple::LIMPIAR_PILA_AL_VOLCAR);

        $registro->registrar('algo');
        $registro->volcar();
        $this->assertSame(['algo'], $registro->pila());

        $registro->registrar('bobo');
        $registro->volcar();
        $this->assertSame(['algo', 'bobo'], $registro->pila());

        $registro->registrar('cosa');
        $registro->volcar();
        $this->assertSame(['algo', 'bobo', 'cosa'], $registro->pila());
    }

}
