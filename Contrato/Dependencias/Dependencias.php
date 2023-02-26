<?php

namespace Gof\Contrato\Dependencias;

use Gof\Interfaz\Errores\Errores;

/**
 * Interfaz que define las funcionalidades básicas de un gestor de inyeción de dependencias
 *
 * Interfaz empleada para los gestores encargados del manejo de dependencias.
 *
 * @package Gof\Contrato\Dependencias
 */
interface Dependencias
{
    /**
     * Agrega una función que deberá definir el objeto asociado a la clase
     *
     * Agrega al gestor una función el cual se encargará de instanciar el objeto que será
     * devuelto cada vez que se obtenga con el mismo nombre con el que se define.
     *
     * @param string   $nombre    Nombre de la clase o interfaz
     * @param callable $invocador Función que define y retorna la instancia del objeto
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function agregar(string $nombre, callable $invocador): bool;

    /**
     * Cambia la instancia asociada al nombre de la clase previamente agregada
     *
     * Modifica el objeto que se devuelve al obtener la instancia de la clase y lo cambia por
     * la nueva instancia, el cual debe ser del mismo tipo de la clase o implementar la interfaz.
     *
     * @param string $nombre    Nombre de la clase o interfaz
     * @param object $instancia Nueva instancia
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function cambiar(string $nombre, object $instancia): bool;

    /**
     * Agrega una instancia al gestor y la asocia a la clase o interfaz
     *
     * Lo mismo que Dependencias::agregar() pero sin la necesidad de una función que la defina.
     * La diferencia es que este método sirve para aquellos objetos que ya se encuentran
     * definidos.
     *
     * @param string $nombre    Nombre de la clase o interfaz
     * @param object $instancia Instancia del objeto a definir
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     *
     * @see Dependencias::agregar()
     */
    public function definir(string $nombre, object $instancia): bool;

    /**
     * Indica aquellas clases de las cuales se requiere una instancia
     *
     * De existir una clase o interfaz que no esté reservada en el gestor se lanzaría una excepción.
     *
     * @param string $nombre   Nombre de la clase o interfaz
     * @param string ...$lista Más nombres de clases o interfaces
     */
    public function dependo(string $nombre, string ...$lista);

    /**
     * Obtiene la instancia del objeto asociado al nombre de la clase o interfaz
     *
     * Si la instancia no se definió aún se llama a la función registrada para crear la
     * instancia del objeto y se devuelve el mismo.
     *
     * @param string $nombre Nombre de la clase o interfaz
     *
     * @return ?object Devuelve la instancia asociada a la clase o interfaz, o **NULL** en caso de error
     */
    public function obtener(string $nombre): ?object;

    /**
     * Remueve del gestor la clase y el objeto asociado
     *
     * Elimina el objeto, o la función que lo define, y deja libre el nombre de la clase o interfaz.
     *
     * @param string $nombre Nombre de la clase o interfaz
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function remover(string $nombre): bool;

    /**
     * Lista de errores
     *
     * Gestor de errores donde se almacenan los errores producidos por el gestor y sus
     * funcionalidades.
     *
     * @return Errores Devuelve una instancia al gestor de errores.
     */
    public function errores(): Errores;
}
