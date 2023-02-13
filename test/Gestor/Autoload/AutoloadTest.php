<?php

declare(strict_types=1);

use Gof\Gestor\Autoload\Autoload;
use Gof\Gestor\Autoload\Excepcion\CadenaInvalidaParaCargar;
use Gof\Gestor\Autoload\Excepcion\EspacioDeNombreInexistente;
use Gof\Gestor\Autoload\Excepcion\EspacioDeNombreInvalido;
use Gof\Gestor\Autoload\Excepcion\ObjetoExistente;
use Gof\Gestor\Autoload\Excepcion\ObjetoInexistente;
use Gof\Gestor\Autoload\Interfaz\Cargador;
use Gof\Gestor\Autoload\Interfaz\Filtro;
use Gof\Interfaz\Archivos\Carpeta;
use Gof\Interfaz\Bits\Mascara;
use PHPUnit\Framework\TestCase;

class AutoloadTest extends TestCase
{
    private $filtro;
    private $cargador;
    private $autoload;

    public function setUp(): void
    {
        $this->filtro = $this->createMock(Filtro::class);
        $this->cargador = $this->createMock(Cargador::class);
        $this->autoload = new Autoload($this->cargador, $this->filtro);
    }

    public function testErroresVacios(): void
    {
        $this->assertSame(0, $this->autoload->error());
    }

    public function testEspaciosDeNombresVacios(): void
    {
        $this->assertEmpty($this->autoload->espaciosDeNombres());
    }

    public function testMetodoFiltro(): void
    {
        $nuevoFiltro = $this->createMock(Filtro::class);
        $this->assertInstanceOf(Filtro::class, $this->autoload->filtro());
        $this->assertSame($nuevoFiltro, $this->autoload->filtro($nuevoFiltro));
    }

    public function testMetodoCargador(): void
    {
        $nuevoCargador = $this->createMock(Cargador::class);
        $this->assertInstanceOf(Cargador::class, $this->autoload->cargador());
        $this->assertSame($nuevoCargador, $this->autoload->cargador($nuevoCargador));
    }

    public function testConfiguracion(): void
    {
        $this->assertInstanceOf(Mascara::class, $this->autoload->configuracion());
        $configuracionPorDefecto = Autoload::LANZAR_EXCEPCIONES | Autoload::REEMPLAZAR_ESPACIOS_DE_NOMBRE | Autoload::DESREGISTRAR_AUTOLOAD_AL_DESTRUIRSE;
        $this->assertSame($configuracionPorDefecto, $this->autoload->configuracion()->obtener());
    }

    /**
     *  @dataProvider dataEspaciosDeNombres
     */
    public function testReservarEspacioDeNombre(string $espacioDeNombre, Carpeta $carpeta): void
    {
        $this->filtro->expects($this->once())
                     ->method('espacioDeNombre')
                     ->with($espacioDeNombre)
                     ->willReturn(true);
        $this->assertTrue($this->autoload->reservar($espacioDeNombre, $carpeta));
        $this->assertNotEmpty($this->autoload->espaciosDeNombres());
    }

    /**
     *  @dataProvider dataEspaciosDeNombres
     */
    public function testErroresAlReservarEspacioDeNombrePorNoPasarElFiltro(string $espacioDeNombre, Carpeta $carpeta): void
    {
        $this->filtro->method('espacioDeNombre')->willReturn(false);
        $this->assertFalse($this->autoload->reservar($espacioDeNombre, $carpeta));
        $this->assertSame(Autoload::ERROR_EN_EL_FILTRO, $this->autoload->error());
        $this->assertEmpty($this->autoload->espaciosDeNombres());

        $this->autoload->configuracion()->activar(Autoload::MODO_ESTRICTO, Autoload::LANZAR_EXCEPCIONES);
        $this->expectException(EspacioDeNombreInvalido::class);
        $this->autoload->reservar($espacioDeNombre, $carpeta);
    }

    /**
     *  @dataProvider dataEspaciosDeNombres
     */
    public function testErrorAlReservarDosVecesElMismoEspacioDeNombre(string $espacioDeNombre, Carpeta $carpeta): void
    {
        $this->filtro->method('espacioDeNombre')->willReturn(true);
        $this->autoload->reservar($espacioDeNombre, $carpeta);
        $this->autoload->reservar($espacioDeNombre, $carpeta);
        $this->assertCount(1, $this->autoload->espaciosDeNombres()); // Se reemplazan el último por el primero

        $this->autoload->configuracion()->desactivar(Autoload::REEMPLAZAR_ESPACIOS_DE_NOMBRE);
        $this->assertFalse($this->autoload->reservar($espacioDeNombre, $carpeta));
        $this->assertSame(Autoload::ERROR_NAMESPACE_RESERVADO, $this->autoload->error());
    }

    /**
     *  @dataProvider dataClaseInterfazTraitValidos
     */
    public function testCargarUnaClaseInterfazTraitCorrectamente(string $nombre, string $tipo): void
    {
        $this->filtro->method('espacioDeNombre')->willReturn(true);
        $this->filtro->expects($this->once())->method('clase')->with($nombre)->willReturn(true);
        $this->cargador->expects($this->once())->method('cargar')->with($nombre)->will($this->returnCallback(
            function($nombre) use($tipo) {
                eval("{$tipo} {$nombre} {}");
                return true;
            }
        ));
        $this->autoload->reservar('', $this->createMock(Carpeta::class));
        $this->assertTrue($this->autoload->cargar($nombre));
        $this->assertSame(0, $this->autoload->error());
    }

    /**
     *  @dataProvider dataCausantesDeErroresAlCargar
     */
    public function testErroresAlCargarUnObjetoDeFormaNormalYConExcepciones(string $nombre, bool $retornoDelFiltro, bool $retornoDelCargador, int $errorEsperado, string $excepcionEsperado): void
    {
        $this->filtro->method('espacioDeNombre')->willReturn(true);
        $this->filtro->method('clase')->willReturn($retornoDelFiltro);
        $this->cargador->method('cargar')->willReturn($retornoDelCargador);

        $this->expectException($excepcionEsperado);
        $this->autoload->reservar('', $this->createMock(Carpeta::class));

        $this->assertFalse($this->autoload->cargar($nombre));
        $this->assertSame($errorEsperado, $this->autoload->error());

        $this->autoload->configuracion()->activar(Autoload::MODO_ESTRICTO, Autoload::LANZAR_EXCEPCIONES);
        $this->autoload->cargar($nombre);
    }

    public function testInstanciarUnaClase(): void
    {
        $clase = 'stdClass';
        $this->assertInstanceOf('stdClass', $this->autoload->instanciar($clase));
    }

    public function testInstanciarUnaClaseConArgumentosDefinidos(): void
    {
        // No se me ocurrió mejor forma de hacerlo, así que...

        $clase = 'UnaClaseConDosParametors';
        class_alias(get_class(new class(0, 0) {
            public $a, $b;
            public function __construct(int $a, int $b) {
                $this->a = $a;
                $this->b = $b;
            }
        }), $clase);

        $argumentoA = 12345;
        $argumentoB = 67890;

        $instancia = $this->autoload->instanciar($clase, $argumentoA, $argumentoB);
        $this->assertSame($argumentoA, $instancia->a);
        $this->assertSame($argumentoB, $instancia->b);
    }

    public function testErrorAlInstanciarUnaClaseInexistente(): void
    {
        $clase = 'Esta\Clase\NoExiste';
        $this->assertNull($this->autoload->instanciar($clase));
    }

    public function testRegistrarYDesregistrarElAutoloadCorrectamente(): void
    {
        // Registrar
        $funcionesRegistradas = spl_autoload_functions();
        $this->assertTrue($this->autoload->registrar());
        $funcionesActuales = spl_autoload_functions();

        $cantidadFuncionesAntesDe = count($funcionesRegistradas);
        $cantidadFuncionesDespuesDe = count($funcionesActuales);

        $this->assertGreaterThan($cantidadFuncionesAntesDe, $cantidadFuncionesDespuesDe);

        // Desregistrar
        $this->assertTrue($this->autoload->desregistrar());
        $funcionesActuales = spl_autoload_functions();
        $cantidadFuncionesDespuesDe = count($funcionesActuales);

        $this->assertEquals($cantidadFuncionesAntesDe, $cantidadFuncionesDespuesDe);
    }

    public function testDesregistrarFuncionAutoloadAlDestruirseElObjetoSegunConfiguracion(): void
    {
        $funcionesRegistradas = spl_autoload_functions();
        $this->assertTrue($this->autoload->registrar());
        $this->assertTrue($this->autoload->configuracion()->activados(Autoload::DESREGISTRAR_AUTOLOAD_AL_DESTRUIRSE));
        $cantidadFuncionesAntesDe = count(spl_autoload_functions());

        // Destruye el objeto
        $this->autoload = null;

        // La configuración por defecto está activo DESREGISTRAR_AUTOLOAD_AL_DESTRUIRSE
        $cantidadDeFuncionesDespuesDeDestruirseElObjeto = count(spl_autoload_functions());

        $this->assertSame($cantidadFuncionesAntesDe, $cantidadDeFuncionesDespuesDeDestruirseElObjeto);
    }

    public function testErroresAlRegistrarYDesregistrarElAutoload(): void
    {
        $this->assertTrue($this->autoload->registrar());
        $this->assertFalse($this->autoload->registrar());
        $this->assertSame(Autoload::ERROR_AUTOLOAD_REGISTRADO, $this->autoload->error());

        $this->assertTrue($this->autoload->desregistrar());
        $this->assertFalse($this->autoload->desregistrar());
        $this->assertSame(Autoload::ERROR_AUTOLOAD_NO_REGISTRADO, $this->autoload->error());
    }

    public function dataEspaciosDeNombres(): array
    {
        $carpeta = $this->createMock(Carpeta::class);
        return [['EspacioDeNombre', $carpeta]];
    }

    public function dataClaseInterfazTraitValidos(): array
    {
        return [
            ['MirenmenSoyUnRasgo', 'trait'],
            ['YoTengoMuchaClase', 'class'],
            ['SoyUnaCapa', 'interface']
        ];
    }

    public function dataCausantesDeErroresAlCargar(): array
    {
        return [
            [__CLASS__, true, false, Autoload::ERROR_OBJETO_EXISTENTE, ObjetoExistente::class],
            ['NoPasaNiElFiltro', false, false, Autoload::ERROR_EN_EL_FILTRO, CadenaInvalidaParaCargar::class],
            ['CargaBienPeroElObjetoNoExiste', true, true, Autoload::ERROR_OBJETO_INEXISTENTE, ObjetoInexistente::class],
            ['Namespace\NoReservado', true, false, Autoload::ERROR_NAMESPACE_INEXISTENTE, EspacioDeNombreInexistente::class]
        ];
    }

}
