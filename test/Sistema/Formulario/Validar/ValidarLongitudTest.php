<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Datos\Campo\Error;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarLongitud;
use PHPUnit\Framework\TestCase;

class ValidarLongitudTest extends TestCase
{
    private Error $error;
    private Campo $campo;
    private ValidarLongitud $longitud;

    public function setUp(): void
    {
        $this->error = $this->createMock(Error::class);
        $this->campo = $this->createMock(Campo::class);

        $this->campo
            ->method('error')
            ->willReturn($this->error);

        $this->longitud = new ValidarLongitud($this->campo);
    }

    public function testImplementaLaInterfazValidable(): void
    {
        $this->assertInstanceOf(Validable::class, $this->longitud);
    }

    public function testValidarDevuelveNullSiExistenErroresEnElCampo(): void
    {
        $this->error
            ->expects($this->any())
            ->method('hay')
            ->willReturn(true);

        $this->assertNull($this->longitud->validar());
    }

    /**
     * @dataProvider dataValoresYLongitudMinimasInvalidas
     */
    public function testValidarLongitudInferiorAlMinimo(mixed $valor, int $longitudMinimo): void
    {
        $this->campo
            ->method('valor')
            ->willReturn($valor);

        $this->error
            ->expects($this->any())
            ->method('codigo')
            ->with(Errores::ERROR_LONGITUD_MINIMO_NO_ALCANZADO);

        $this->longitud->minimo($longitudMinimo);
        $this->assertFalse($this->longitud->validar());
    }

    public function dataValoresYLongitudMinimasInvalidas(): array
    {
        return [
            ['abc', 4]
        ];
    }

    /**
     * @dataProvider dataValoresYLongitudMaximasInvalidas
     */
    public function testValidarLongitudSuperioAlMaximo(mixed $valor, int $longitudMaximo): void
    {
        $this->campo
            ->method('valor')
            ->willReturn($valor);

        $this->error
            ->expects($this->any())
            ->method('codigo')
            ->with(Errores::ERROR_LONGITUD_MAXIMO_EXCEDIDO);

        $this->longitud->maximo($longitudMaximo);
        $this->assertFalse($this->longitud->validar());
    }

    public function dataValoresYLongitudMaximasInvalidas(): array
    {
        return [
            ['abc', 1],
            ['abc', 2],
        ];
    }

    /**
     * @dataProvider dataValoresYLongitudValidas
     */
    public function testValidarDevuelveTrue(mixed $valor, int $longitudMinimo, int $longitudMaximo): void
    {
        $this->campo
            ->expects($this->any())
            ->method('valor')
            ->willReturn($valor);

        $this->error
            ->expects($this->any())
            ->method('hay')
            ->willReturn(false);

        $this->error
            ->expects($this->never())
            ->method('codigo');

        $this->error
            ->expects($this->never())
            ->method('mensaje');

        $this->longitud->minimo($longitudMinimo);
        $this->longitud->maximo($longitudMaximo);
        $this->assertTrue($this->longitud->validar());
    }

    public function dataValoresYLongitudValidas(): array
    {
        return [
            ['', 0, 0],
            ['abc', 1, 3],
            ['abc', 0, 0],
            ['abcd', 3, 1],
            ['áéíóú', 5, 5],
        ];
    }

}
