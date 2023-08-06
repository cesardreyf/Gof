<?php

namespace Gof\Gestor\Dependencias\Simple;

use Gof\Gestor\Dependencias\Simple\Excepcion\MetodoInexistente;
use Gof\Gestor\Dependencias\Simple\Excepcion\MetodoReservado;

/**
 * Gestor de dependencias con soporte para métodos mágicos
 *
 * A diferencia del gestor de dependencias base este puede almacejar un alias
 * de las clases guardadas para ser accedidas como si fueran métodos propios
 * del gestor de dependencias.
 *
 * @package Gof\Gestor\Dependencias\Simple
 */
class DependenciasMagicas extends Dependencias
{
    /**
     * Error lanzado cuando el método ya está reservado
     *
     * @var int
     */
    const ERROR_METODO_RESERVADO = 1001;

    /**
     * Lista de nombres de métodos reservados
     *
     * Aquí se cachean los nombres de los métodos públicos del gestor que no
     * pueden ser usados para asociarlos a dependencias/clases.
     *
     * @var string[]
     */
    private array $metodosPublicos;

    /**
     * Lista de métodos reservados
     *
     * Array cuyas claves son los métodos asociados y el valor las clases reservadas.
     *
     * @var array<string, string>
     */
    private array $metodos = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->metodosPublicos = get_class_methods(self::class);
    }

    /**
     * Alias para obtener una dependencia
     *
     * Si existe registrado una clase Ejemplo y se asocia el método 'ejemplo'
     * la instancia es accesible a través de: $dependencias->ejemplo();
     * Equivale a usar $dependencias->obtener('Ejemplo').
     *
     * Si el método no está reservado, es un método público o simplemente no
     * existe lanzará una excepción MetodoInexistente, independientemente de
     * la configuración del gestor.
     *
     * @see Dependencias::obtener()
     *
     * @throws MetodoInexistente
     */
    public function __call(string $metodo, array $argumentos)
    {
        if( isset($this->metodos[$metodo]) ) {
            return $this->obtener($this->metodos[$metodo]);
        }
        throw new MetodoInexistente($metodo);
    }

    /**
     * Asocia un nombre de método a una clase reservada
     *
     * @param string $metodo Nombre del método a asociar.
     * @param string $clase  Clase o interfaz reservada.
     *
     * @return bool Devuelve el estado de la operación.
     *
     * @throws MetodoReservado si el nombre del método ya está reservado.
     *
     * @see DependenciasMagicas::__call()
     */
    public function asociarMetodo(string $metodo, string $clase): bool
    {
        if( $this->metodoEstaReservado($metodo) || $this->claseNoEstaReservada($clase) ) {
            return false;
        }
        $this->metodos[$metodo] = $clase;
        return true;
    }

    /**
     * @inheritDoc
     */
    public function remover(string $nombre): bool
    {
        if( parent::remover($nombre) === true ) {
            $this->metodos = array_diff($this->metodos, [$nombre]);
            return true;
        }
        return false;
    }

    /**
     * Obtiene la lista de métodos reservados
     *
     * Obtiene un array donde las claves son los nombres de los métodos
     * reservados y sus valores la clase o interfaz asociada.
     *
     * @return array<string, string>
     */
    public function metodosReservados(): array
    {
        return $this->metodos;
    }

    /**
     * Verifica si el método está reservado
     *
     * @param string $metodo Nombre del método.
     *
     * @return bool Devuelve el estado de la operación.
     *
     * @access protected
     */
    protected function metodoEstaReservado(string $metodo): bool
    {
        if( isset($this->metodos[$metodo]) || in_array($metodo, $this->metodosPublicos) ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
                throw new MetodoReservado($metodo);
            }
            $this->agregarError(self::ERROR_METODO_RESERVADO);
            return true;
        }
        return false;
    }

}
