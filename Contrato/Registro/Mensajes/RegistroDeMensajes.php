<?php

namespace Gof\Contrato\Registro\Mensajes;

use Gof\Interfaz\Lista;

/**
 * Interfaz para los gestores de registro de mensajes
 *
 * @package Gof\Contrato\Registro\Mensajes
 */
interface RegistroDeMensajes extends MensajeGuardable, Lista
{
    /**
     * Agrega un mensaje al registro
     *
     * @param string $mensaje
     */
    public function agregarMensaje(string $mensaje);

    /**
     * Crea un subregistro asociado a un nombre
     *
     * @param string $nombre Nombre del subregistro
     *
     * @return RegistroDeMensajes
     */
    public function crearSubregistro(string $nombre): self;

    /**
     * Prepara un mensaje para ser guardado en el registro
     *
     * @param string $mensaje
     *
     * @return MensajeVinculante
     */
    public function preparar(string $mensaje): MensajeVinculante;

    /**
     * Limpia el registro de mensajes
     */
    public function limpiar();

    /**
     * Verifica si el registro está vacío
     *
     * @return bool Devuelve **true** si está vacio, **false** de lo contrario
     */
    public function vacio(): bool;

    /**
     * Verifica si hay mensajes registrados
     *
     * @return bool Devuelve **true** si hay mensajes, **false** de lo contrario
     */
    public function hay(): bool;
}
