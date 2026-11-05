<?php

namespace App\Support;

use App\Models\User;

class WhatsApp
{
    public static function number(): string
    {
        return config('services.whatsapp.number', '51910673119');
    }

    public static function link(string $text): string
    {
        return 'https://wa.me/' . self::number() . '?text=' . rawurlencode($text);
    }

    /** Presentación del docente para que el admin lo ubique rápido en /admin */
    private static function intro(User $user): string
    {
        return "Hola! Soy {$user->name}, docente"
            . ($user->area_docente ? " de {$user->area_docente}" : '')
            . ($user->ugel ? " - {$user->ugel}" : '')
            . '.';
    }

    public static function creditsLink(User $user): string
    {
        return self::link(self::intro($user)
            . ' Quiero recargar créditos en Yachaycito STEAM para seguir generando sesiones y planificaciones.'
            . " Mi correo de registro es {$user->email}.");
    }

    public static function proLink(User $user): string
    {
        return self::link(self::intro($user)
            . ' Quiero activar el Plan Pro de Yachaycito STEAM (S/ 25/mes).'
            . " Mi correo de registro es {$user->email}.");
    }

    public static function institutionLink(User $user): string
    {
        return self::link("Hola! Soy {$user->name}"
            . ($user->ugel ? " de {$user->ugel}" : '')
            . '. Quiero información sobre el Plan Institución de Yachaycito STEAM (S/ 350/año) para mi IE.'
            . " Mi correo de registro es {$user->email}.");
    }
}
