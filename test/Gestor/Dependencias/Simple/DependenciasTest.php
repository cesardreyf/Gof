<?php

declare(strict_types=1);

namespace Test\Gestor\Dependencias\Simple;

use Gof\Contrato\Dependencias\Dependencias as Contrato;
use Gof\Gestor\Dependencias\Simple\Dependencias;
use Gof\Gestor\Dependencias\Simple\Excepcion\ClaseInexistente;
use Gof\Gestor\Dependencias\Simple\Excepcion\ClaseNoReservada;
use Gof\Gestor\Dependencias\Simple\Excepcion\ClaseReservada;
use Gof\Gestor\Dependencias\Simple\Excepcion\DependenciasInexistentes;
use Gof\Gestor\Dependencias\Simple\Excepcion\ObjetoNoCorrespondido;
use Gof\Gestor\Dependencias\Simple\Excepcion\SinPermisosParaCambiar;
use Gof\Gestor\Dependencias\Simple\Excepcion\SinPermisosParaDefinir;
use Gof\Gestor\Dependencias\Simple\Excepcion\SinPermisosParaRemover;
use Gof\Interfaz\Errores\Errores;
use PHPUnit\Framework\TestCase;
use stdClass;

class UnaClase {}
interface UnaInterfaz {}
class UnaClaseQueImplementaLainterfaz implements UnaInterfaz {}

class DependenciasTest extends TestCase
{
    private $dependencias;
    private $funcionVacia;

    public function setUp(): void
    {
        $this->funcionVacia = function() {};
        $this->dependencias = new Dependencias();
    }

    public function testImplementaElContrato(): void
    {
        $this->assertInstanceOf(Contrato::class, $this->dependencias);
    }

    public function testMetodoErroresDevuelveUnaInstanciaDeErrores(): void
    {
        $this->assertInstanceOf(Errores::class, $this->dependencias->errores());
    }

    public function testAgregarUnaClaseExistenteYObtenerla(): void
    {
        $funcion = function() { return new UnaClase(); };
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $funcion));
        $this->assertInstanceOf(UnaClase::class, $this->dependencias->obtener(UnaClase::class));
    }

    public function testAgregarUnaClaseExistenteQueImplementaUnaInterfazYObtenerla(): void
    {
        $funcion = function() { return new UnaClaseQueImplementaLainterfaz(); };
        $this->assertTrue($this->dependencias->agregar(UnaInterfaz::class, $funcion));
        $this->assertInstanceOf(UnaInterfaz::class, $this->dependencias->obtener(UnaInterfaz::class));
    }

    public function testErrorAlAgregarUnaClaseInexistente(): void
    {
        $nombreDeClaseInexistente = 'ClaseInexistente';
        $this->assertFalse($this->dependencias->agregar($nombreDeClaseInexistente, $this->funcionVacia));
        $this->assertSame(Dependencias::ERROR_CLASE_INEXISTENTE, $this->dependencias->errores()->error());

        $this->expectException(ClaseInexistente::class);
        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->dependencias->agregar($nombreDeClaseInexistente, $this->funcionVacia);
    }

    public function testErrorAlAgregaUnaClaseYaReservada(): void
    {
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $this->funcionVacia));
        $this->assertFalse($this->dependencias->agregar(UnaClase::class, $this->funcionVacia));
        $this->assertSame(Dependencias::ERROR_CLASE_RESERVADA, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ClaseReservada::class);
        $this->dependencias->agregar(UnaClase::class, $this->funcionVacia);
    }

    public function testErrorAlObtenerUnaClaseQueNoEstaReservada(): void
    {
        $unaClaseNoReservada = 'UnaClaseNoReservada';
        $this->assertNull($this->dependencias->obtener($unaClaseNoReservada));
        $this->assertSame(Dependencias::ERROR_CLASE_NO_RESERVADA, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ClaseNoReservada::class);
        $this->dependencias->obtener($unaClaseNoReservada);
    }

    /**
     *  @dataProvider dataDiferentesTiposDeValoresParaRetornar
     */
    public function testErrorAlObtenerInstanciaPorUnaFuncionQueNoRetornaUnObjeto($algo): void
    {
        $funcion = function() use ($algo) { return $algo; };
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $funcion));
        $this->assertNull($this->dependencias->obtener(UnaClase::class));
        $this->assertSame(Dependencias::ERROR_OBJETO_NO_CORRESPONDIDO, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ObjetoNoCorrespondido::class);
        $this->dependencias->obtener(UnaClase::class);
    }

    public function testErrorAlObtenerObjetoNoImplementaUnaInterfaz(): void
    {
        $funcion = function() { return new UnaClase(); };
        $this->assertTrue($this->dependencias->agregar(UnaInterfaz::class, $funcion));
        $this->assertNull($this->dependencias->obtener(UnaInterfaz::class));
        $this->assertSame(Dependencias::ERROR_OBJETO_NO_CORRESPONDIDO, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ObjetoNoCorrespondido::class);
        $this->dependencias->obtener(UnaInterfaz::class);
    }

    public function testInstanciarUnaSolaVezYCachearElObjeto(): void
    {
        $funcion = $this->getMockBuilder(stdClass::class)->setMethods(['__invoke'])->getMock();
        $funcion->expects($this->once())->method('__invoke')->willReturn(new UnaClase());
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $funcion));

        $unaInstancia = $this->dependencias->obtener(UnaClase::class);
        $this->assertInstanceOf(UnaClase::class, $unaInstancia);

        // Se vuelve a obtener la misma instancia sin llamar nuevamente a la función que la crea
        $this->assertSame($unaInstancia, $this->dependencias->obtener(UnaClase::class));
    }

    public function testInstanciarAlAgregar(): void
    {
        $this->dependencias->configuracion()->activar(Dependencias::INSTANCIAR_AL_AGREGAR);
        $funcion = $this->getMockBuilder(stdClass::class)->setMethods(['__invoke'])->getMock();
        $funcion->expects($this->once())->method('__invoke')->willReturn(new UnaClase());
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $funcion));
    }

    public function testErrorAlCambiarSiNoEstaPermitido(): void
    {
        $claseNoReservada = 'ClaseNoReservada';
        $this->dependencias->configuracion()->desactivar(Dependencias::PERMITIR_CAMBIAR);
        $this->assertFalse($this->dependencias->cambiar($claseNoReservada, new stdClass()));
        $this->assertSame(Dependencias::ERROR_SIN_PERMISOS_PARA_CAMBIAR, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(SinPermisosParaCambiar::class);
        $this->dependencias->cambiar($claseNoReservada, new stdClass());
    }

    public function testErrorAlCambiarUnaClaseNoReservada(): void
    {
        $claseNoReservada = 'ClaseNoReservada';
        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_CAMBIAR);
        $this->assertFalse($this->dependencias->cambiar($claseNoReservada, new stdClass()));
        $this->assertSame(Dependencias::ERROR_CLASE_NO_RESERVADA, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ClaseNoReservada::class);
        $this->dependencias->cambiar($claseNoReservada, new stdClass());
    }

    public function testErrorAlCambiarPorAgregarUnaNuevaInstanciaQueNoCorresponde(): void
    {
        $this->assertTrue($this->dependencias->agregar(UnaInterfaz::class, $this->funcionVacia));
        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_CAMBIAR);

        $this->assertFalse($this->dependencias->cambiar(UnaInterfaz::class, new UnaClase()));
        $this->assertSame(Dependencias::ERROR_OBJETO_NO_CORRESPONDIDO, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ObjetoNoCorrespondido::class);
        $this->dependencias->cambiar(UnaInterfaz::class, new UnaClase());
    }

    public function testModificarNuevaInstanciaDeUnaClase(): void
    {
        $instancia = new UnaClase();
        $funcion = function() use ($instancia) { return $instancia; };
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $funcion));
        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_CAMBIAR);
        $this->assertSame($instancia, $this->dependencias->obtener(UnaClase::class));

        $otraInstanciaDeLaMismaClase = new UnaClase();
        $this->assertTrue($this->dependencias->cambiar(UnaClase::class, $otraInstanciaDeLaMismaClase));
        $this->assertSame($otraInstanciaDeLaMismaClase, $this->dependencias->obtener(UnaClase::class));
        $this->assertNotSame($instancia, $otraInstanciaDeLaMismaClase);
    }

    public function testRemoverUnaClasePreviamenteReservada(): void
    {
        $funcion = function() { return new UnaClase(); };
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $funcion));
        $this->assertNotNull($this->dependencias->obtener(UnaClase::class));

        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_REMOVER);
        $this->assertTrue($this->dependencias->remover(UnaClase::class));

        $this->assertNull($this->dependencias->obtener(UnaClase::class));
        $this->assertSame(Dependencias::ERROR_CLASE_NO_RESERVADA, $this->dependencias->errores()->error());
    }

    public function testErrorAlRemoverUnaClaseSinPermisos(): void
    {
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $this->funcionVacia));
        $this->dependencias->configuracion()->desactivar(Dependencias::PERMITIR_CAMBIAR);
        $this->assertFalse($this->dependencias->remover(UnaClase::class));
        $this->assertSame(Dependencias::ERROR_SIN_PERMISOS_PARA_REMOVER, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(SinPermisosParaRemover::class);
        $this->dependencias->remover(UnaClase::class);
    }

    public function testDefinirUnaInstanciaDeUnaClaseNoReservada(): void
    {
        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_DEFINIR);
        $this->assertTrue($this->dependencias->definir(UnaClase::class, new UnaClase()));
        $this->assertInstanceOf(UnaClase::class, $this->dependencias->obtener(UnaClase::class));

        $this->assertTrue($this->dependencias->definir(UnaInterfaz::class, new UnaClaseQueImplementaLainterfaz()));
        $this->assertInstanceOf(UnaInterfaz::class, $this->dependencias->obtener(UnaInterfaz::class));
    }

    public function testErrorAlDefinirSinPermisos(): void
    {
        $this->dependencias->configuracion()->desactivar(Dependencias::PERMITIR_DEFINIR);
        $this->assertFalse($this->dependencias->definir(UnaClase::class, new UnaClase()));
        $this->assertSame(Dependencias::ERROR_SIN_PERMISOS_PARA_DEFINIR, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(SinPermisosParaDefinir::class);
        $this->dependencias->definir(UnaClase::class, new UnaClase());
    }

    public function testErrorAlDefinirUnaClaseYaReservada(): void
    {
        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_DEFINIR);
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $this->funcionVacia));
        $this->assertFalse($this->dependencias->definir(UnaClase::class, new UnaClase()));
        $this->assertSame(Dependencias::ERROR_CLASE_RESERVADA, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ClaseReservada::class);
        $this->dependencias->definir(UnaClase::class, new UnaClase());
    }

    public function testErrorAlDefinirUnaClaseQueNoCorresponde(): void
    {
        $this->dependencias->configuracion()->activar(Dependencias::PERMITIR_DEFINIR);
        $this->assertFalse($this->dependencias->definir(UnaInterfaz::class, new UnaClase()));
        $this->assertSame(Dependencias::ERROR_OBJETO_NO_CORRESPONDIDO, $this->dependencias->errores()->error());

        $this->dependencias->configuracion()->activar(Dependencias::LANZAR_EXCEPCION);
        $this->expectException(ObjetoNoCorrespondido::class);
        $this->dependencias->definir(UnaInterfaz::class, new UnaClase());
    }

    /**
     *  @dataProvider dataNombresDeClasesInexistentes
     */
    public function testMetodoDependoLanzaExcepcionSiNoExistenLasDependenciasIndicadas(string $nombreDeClaseInexistente): void
    {
        $this->expectException(DependenciasInexistentes::class);
        $this->dependencias->dependo($nombreDeClaseInexistente);
    }

    public function testMetodoDependoNoLanzaExcepcionSiExistenLasDependencias(): void
    {
        $this->assertTrue($this->dependencias->agregar(UnaClase::class, $this->funcionVacia));
        $this->assertTrue($this->dependencias->agregar(UnaInterfaz::class, $this->funcionVacia));
        $this->assertNull($this->dependencias->dependo(UnaClase::class, UnaInterfaz::class));
    }

    public function dataDiferentesTiposDeValoresParaRetornar(): array
    {
        return [
            [0],        //< Int
            [1.4],      //< Float
            [null],     //< Void
            [false],    //< Bool
            ['string']  //< String
        ];
    }

    public function dataNombresDeClasesInexistentes(): array
    {
        return [
            [UnaClase::class],
            [UnaInterfaz::class]
        ];
    }

}
