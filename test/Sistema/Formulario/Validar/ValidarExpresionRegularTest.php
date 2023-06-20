<?php

declare(strict_types=1);

namespace Test\Sistema\Formulario\Validar;

use Gof\Interfaz\Errores\Mensajes\Error;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarExpresionRegular;
use PHPUnit\Framework\TestCase;

class ValidarExpresionRegularTest extends TestCase
{
    private Error $error;
    private Campo $campo;
    private ValidarExpresionRegular $regex;

    public function setUp(): void
    {
        $this->error = $this->createMock(Error::class);
        $this->campo = $this->createMock(Campo::class);

        $this->campo
            ->method('error')
            ->willReturn($this->error);

        $this->regex = new ValidarExpresionRegular($this->campo);
    }

    public function testImplementaValidable(): void
    {
        $this->assertInstanceOf(Validable::class, $this->regex);
    }

    /**
     * @dataProvider dataExpresionesRegularesYValoresDeCamposValidos
     */
    public function testAgregarExpresionesRegularesYValidarDevuelveTrue(string $valorDelCampo, string $expresionRegular): void
    {
        $this->error
            ->expects($this->any())
            ->method('hay')
            ->willReturn(false);

        $this->error
            ->expects($this->never())
            ->method('mensaje');

        $this->error
            ->expects($this->never())
            ->method('mensaje');

        $this->campo
            ->expects($this->any())
            ->method('valor')
            ->willReturn($valorDelCampo);

        $this->regex->agregar($expresionRegular);
        $this->assertTrue($this->regex->validar());
    }

    public function dataExpresionesRegularesYValoresDeCamposValidos(): array
    {
        return [
            ['abcdefghijklmnopqrstuvwxyz', "/^[a-z]+$/"],
            ['áéíóúñüÁÉÍÓÚÑÜ', "/^[\p{L}\p{M}]+$/u"],
            ['0123456789', "/^[0-9]+$/"],
        ];
    }

    /**
     * @dataProvider dataValoresExpresionesRegularesYMensajesDeErrores
     */
    public function testAgregarExpresionesRegularesYValidarDevuelveFalse(mixed $valorDelCampo, string $expresionRegular, ?string $mensajeDeErrorEsperado): void
    {
        $this->campo
            ->method('valor')
            ->willReturn($valorDelCampo);

        $this->error
            ->expects($this->any())
            ->method('hay')
            ->willReturn(false);

        $this->error
            ->expects($this->any())
            ->method('codigo')
            ->with(Errores::ERROR_REGEX_CADENA_INVALIDA);

        $this->error
            ->expects($this->any())
            ->method('mensaje')
            ->with($mensajeDeErrorEsperado ?? ValidarExpresionRegular::CADENA_INVALIDA);

        $this->regex->agregar($expresionRegular, $mensajeDeErrorEsperado);
        $this->assertFalse($this->regex->validar());
    }

    public function dataValoresExpresionesRegularesYMensajesDeErrores(): array
    {
        return [
            [
                'árbol',
                '/^[a-z]+$/',
                'Solo se permiten caracteres ASCII'
            ],
            [
                'mensaje de error por defecto',
                '/^[0-9]+$/',
                null
            ],
        ];
    }

    public function testValidarDevuelveNullSiExistenErroresPreviosEnElCampo(): void
    {
        $this->error
            ->expects($this->once())
            ->method('hay')
            ->willReturn(true);

        $this->error
            ->expects($this->never())
            ->method('codigo');

        $this->error
            ->expects($this->never())
            ->method('mensaje');

        $this->assertNull($this->regex->validar());
    }

}
