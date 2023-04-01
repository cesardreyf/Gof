<?php

declare(strict_types=1);

namespace Test\Sistema\Propiedades\Simple\Operaciones;

use Gof\Gestor\Acciones\Interfaz\Accion;
use Gof\Gestor\Propiedades\Propiedad;
use Gof\Interfaz\Errores\Errores;
use Gof\Sistema\Propiedades\Simple\Operaciones\OperacionSimple;
use PHPUnit\Framework\TestCase;
use stdClass;

class OperacionSimpleTest extends TestCase
{
    private Accion $accion;
    private Propiedad $propiedades;
    private OperacionSimple $operacion;

    public function setUp(): void
    {
        $this->accion = $this->createMock(Accion::class);
        $this->propiedades = $this->createMock(Propiedad::class);
        $this->operacion = new OperacionSimple($this->propiedades, $this->accion);
    }

    public function testInstanciasDeMetodos(): void
    {
        $this->assertInstanceOf(Propiedad::class, $this->operacion->propiedades());
        $this->assertInstanceOf(Errores::class, $this->operacion->errores());
    }

    /**
     * @dataProvider dataParaOperarDeUnoEnUno
     */
    public function testOperarConUnaListaDePropiedadesDeUnSoloElemento(mixed $propiedad, string $identificador, mixed $resultadoAccionar, bool $resultadoOperar): void
    {
        $this->propiedades
             ->method('lista')
             ->willReturn(
                 [$identificador => $propiedad]
             );

        $this->accion
             ->expects($this->once())
             ->method('accionar')
             ->with($propiedad, $identificador)
             ->willReturn($resultadoAccionar);

        $this->assertSame($resultadoOperar, $this->operacion->operar());

        if( $resultadoAccionar !== 0 ) {
            $this->assertTrue($this->operacion->errores()->hay());
            $this->assertSame($resultadoAccionar, $this->operacion->errores()->error());
        }
    }

    public function dataParaOperarDeUnoEnUno(): array
    {
        return [
        //  Propiedad | Identificador | ResultadoDelAccionar | ResultadoEsperadoAlOperar
            [123, 'integer', 0, true],
            [123, 'da_igual', 1, false],
        ];
    }

    /**
     * @dataProvider dataListaDePropiedades
     */
    public function testOperarRecorreLaListaDePropiedades(array $propiedades): void
    {
        $sinErrores = 0;
        $nPropiedades = count($propiedades);

        $this->propiedades
             ->method('lista')
             ->willReturn($propiedades);

        $this->accion
             ->expects($this->exactly($nPropiedades))
             ->method('accionar')
             ->willReturn($sinErrores);

        $this->assertTrue($this->operacion->operar());
    }

    /**
     * @dataProvider dataListaDePropiedades
     */
    public function testOperarDevuelveFalseSiAlAccionarSeDevuelveUnEnteroDiferenteDeCero(array $propiedades): void
    {
        $this->propiedades
             ->method('lista')
             ->willReturn($propiedades);

        $cantidadDePropiedades = count($propiedades);
        $resultados = range(1, $cantidadDePropiedades);

        $this->accion
             ->expects($this->exactly($cantidadDePropiedades))
             ->method('accionar')
             ->will($this->onConsecutiveCalls(...$resultados));

        $this->assertFalse($this->operacion->operar());
        $this->assertTrue($this->operacion->errores()->hay());

        $listaDeErroresEsperados = array_combine(array_keys($propiedades), $resultados);
        $this->assertSame($listaDeErroresEsperados, $this->operacion->errores()->lista());
    }

    public function dataListaDePropiedades(): array
    {
        return [
            [[
                'integer' => 12345,
                'float' => 1.2345,
                'bool' => true,
                'null' => null,
                'object' => new stdClass()
            ]]
        ];
    }

    /**
     * @dataProvider dataPropiedadesDiferentesDelTipoInteger
     */
    public function testErrorAlDevolverUnValorDiferenteDelTipoInteger(array $propiedades): void
    {
        $this->propiedades
             ->method('lista')
             ->willReturn($propiedades);

        $this->accion
             ->method('accionar')
             ->will($this->returnArgument(0));

        $this->assertFalse($this->operacion->operar());

        $listaDeErroresEsperado = array_fill_keys(array_keys($propiedades), OperacionSimple::RESULTADO_INESPERADO);
        $this->assertSame($listaDeErroresEsperado, $this->operacion->errores()->lista());
    }

    public function dataPropiedadesDiferentesDelTipoInteger(): array
    {
        return [
            [[
                'null' => null,
                'bool' => true,
                'float' => 2.13149,
                'string' => 'algo bobo',
                'object' => new stdClass()
            ]]
        ];
    }

    public function testUnResultadoPrevioNoInfluyeEnElSiguienteResultadoAlOperar(): void
    {
        $sinErrores = 0;
        $conErrores = 1; // ≠ 0

        $propiedad = 'valor';
        $identificador = 'clave';

        $this->propiedades
             ->method('lista')
             ->willReturn([$identificador => $propiedad]);

        $this->accion
             ->expects($this->exactly(2))
             ->method('accionar')
             ->with($propiedad, $identificador)
             ->will($this->onConsecutiveCalls($conErrores, $sinErrores));

        $this->assertEmpty($this->operacion->errores()->lista());
        $this->assertFalse($this->operacion->operar());

        $this->assertNotEmpty($this->operacion->errores()->lista());
        $this->assertTrue($this->operacion->operar(), 'El error anterior no debería influir en la siguiente operación válida');
    }

}
