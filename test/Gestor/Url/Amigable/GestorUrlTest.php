<?php

declare(strict_types=1);

namespace Test\Gestor\Url\Amigable;

use Gof\Gestor\Url\Amigable\GestorUrl;
use PHPUnit\Framework\TestCase;

class GestorUrlTest extends TestCase
{

    public function testPeticionVaciaDevuelveUnaListaVacia(): void
    {
        $peticion = '';
        $separador = '/';
        $url = new GestorUrl($separador);
        $this->assertEmpty($url->lista());
    }

    /**
     *  @dataProvider dataPeticionesJuntoConSeparador
     *  @dataProvider dataPeticionSanitizada
     *  @dataProvider dataPeticionNoLimpiada
     */
    public function testDividirLaPeticionSegunElSeparador(string $peticion, string $separador, array $resultadoEsperado): void
    {
        $url = new GestorUrl($separador);
        $url->procesar($peticion);
        $this->assertNotEmpty($url->lista());
        $this->assertSame($resultadoEsperado, $url->lista());
    }

    public function dataPeticionesJuntoConSeparador(): array
    {
        return [
            [
                'algo/bobo/como/este/dedo/fofo', '/',
                ['algo', 'bobo', 'como', 'este', 'dedo', 'fofo']
            ],
            [
                'las-malvinas-son-argentinas', '-',
                ['las', 'malvinas', 'son', 'argentinas']
            ],
            [
                'messi/es-mejor-que/maradona', '/',
                ['messi', 'es-mejor-que', 'maradona']
            ]
        ];
    }

    public function dataPeticionNoLimpiada(): array
    {
        return [
            [
                '///algo/bobo///', '/',
                ['algo', 'bobo']
            ],
            [
                '----menos-es-mas--', '-',
                ['menos', 'es', 'mas']
            ],
            [
                '++algo+++no-va++++bien++', '+',
                ['algo', '', '', 'no-va', '', '', '', 'bien']
            ]
        ];
    }

    public function dataPeticionSanitizada(): array
    {
        return [
            [
                "Que+es+esto?+\x99", '+',
                ['Que', 'es', 'esto?', '']
            ]
        ];
    }

}
