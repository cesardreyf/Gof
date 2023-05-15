<?php

declare(strict_types=1);

namespace Test\Gestor\Redireccion;

use Gof\Gestor\Redireccion\Redirigir;
use PHPUnit\Framework\TestCase;

/**
 * Lo dejo porque ahora mismo no me está funcionando bien el tema de obtener los headers
 *
 * Para cuando tenga más tiempo retorno y le hecho un ojo a esta mierda.
 *
 * Error que me aparecía:
 *
 *  Cannot modify header information - headers already sent by (output started at phar:///usr/bin/phpunit/phpunit/Util/Printer.php:82)
 */
class RedirigirTest extends TestCase
{
    const DIRECCION_BASE = 'http://localhost';

    private Redirigir $redirigir;

    public function setUp(): void
    {
        $this->redirigir = new Redirigir(self::DIRECCION_BASE);
    }

    public function testDireccionBase(): void
    {
        $nuevaDirecionBase = 'http://nueva.direccion.web/';
        $this->assertSame(self::DIRECCION_BASE, $this->redirigir->base());
        $this->assertSame($nuevaDirecionBase, $this->redirigir->base($nuevaDirecionBase));
    }

    public function testRedirigrALaPaginaPrincipal(): void
    {
        $temporal = true;
        $direccion = 'algun/lugar';

        ob_start();
        $this->redirigir->a($direccion, $temporal);
        ob_get_clean();

        $headers = headers_list();
        $location = false;

        foreach( $headers as $header ) {
            if( strpos($header, 'Location:') !== false ) {
                $location = true;

                $locacionEsperado = 'Location: ' . self::DIRECCION_BASE . $direccion;
                $this->assertEquals($locacionEsperado, $header);
            }
        }

        $this->assertTrue($location);
    }

}
