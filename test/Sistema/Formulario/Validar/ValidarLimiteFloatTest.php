<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Datos\Errores\Mensajes\Error;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarLimiteFloat;
use PHPUnit\Framework\TestCase;

class ValidarLimiteFloatTest extends TestCase
{
    private Error $error;
    private Campo $campo;
    private ValidarLimiteFloat $validador;

    public function setUp(): void
    {
        $this->error     = $this->createMock(Error::class);
        $this->campo     = $this->createMock(Campo::class);
        $this->validador = new ValidarLimiteFloat($this->campo);

        $this->campo
            ->method('error')
            ->willReturn($this->error);
    }

    public function testImplementaLaInterfazValidable(): void
    {
        $this->assertInstanceOf(Validable::class, $this->validador);
    }

    public function testValidarDevuelveNullSiCampoContieneErrores(): void
    {
        $this->error
            ->expects($this->any())
            ->method('hay')
            ->willReturn(true);

        $this->assertNull($this->validador->validar());
    }

    /**
     * @dataProvider dataValoresDeCampoQueNoSonNumericos
     */
    public function testValidarDevuelveNullSiElValorDelCampoNoEsNumerico(mixed $valorDelCampo): void
    {
        $this->campo
            ->expects($this->any())
            ->method('valor')
            ->willReturn($valorDelCampo);

        $this->assertNull($this->validador->validar());
    }

    public function dataValoresDeCampoQueNoSonNumericos(): array
    {
        return [
            ['string'],
            [[PHP_FLOAT_MAX]],
        ];
    }

    /**
     * @dataProvider dataValoresYLimitesMinimos
     */
    public function testValidarValoresInferioresAlLimiteMinimo(float $valorDelCampo, float $limiteMinimo): void
    {
        $this->campo
            ->method('valor')
            ->willReturn($valorDelCampo);

        $this->error
            ->expects($this->any())
            ->method('codigo')
            ->with(Errores::ERROR_LIMITE_MINIMO_NO_ALCANZADO);

        $this->validador->minimo($limiteMinimo);
        $this->assertFalse($this->validador->validar());
    }

    public function dataValoresYLimitesMinimos(): array
    {
        return [
            [1.1, 1.2],
        ];
    }

    /**
     * @dataProvider dataValoresYLimitesMaximos
     */
    public function testValidarValoresSuperiorAlLimiteMaximo(float $valorDelCampo, float $limiteMaximo): void
    {
        $this->campo
            ->method('valor')
            ->willReturn($valorDelCampo);

        $this->error
            ->expects($this->any())
            ->method('codigo')
            ->with(Errores::ERROR_LIMITE_MAXIMO_EXCEDIDO);

        $this->validador->maximo($limiteMaximo);
        $this->assertFalse($this->validador->validar());
    }

    public function dataValoresYLimitesMaximos(): array
    {
        return [
            // Valor, Maximo
            [20.1, 10.1],
        ];
    }

    /**
     * @dataProvider dataValoresYSusLimitesValidos
     */
    public function testValidarDevuelveTrue(float $valorDelCampo, float $limiteMinimo, float $limiteMaximo): void
    {
        $this->campo
            ->method('valor')
            ->willReturn($valorDelCampo);

        $this->error
            ->expects($this->never())
            ->method('codigo');

        $this->error
            ->expects($this->never())
            ->method('mensaje');

        $this->validador->minimo($limiteMinimo);
        $this->validador->maximo($limiteMaximo);
        $this->assertTrue($this->validador->validar());
    }

    public function dataValoresYSusLimitesValidos(): array
    {
        return [
            // Valor, Limite Minimo, Limite Maximo
            [0.1, PHP_FLOAT_MIN, PHP_FLOAT_MAX],
            [-5.0, -10.0, -1.0],
            [1.0, 0.0, 2.0],
        ];
    }

}
