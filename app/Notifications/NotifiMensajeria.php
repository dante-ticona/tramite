<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class NotifiMensajeria extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'url' => appUrl().('/home#/buscarCasos'),
            'message' => 'Tienes un mensaje de ' . getUserId($this->data['tiene_mensaje_de']),
            'user_id' => $this->data['user_id'],
            'cas_cod_id' => $this->data['cas_cod_id'],
            'tipo_conversacion' => $this->data['tipo_conversacion'],
            'id_caso' => $this->data['id_caso'],
            'mensaje' => $this->data['mensaje'],
            'estado' => $this->data['estado'],
            'estadosis' => $this->data['estadosis'],
            'id_conversacion' => $this->data['id_conversacion']
        ];
    }
}
