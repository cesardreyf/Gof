<?php

declare(strict_types=1);

use Gof\Datos\Archivos\Archivo;
use Gof\Interfaz\Archivos\Archivo as IArchivo;
use PHPUnit\Framework\TestCase;

class ArchivoTest extends TestCase
{

    public function test_archivoExistente(): void
    {
        $archivo = new Archivo(__FILE__);
        $this->assertSame(__FILE__, $archivo->ruta());
    }

    public function test_archivoInexistente(): void
    {
        $this->expectException(Exception::class);
        new Archivo(__DIR__ . '/este_archivo_claramente_no_existe');
    }

    public function test_carpetaEnLugarDeUnArchivo(): void
    {
        $this->expectException(Exception::class);
        new Archivo(__DIR__);
    }

}
