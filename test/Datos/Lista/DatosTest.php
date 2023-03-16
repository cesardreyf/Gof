<?php

declare(strict_types=1);

namespace Test\Datos\Lista;

use Gof\Datos\Lista\Datos\ListaDeDatos;
use Gof\Interfaz\Lista;
use Gof\Interfaz\Lista\Datos;
use PHPUnit\Framework\TestCase;
use stdClass;

class DatosTest extends TestCase
{
    private $datos;

    public function setUp(): void
    {
        $this->datos = new ListaDeDatos();
    }

    public function testListaDeDatosVacioAlInstanciar(): void
    {
        $this->assertEmpty($this->datos->lista());
    }

    public function testImplementaLaInterfazCorrectamente(): void
    {
        $this->assertInstanceOf(Lista::class, $this->datos);
        $this->assertInstanceOf(Datos::class, $this->datos);
    }

    /**
     * @dataProvider dataDiferenteTipoDeDatosConSusClavesAsociadas
     */
    public function testAgregarDatosConClaves(string $clave, $dato): void
    {
        $this->assertSame($clave, $this->datos->agregar($dato, $clave));

        $listaEsperada = [$clave => $dato];
        $this->assertSame($listaEsperada, $this->datos->lista());
    }

    public function dataDiferenteTipoDeDatosConSusClavesAsociadas(): array
    {
        return [
            ['vector', []],
            ['cadena', 'tonto'],
            ['objeto', new stdClass()],
            ['enteros_positivos', 1234567],
            ['reales_negativos', 1.234567],
            ['funcion_lambda', function() {}]
        ];
    }

    /**
     * @dataProvider dataDatosApilablesSinClavesAsociadas
     */
    public function testAgregarDatosApilables(array $datos): void
    {
        $supuestoIdentificadorNumerico = 0;

        foreach( $datos as $dato ) {
            $this->assertEquals($supuestoIdentificadorNumerico, $this->datos->agregar($dato));

            $supuestoIdentificadorNumerico++;
        }

        $this->assertSame($datos, $this->datos->lista());
    }

    public function dataDatosApilablesSinClavesAsociadas(): array
    {
        return [
            [[false, true, 'dos', 3, 4.0]]
        ];
    }

    public function testAgregarUnDatoYaExistenteDevuelveNull(): void
    {
        $dato = 'cualquier_cosa';
        $otroDato = 'otro_dato_mas';
        $clave = 'una_clave_cualquiera';
        $identificador = $this->datos->agregar($dato, $clave);

        $this->assertSame($clave, $identificador);
        $this->assertNull($this->datos->agregar($otroDato, $clave));

        // Validando que no hubo un cambio de datos
        $listaDeDatosEsperada = [$clave => $dato];
        $this->assertSame($listaDeDatosEsperada, $this->datos->lista());
    }

    /**
     * @dataProvider dataDiferenteTipoDeDatosConSusClavesAsociadas
     */
    public function testRemoverDatosCorrectamente(string $clave, $dato): void
    {
        $identificador = $this->datos->agregar($dato, $clave);
        $this->assertArrayHasKey($identificador, $this->datos->lista());
        $this->assertTrue($this->datos->remover($identificador));
        $this->assertArrayNotHasKey($identificador, $this->datos->lista());
    }

    public function testRemoverDatoInexistenteDevuelveFalse(): void
    {
        $identificador = 'indentificador_inexistente_en_la_lista';
        $this->assertFalse($this->datos->remover($identificador));
    }

    /**
     * @dataProvider dataDatosCambiables
     */
    public function testCambiarDatos($clave, $viejoDato, $nuevoDato): void
    {
        $identificador = $this->datos->agregar($viejoDato, $clave);
        $this->assertTrue($this->datos->cambiar($identificador, $nuevoDato));

        $this->assertCount(1, $this->datos->lista());
        $this->assertTrue(in_array($nuevoDato, $this->datos->lista()));
    }

    public function dataDatosCambiables(): array
    {
        return [
            ['una_clave', 'valor_viejo', 'valor_nuevo'],
            [null, 12345, new stdClass()]
        ];
    }

    public function testCambiarDatosInexistenteDevuelveFalse(): void
    {
        $dato = 'da_igual';
        $identificador = 'indentificador_inexistente_en_la_lista';
        $this->assertFalse($this->datos->cambiar($identificador, $dato));
    }

    /**
     * @dataProvider dataDiferenteTipoDeDatosConSusClavesAsociadas
     */
    public function testObtenerDatos($clave, $dato): void
    {
        $identificador = $this->datos->agregar($dato, $clave);
        $this->assertNotNull($identificador);
        $this->assertSame($dato, $this->datos->obtener($identificador));
    }

    public function testObtenerDatosInexistentesDevuelveNull(): void
    {
        $identificador = 'indentificador_inexistente_en_la_lista';
        $this->assertNull($this->datos->obtener($identificador));
    }

    /**
     * @dataProvider dataConjuntoDeDatos
     */
    public function testMetodoListaDevuelveElConjuntoDeDatos(array $datos): void
    {
        $this->assertEmpty($this->datos->lista());

        foreach( $datos as $clave => $dato ) {
            $this->assertNotNull($this->datos->agregar($dato, (string)$clave));
        }

        $this->assertSame($datos, $this->datos->lista());
    }

    public function dataConjuntoDeDatos(): array
    {
        return [
            [['cadena' => 'CaDéN@', 'número' => 123, 4.5678, new stdClass(), 'llamaratras' => function() {}]]
        ];
    }

}
