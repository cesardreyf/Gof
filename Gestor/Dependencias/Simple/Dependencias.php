<?php

namespace Gof\Gestor\Dependencias\Simple;

use Gof\Contrato\Dependencias\Dependencias as IDependencias;
use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Datos\Errores\ErrorNumerico;
use Gof\Gestor\Dependencias\Simple\Excepcion\ClaseInexistente;
use Gof\Gestor\Dependencias\Simple\Excepcion\ClaseNoReservada;
use Gof\Gestor\Dependencias\Simple\Excepcion\ClaseReservada;
use Gof\Gestor\Dependencias\Simple\Excepcion\DependenciasInexistentes;
use Gof\Gestor\Dependencias\Simple\Excepcion\ObjetoNoCorrespondido;
use Gof\Gestor\Dependencias\Simple\Excepcion\SinPermisosParaCambiar;
use Gof\Gestor\Dependencias\Simple\Excepcion\SinPermisosParaDefinir;
use Gof\Gestor\Dependencias\Simple\Excepcion\SinPermisosParaRemover;
use Gof\Interfaz\Errores\Errores;

/**
 * Gestor de inyección de dependencias
 *
 * Clase que ofrece una "alternativa" al patrón Singleton.
 *
 * @package Gof\Gestor\Dependencias\Simple
 */
class Dependencias implements IDependencias
{
    // const PERMITIR_AGREGAR = 1;

    /**
     * @var int Indica que se puede modificar las instancias de las clases
     */
    const PERMITIR_CAMBIAR = 2;

    /**
     * @var int Indica que se pueden eliminar las clases y sus objetos
     */
    const PERMITIR_REMOVER = 4;

    /**
     * @var int Indica que se pueden definir las clases
     */
    const PERMITIR_DEFINIR = 8;

    /**
     * @var int Indica que se llame a la función que define el objeto justamente después de agregarlo
     */
    const INSTANCIAR_AL_AGREGAR = 16;

    /**
     * @var int Indica que lance excepciones cuando detecte un error
     */
    const LANZAR_EXCEPCION = 32;

    /**
     * @var int Error que indica que la clase no está reservada
     */
    const ERROR_CLASE_INEXISTENTE = 100;

    /**
     * @var int Error que indica que el nombre de la clase/interfaz ya está reservada
     */
    const ERROR_CLASE_RESERVADA = 110;

    /**
     * @var int Error que indica que la clase no está reservada
     */
    const ERROR_CLASE_NO_RESERVADA = 111;

    /**
     * @var int Error que indica que el objeto devuelto por la función registrada no corresponde con la clase/interfaz
     */
    const ERROR_OBJETO_NO_CORRESPONDIDO = 200;

    /*
     * @var int Indica que no se tiene permisos para agregar
     */
    // const ERROR_SIN_PERMISOS_PARA_AGREGAR = 300;

    /**
     * @var int Indica que no se tiene permisos para cambiar
     */
    const ERROR_SIN_PERMISOS_PARA_CAMBIAR = 301;

    /**
     * @var int Indica que no se tiene permisos para remover
     */
    const ERROR_SIN_PERMISOS_PARA_REMOVER = 302;

    /**
     * @var int Indica que no se tiene permisos para remover
     */
    const ERROR_SIN_PERMISOS_PARA_DEFINIR = 303;

    /**
     * @var array<string, callable> Lista de clases
     */
    private $clases;

    /**
     * @var ErrorNumerico Lista de errores
     */
    private $errores;

    /**
     * @var MascaraDeBits Configuración interna
     */
    private $configuracion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->errores = new ErrorNumerico();
        $this->configuracion = new MascaraDeBits();
    }

    /**
     * Agrega una clase a la lista de dependencias disponibles
     *
     * Inserta una función anónima que será llamada una sola vez para obtener la instancia
     * del objeto en cuestión. El objeto debe ser una instancia de la clase o implementar
     * la interfaz indicada en el parámetro $nombre.
     *
     * Si la función no retorna ningún valor o devuelve un tipo de dato diferente a la clase
     * o interfaz esperada, al llamar al método Dependencias::obtener() se devolverá un valor
     * null o se lanzará una excepción, según la configuración.
     *
     * El nombre de la clase, o interfaz, será usada como clave asociada al objeto dentro de
     * la lista interna del gestor. Para obtener la instancia deberá usarse el mismo nombre.
     *
     * Si ocurre un error la función devolverá un valor **false**. Para obtener más
     * información sobre el error ocurrido vea Dependencias::errores().
     *
     * Errores generados por esta función:
     *
     * Dependencias::ERROR_CLASE_INEXISTENTE: si el nombre de la clase no corresponde con
     * ninguna clase o interfaz existente (cargada).
     *
     * Dependencias::ERROR_CLASE_RESERVADA: si ya se registró previamente un objeto con el
     * mismo nombre de la clase o interfaz. Para cambiar el objeto de una clase o interfaz
     * ya reservada utilice el método Dependencias::cambiar().
     *
     * Dependencias::ERROR_SIN_PERMISOS_PARA_AGREGAR: si está desactivado en configuración
     * Dependencias::PERMITIR_AGREGAR.
     *
     * @param string   $nombre    Nombre de la clase o la interfaz del objeto
     * @param callable $invocador Función que debe retornar el objeto
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     *
     * @see Dependencias::obtener() para más información sobre la obtención de la instancia.
     * @see Dependencias::cambiar() para cambiar el objeto de una clase ya registrada.
     * @see Dependencias::errores() para ver los errores ocurridos.
     *
     * @throws ClaseInexistente si la clase, o interfaz, no existe.
     * @throws ClaseReservada si la clase ya está reservada en el gestor.
     */
    public function agregar(string $nombre, callable $invocador): bool
    {
        if( !class_exists($nombre) && !interface_exists($nombre) ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
                throw new ClaseInexistente($nombre);
            }

            $this->errores->agregar(self::ERROR_CLASE_INEXISTENTE);
            return false;
        }

        if( $this->claseEstaReservada($nombre) ) {
            return false;
        }

        $this->clases[$nombre] = $this->definicionDeFuncionFinal($this->clases[$nombre], $invocador, $nombre);

        if( $this->configuracion->activados(self::INSTANCIAR_AL_AGREGAR) ) {
            $this->clases[$nombre]();
        }

        return true;
    }

    /**
     * Obtiene la instancia de la clase
     *
     * Si la clase aún no está definida se llamará al invocador y se almacenará la instancia.
     * Si el invocador no devuelve una instancia de la clase, o que implemente la interfaz,
     * devolverá **null** o lanzará una excepción, según configuración.
     *
     * @param string $nombre Nombre de la clase o interfaz reservada
     *
     * @return ?object Devuelve la instancia de la clase o **null** en caso de error
     *
     * @see Dependencias::errores() para ver los errores ocurridos.
     *
     * @throws ClaseNoReservada si la clase no está reservada en el gestor.
     */
    public function obtener(string $nombre): ?object
    {
        if( $this->claseNoEstaReservada($nombre) ) {
            return null;
        }

        return $this->clases[$nombre]();
    }

    /**
     * Cambia la instancia que devuelve la clase indicada
     *
     * Modifica la instancia del objeto que se devuelve al llamar al método Dependencias::obtener()
     * con el nombre de la clase indicada.
     *
     * @param string $nombre         Nombre de la clase o interfaz
     * @param object $nuevaInstancia Nueva instancia de la clase
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     *
     * @throws SinPermisosParaCambiar si no se tiene permisos para esta acción.
     * @throws ClaseNoReservada si la clase no está reservada en el gestor.
     * @throws ObjetoNoCorrespondido si la nueva instancia no corresponde con el tipo de la clase o interfaz.
     */
    public function cambiar(string $nombre, object $nuevaInstancia): bool
    {
        if( $this->configuracion->desactivados(self::PERMITIR_CAMBIAR) ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
                throw new SinPermisosParaCambiar($nombre);
            }

            $this->errores->agregar(self::ERROR_SIN_PERMISOS_PARA_CAMBIAR);
            return false;
        }

        if( $this->claseNoEstaReservada($nombre) || $this->objetoNoCorresponde($nuevaInstancia, $nombre) ) {
            return false;
        }

        $this->clases[$nombre] = static function() use ($nuevaInstancia) {
            return $nuevaInstancia;
        };

        return true;
    }

    /**
     * Elimina una clase reservada
     *
     * Si en configuración está desactivado Dependencias::PERMITIR_REMOVER se lanzará
     * un error Dependencias::ERROR_SIN_PERMISOS_PARA_REMOVER.
     *
     * @param string $nombre Nombre de la clase
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     *
     * @throws SinPermisosParaRemover si no se tiene permisos para esta acción.
     * @throws ClaseNoReservada si la clase o interfaz no está reservada por el gestor.
     */
    public function remover(string $nombre): bool
    {
        if( $this->configuracion->desactivados(self::PERMITIR_REMOVER) ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
                throw new SinPermisosParaRemover($nombre);
            }

            $this->errores->agregar(self::ERROR_SIN_PERMISOS_PARA_REMOVER);
            return false;
        }

        if( $this->claseNoEstaReservada($nombre) ) {
            return false;
        }

        $this->clases[$nombre] = null;
        unset($this->clases[$nombre]);

        return true;
    }

    /**
     * Define directamente el objeto que devolverá la clase a reservarse
     *
     * A diferencia de Dependencias::agregar() esta función define directamente el objeto que
     * será devuelto por Dependencias::obtener().
     *
     * La clase, o interfaz, no debe estar registrada.
     * Si ya está reservada use Dependencias::cambiar().
     *
     * @param string $nombre Nombre de la clase o interfaz
     * @param object $objeto Instancia del objeto a definir
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     *
     * @see Dependencias::agregar()
     * @see Dependencias::cambiar()
     *
     * @throws SinPermisosParaDefinir si no se tiene permisos para esta acción.
     * @throws ClaseReservada si la clase ya está reservada.
     * @throws ObjetoNoCorrespondido si el objeto no corresponde con el tipo de la clase o interfaz.
     */
    public function definir(string $nombre, object $objeto): bool
    {
        if( $this->configuracion->desactivados(self::PERMITIR_DEFINIR) ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
                throw new SinPermisosParaDefinir($nombre);
            }

            $this->errores->agregar(self::ERROR_SIN_PERMISOS_PARA_DEFINIR);
            return false;
        }

        if( $this->claseEstaReservada($nombre) || $this->objetoNoCorresponde($objeto, $nombre) ) {
            return false;
        }

        $this->clases[$nombre] = static function() use ($objeto) {
            return $objeto;
        };

        return true;
    }

    /**
     * Valida si se encuentran reservadas los nombre de las clases especificadas
     *
     * Si alguna de las dependencias indicada no se encuentra reservada en el gestor se
     * lanzará una excepción **DependenciasInexistentes** con todas aquellas clases
     * que no se encontraron.
     *
     * @param string $nombre   Nombre de la clase a validar si se encuentra reservada o no.
     * @param string ...$otros Más nombres de clases
     *
     * @return void
     *
     * @throws DependenciasInexistentes si hay una o más dependencias inexistentes
     */
    public function dependo(string $nombre, string ...$lista)
    {
        $lista[] = $nombre;
        $inexistentes = [];

        foreach( $lista as $clase ) {
            if( !isset($this->clases[$clase]) ) {
                $inexistentes[] = $clase;
            }
        }

        if( empty($inexistentes) === false ) {
            throw new DependenciasInexistentes($inexistentes);
        }
    }

    /**
     * Obtiene la lista de errores
     *
     * @return Errores Errores ocurridos
     */
    public function errores(): Errores
    {
        return $this->errores;
    }

    /**
     * Obtiene la configuración interna
     *
     * @return MascaraDeBits Devuelve una instancia de MascaraDeBits para configurar el gestor
     */
    public function configuracion(): MascaraDeBits
    {
        return $this->configuracion;
    }

    /**
     * Define la función anónima final que retornará Dependencias::obtener()
     *
     * Retorna una función donde se definirá la función final que será llamada por
     * Dependencias::obtener()
     *
     * @param object  &$elemento  Elemento del array donde se definirá la función
     * @param callable $invocador Función anónima donde se define la clase
     * @param string   $clase     Nombre de la clase o interfaz
     *
     * @return callable Devuelve una función anónima
     *
     * @access private
     */
    private function definicionDeFuncionFinal(&$elemento, callable $invocador, string $clase): callable
    {
        return function() use (&$elemento, $invocador, $clase) {
            // Obtiene la instancia de la función anónima
            // proporcionada por el usuario
            $objeto = $invocador();

            // Si la instancia no corresponde con la clase o interfaz
            if( $this->objetoNoCorresponde($objeto, $clase) ) {
                return null;
            }

            return ($elemento = static function() use ($objeto) {
                return $objeto;
            })();
        };
    }

    /**
     * Verifica si la clase, o interfaz, está ya reservada
     *
     * @param string $nombre Nombre de la clase
     *
     * @return bool Devuelve **true** si la clase está reservada o **false** de lo contrario
     *
     * @access private
     */
    private function claseEstaReservada(string $nombre): bool
    {
        if( empty($this->clases[$nombre]) ) {
            return false;
        }

        if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
            throw new ClaseReservada($nombre);
        }

        $this->errores->agregar(self::ERROR_CLASE_RESERVADA);
        return true;
    }

    /**
     * Valida si el objeto es una instancia de la clase o si implementa la interfaz
     *
     * @param object $objeto Instancia del objeto
     * @param string $clase  Nombre de la clase o interfaz
     *
     * @return bool Devuelve **false** si el objeto es una instancia de la clase, o **true** de lo contrario
     *
     * @access private
     */
    private function objetoNoCorresponde($objeto, string $clase): bool
    {
        if( $objeto instanceof $clase ) {
            return false;
        }

        if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
            throw new ObjetoNoCorrespondido($clase, $objeto);
        }

        $this->errores->agregar(self::ERROR_OBJETO_NO_CORRESPONDIDO);
        return true;
    }

    /**
     * Valida si el nombre de la clase o interfaz no está reservada
     *
     * @param string $nombre Nombre de la clase o interfaz
     *
     * @return bool Devuelve **true** si la clase no está reservada, **false** de lo contrario
     *
     * @access private
     */
    private function claseNoEstaReservada(string $nombre): bool
    {
        if( empty($this->clases[$nombre]) ) {
            if( $this->configuracion->activados(self::LANZAR_EXCEPCION) ) {
                throw new ClaseNoReservada($nombre);
            }

            $this->errores->agregar(self::ERROR_CLASE_NO_RESERVADA);
            return true;
        }

        return false;
    }

}
