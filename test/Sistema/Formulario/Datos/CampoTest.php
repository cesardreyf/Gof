<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Datos;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Interfaz\Errores\Mensajes\Error;
use PHPUnit\Framework\TestCase;
use stdClass;

class CampoTest extends TestCase
{

    /**
     * @dataProvider dataClavesYTipos
     */
    public function testPasarClavePorConstructor(string $clave, int $tipo): void
    {
        $campo = new Campo($clave, $tipo);
        $this->assertSame($tipo, $campo->tipo());
        $this->assertSame($clave, $campo->clave());
    }

    public function dataClavesYTipos(): array
    {
        return [
            ['string', 0],
            ['int', 1],
            ['float', 2],
            ['array', 3],
        ];
    }

    public function testValorNuloAlCrearElCampo(): void
    {
        $tipo = 0;
        $clave = 'algo';
        $campo = new Campo($clave, $tipo);
        $this->assertNull($campo->valor());
    }

    /**
     * @dataProvider dataValoresDiferentes
     */
    public function testCambiarValoresMixtos(array $valores): void
    {
        $tipo = 0;
        $clave = 'algo';
        $campo = new Campo($clave, $tipo);
        $this->assertNull($campo->valor());

        $valorViejo = null;

        foreach( $valores as $nuevoValor ) {
            $campo->valor = $nuevoValor;
            $this->assertSame($nuevoValor, $campo->valor());
            $this->assertNotSame($valorViejo, $campo->valor());

            $valorViejo = $nuevoValor;
        }
    }

    public function dataValoresDiferentes(): array
    {
        return [
            [['algo', 123, 4.56, -789, -0.123, [], new stdClass()]]
        ];
    }

    public function testCambiarTipos(): void
    {
        $tipo = 0;
        $clave = 'algo';
        $campo = new Campo($clave, $tipo);
        $this->assertSame($tipo, $campo->tipo());

        $numeroNatural = 1;
        $campo->tipo = $numeroNatural;
        $this->assertSame($numeroNatural, $campo->tipo());

        $numeroEntero = -2;
        $campo->tipo = $numeroEntero;
        $this->assertSame($numeroEntero, $campo->tipo());
    }

    /**
     * @dataProvider dataClavesDiferentes
     */
    public function testCambiarClave(array $claves): void
    {
        $tipo = 0;
        $clave = 'algo';
        $campo = new Campo($clave, $tipo);
        $this->assertSame($clave, $campo->clave());

        $claveVieja = $clave;

        foreach( $claves as $nuevaClave ) {
            $campo->clave = $nuevaClave;
            $this->assertSame($nuevaClave, $campo->clave());
            $this->assertNotSame($claveVieja, $campo->clave());

            $claveVieja = $nuevaClave;
        }
    }

    public function dataClavesDiferentes(): array
    {
        return [
            [['y', 'vo', 'que', 'mira', 'bobo']]
        ];
    }

    public function testFuncionErrorDevuelveInstanciaDeError(): void
    {
        $campo = new Campo('algo', 0);
        $this->assertInstanceOf(Error::class, $campo->error());
    }

}
