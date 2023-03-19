<?php

declare(strict_types=1);

namespace Test\Sistema\Reportes\Plantilla;

use Gof\Sistema\Reportes\Interfaz\Plantilla;
use Gof\Sistema\Reportes\Plantilla\Errores;
use PHPUnit\Framework\TestCase;
use stdClass;
use Exception;

class ErroresTest extends TestCase
{
    protected $plantilla;

    public function setUp(): void
    {
        $this->plantilla = new Errores();
    }

    public function testImplementaInterfaz(): void
    {
        $this->assertInstanceOf(Plantilla::class, $this->plantilla);
    }

    public function testMensajeVacioAlInstanciarLaClase(): void
    {
        $this->assertEmpty($this->plantilla->mensaje());
    }

    public function testErrorDeTipoDeDatosAlTraducir(): void
    {
        $this->assertFalse($this->plantilla->traducir(new Exception()));
    }

    /**
     *  @dataProvider dataJesusTePide
     */
    public function testTipoDeDatosArray(array $datos): void
    {
        $this->assertArrayHasKey('type',    $datos);
        $this->assertArrayHasKey('line',    $datos);
        $this->assertArrayHasKey('file',    $datos);
        $this->assertArrayHasKey('message', $datos);

        $this->assertTrue($this->plantilla->traducir($datos));
        $mensajeFinal = $this->plantilla->mensaje();

        $this->assertStringContainsString((string)$datos['line'], $mensajeFinal);
        $this->assertStringContainsString($datos['file'],         $mensajeFinal);
        $this->assertStringContainsString($datos['message'],      $mensajeFinal);
    }

    /**
     *  @dataProvider dataDatosInvalidos
     */
    public function testDatosConClavesValidosPeroValoresErroneos(array $datos): void
    {
        $this->assertFalse($this->plantilla->traducir($datos));
    }

    /**
     *  @dataProvider dataTraduccionDeTypeIntToString
     */
    public function testTraduccionDelTypeDeDatos(int $typeInt, string $typeString): void
    {
        $datos = ['type' => $typeInt, 'line' => 0, 'file' => '', 'message' => ''];
        $this->plantilla->traducir($datos);
        $mensajeFinal = $this->plantilla->mensaje();
        $this->assertStringContainsString($typeString, $mensajeFinal);
    }

    public function testTypeInexistenteDevuelveResultadoFalse(): void
    {
        $datos = ['type' => 3, 'line' => 0, 'file' => '', 'message' => ''];
        $this->assertFalse($this->plantilla->traducir($datos));
    }

    /**
     *  @dataProvider dataJesusTePide
     */
    public function testMensajesDiferentesEnTraduccionesDiferentes(array $datos): void
    {
        $otrosDatos = [
            'type'    => E_ERROR,
            'line'    => 1816,
            'file'    => 'República',
            'message' => 'El 21% sigue siendo poco'
        ];

        $this->plantilla->traducir($datos);
        $mensajeUno = $this->plantilla->mensaje();

        $this->plantilla->traducir($otrosDatos);
        $mensajeDos = $this->plantilla->mensaje();

        $this->assertNotSame($mensajeUno, $mensajeDos);
    }

    public function dataJesusTePide(): array
    {
        return [
            [
                [
                    'type'    => E_ERROR,
                    'line'    => 666,
                    'file'    => 'Biblia',
                    'message' => 'El 10% es poco, creo'
                ]
            ]
        ];
    }

    public function dataDatosInvalidos(): array
    {
        return [
            [
                [
                    'type'    => 'esto debería ser un Int',
                    'line'    => 'y esto más de lo mismo!',
                    'file'    => array('¿soy un archivo?'),
                    'message' => new stdClass()
                ]
            ]
        ];
    }

    public function dataTraduccionDeTypeIntToString(): array
    {
        return [
            [E_ERROR,             'E_ERROR'],
            [E_WARNING,           'E_WARNING'],
            [E_PARSE,             'E_PARSE'],
            [E_NOTICE,            'E_NOTICE'],
            [E_CORE_ERROR,        'E_CORE_ERROR'],
            [E_CORE_WARNING,      'E_CORE_WARNING'],
            [E_COMPILE_ERROR,     'E_COMPILE_ERROR'],
            [E_COMPILE_WARNING,   'E_COMPILE_WARNING'],
            [E_USER_ERROR,        'E_USER_ERROR'],
            [E_USER_WARNING,      'E_USER_WARNING'],
            [E_USER_NOTICE,       'E_USER_NOTICE'],
            [E_STRICT,            'E_STRICT'],
            [E_RECOVERABLE_ERROR, 'E_RECOVERABLE_ERROR'],
            [E_DEPRECATED,        'E_DEPRECATED'],
            [E_USER_DEPRECATED,   'E_USER_DEPRECATED']
        ];
    }

}
