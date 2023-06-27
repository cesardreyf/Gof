<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Datos\Campo\Error;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarLimiteInt;
use PHPUnit\Framework\TestCase;

class ValidarLimiteIntTest extends TestCase
{
    private Error $error;
    private Campo $campo;
    private ValidarLimiteInt $validador;

    public function setUp(): void
    {
        $this->error     = $this->createMock(Error::class);
        $this->campo     = $this->createMock(Campo::class);
        $this->validador = new ValidarLimiteInt($this->campo);

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
            [[PHP_INT_MAX]],
        ];
    }

    /**
     * @dataProvider dataValoresYLimitesMinimos
     */
    public function testValidarValoresInferioresAlLimiteMinimo(int $valorDelCampo, int $limiteMinimo): void
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
            [10, 20],
            [-20, -10],
        ];
    }

    /**
     * @dataProvider dataValoresYLimitesMaximos
     */
    public function testValidarValoresSuperiorAlLimiteMaximo(int $valorDelCampo, int $limiteMaximo): void
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
            [20, 19],
            [-10, -11],
        ];
    }

    /**
     * @dataProvider dataValoresYSusLimitesValidos
     */
    public function testValidarDevuelveTrue(int $valorDelCampo, int $limiteMinimo, int $limiteMaximo): void
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
            [0, PHP_INT_MIN, PHP_INT_MAX],
            [-5, -10, -1],
            [1, 0, 2],
        ];
    }

}
