<?php

namespace App\Http\Controllers;

use App\Support\WhatsApp;
use Illuminate\Support\Facades\Auth;

class CreditsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $whatsappCreditos    = WhatsApp::creditsLink($user);
        $whatsappPro         = WhatsApp::proLink($user);
        $whatsappInstitucion = WhatsApp::institutionLink($user);

        return view('credits.index', compact('user', 'whatsappCreditos', 'whatsappPro', 'whatsappInstitucion'));
    }
}
