<?php

declare(strict_types=1);

namespace Test\Sistema\MVC\Rutas;

use Gof\Contrato\Enrutador\Enrutador;
use Gof\Datos\Lista\Texto\ListaDeTextos;
use Gof\Gestor\Url\Amigable\GestorUrl;
use Gof\Sistema\MVC\Aplicacion\DAP\N1 as DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Rutas\Configuracion;
use Gof\Sistema\MVC\Rutas\Excepcion\ConfiguracionInexistente;
use Gof\Sistema\MVC\Rutas\Excepcion\EnrutadorInexistente;
use Gof\Sistema\MVC\Rutas\Rutas;
use Gof\Sistema\MVC\Rutas\Simple\Gestor as GestorSimple;
use PHPUnit\Framework\TestCase;

class RutasTest extends TestCase
{
    private DAP $dap;
    private Rutas $rutas;
    private Configuracion $configuracion;

    public function setUp(): void
    {
        $this->dap = new DAP();
        $this->rutas = new Rutas();
        $this->configuracion = new Configuracion();
        $this->rutas->configuracion = $this->configuracion;
    }

    public function testFuncionamientoEsperado()
    {
        $this->configuracion->separador = '/';
        $this->configuracion->urlClave = '__peticion';
        $_GET[$this->configuracion->urlClave] = 'recurso1/recurso2';

        $enrutador = $this->createMock(Enrutador::class);
        $this->configuracion->enrutador = $enrutador;

        $separador = $this->configuracion->separador;
        $solicitud = $_GET[$this->configuracion->urlClave];

        $listaDeRecursos = ['recurso1', 'recurso2'];
        $this->dap->solicitud->lista = $listaDeRecursos;

        $resto = ['param1', 'param2'];
        $nombreClase = 'Controlador\Index';

        $this->assertEmpty($this->dap->parametros);
        $this->assertEmpty($this->dap->controlador);

        $enrutador
            ->expects($this->once())
            ->method('procesar')
            ->with(new ListaDeTextos($listaDeRecursos));
        $enrutador
            ->expects($this->once())
            ->method('resto')
            ->willReturn($resto);
        $enrutador
            ->expects($this->once())
            ->method('nombreClase')
            ->willReturn($nombreClase);

        $this->rutas->ejecutar($this->dap);
        $this->assertSame($resto, $this->dap->parametros);
        $this->assertSame($nombreClase, $this->dap->controlador);
    }

}
