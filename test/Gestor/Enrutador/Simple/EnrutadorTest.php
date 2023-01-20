<?php

declare(strict_types=1);

use Gof\Datos\Lista\Texto\ListaDeTextos;
use Gof\Gestor\Enrutador\Simple\Enrutador;
use PHPUnit\Framework\TestCase;

class EnrutadorTest extends TestCase
{
    private $paginaPrincipal;
    private $paginaInexistente;
    private $paginasDisponibles;

    public function setUp(): void
    {
        $this->paginaPrincipal = 'pagina_principal';
        $this->paginaInexistente = 'pagina_inexistente';

        $this->paginasDisponibles = [
            'una_pagina',
            'otra_pagina',
            'perfil' => [
                'usuario',
                'editar' => [
                    'nombre'
                ]
            ],
            'pagina_con_index' => [
            ]
        ];
    }

    public function test_enrutadorSinObjetivos(): void
    {
        $paginasObjetivos = new ListaDeTextos([]);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase($this->paginaPrincipal, $enrutador->nombreClase(), 'Si la lista de objetivos está vacía el nombre de la clase debería ser igual a la página principal');
    }

    public function test_enrutadorConObjetivosMultiples(): void
    {
        $paginasObjetivos = new ListaDeTextos(['perfil', 'usuario']);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase('perfil\usuario', $enrutador->nombreClase());

        $paginasObjetivos = new ListaDeTextos(['perfil', 'editar', 'nombre']);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase('perfil\editar\nombre', $enrutador->nombreClase());
    }

    public function test_enrutadorConIndiceImplicito(): void
    {
        $paginasObjetivos = new ListaDeTextos(['perfil']);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase('perfil\\' . $this->paginaPrincipal, $enrutador->nombreClase());

        $paginasObjetivos = new ListaDeTextos(['pagina_con_index']);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase('pagina_con_index\\' . $this->paginaPrincipal, $enrutador->nombreClase());
    }

    public function test_enrutadorConObjetivoInexistente(): void
    {
        $paginasObjetivos = new ListaDeTextos(['objetivo_inexistente_en_paginas_disponibles']);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase($this->paginaInexistente, $enrutador->nombreClase(), 'Si el objetivo no se encuentra en la lista de disponibles el nombre del controlador debe ser $paginaInexistente');

        $paginasObjetivos = new ListaDeTextos(['perfil', 'perfil_existe_pero_esta_subpagina_no']);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase($this->paginaInexistente, $enrutador->nombreClase(), 'Si el objetivo no se encuentra en la lista de disponibles el nombre del controlador debe ser $paginaInexistente');
    }

}
