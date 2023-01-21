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

    /**
     *  @dataProvider data_objetivosMultiples
     *  @dataProvider data_objetivosInexistentes
     *  @dataProvider data_paginasPrincipalesImplicitas
     */
    public function test_enrutadorConMultiplesDatas(string $nombreDeLaClase, array $objetivos): void
    {
        $paginasObjetivos = new ListaDeTextos($objetivos);
        $enrutador = new Enrutador($paginasObjetivos, $this->paginasDisponibles, $this->paginaPrincipal, $this->paginaInexistente);
        $this->assertStringContainsStringIgnoringCase($nombreDeLaClase, $enrutador->nombreClase());
    }

    public function data_objetivosMultiples(): array
    {
        return [
            ['perfil\usuario',       ['perfil', 'usuario']],
            ['perfil\editar\nombre', ['perfil', 'editar', 'nombre']]
        ];
    }

    public function data_paginasPrincipalesImplicitas(): array
    {
        return [
            ["perfil\\{$this->paginaPrincipal}", ['perfil']],
            ["pagina_con_index\\$this->paginaPrincipal", ['pagina_con_index']]
        ];
    }

    public function data_objetivosInexistentes(): array
    {
        return [
            ["{$this->paginaInexistente}", ['objetivo_inexistente_en_paginas_disponibles']],
            ["{$this->paginaInexistente}", ['perfil', 'perfil_existe_pero_esta_subpagina_no']]
        ];
    }

}
