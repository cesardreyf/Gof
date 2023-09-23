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
     * Crea un subregistro asociado a un nombre
     *
     * @param string $nombre Nombre del subregistro
     */
    public function crearSubregistro(string $nombre): self;

    /**
     * Agrega un mensaje al registro
     *
     * @param string $mensaje
     */
    public function agregarMensaje(string $mensaje);

    /**
     * Prepara un mensaje para ser guardado en el registro
     *
     * @param string $mensaje
     *
     * @return MensajePreparable
     */
    public function preparar(string $mensaje): MensajeVinculante;

    /**
     * Limpia el registro de mensajes
     */
    public function limpiar();

    /**
     * Verifica si el registro está vacío
     *
     * @return bool Devuelve el estado de la validación
     */
    public function vacio(): bool;
}
