<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $phone = '51910673119';

        $msgPro = urlencode(
            "Hola! Soy {$user->name}, docente" .
            ($user->area_docente ? " de {$user->area_docente}" : '') .
            ($user->ugel ? " — {$user->ugel}" : '') .
            ". Quiero activar el Plan Pro de YachaPlanner (S/ 25/mes). Mi correo es {$user->email}."
        );

        $msgInstitucion = urlencode(
            "Hola! Soy {$user->name}" .
            ($user->ugel ? " de {$user->ugel}" : '') .
            ". Quiero información sobre el Plan Institución de YachaPlanner (S/ 350/año) para mi IE. Mi correo es {$user->email}."
        );

        $whatsappPro         = "https://wa.me/{$phone}?text={$msgPro}";
        $whatsappInstitucion = "https://wa.me/{$phone}?text={$msgInstitucion}";

        return view('credits.index', compact('user', 'whatsappPro', 'whatsappInstitucion'));
    }
}