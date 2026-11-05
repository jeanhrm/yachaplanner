<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Support\DocxExporter;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function word(ChatSession $session, DocxExporter $exporter)
    {
        // (int): en PostgreSQL/PDO el id puede llegar como string y !== fallaría siempre
        if ((int) $session->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $lastAssistant = ChatMessage::where('session_id', $session->id)
                                    ->where('role', 'assistant')
                                    ->orderBy('id', 'desc')
                                    ->first();

        if (!$lastAssistant) {
            abort(404, 'Este documento todavía no tiene contenido para descargar.');
        }

        $content = $lastAssistant->content;

        // La retroalimentación del modo revisión es para el docente, no va en el documento
        $content = preg_replace('/^##\s*RETROALIMENTACI[ÓO]N A TU PROPUESTA.*?(?=^##\s)/msu', '', $content) ?? $content;

        // El cierre "✅ Tu sesión está lista. Puedes descargarla..." es para el chat, no para el documento
        $content = preg_replace('/^\s*✅[^\n]*(Exportar|Descargar)[^\n]*$/mu', '', $content) ?? $content;

        $path     = $exporter->build($content, $session->display_title);
        $filename = 'yachaycito_' . $session->module . '_' . date('Ymd_His') . '.docx';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend();
    }
}
