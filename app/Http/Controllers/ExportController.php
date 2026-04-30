<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function word(Request $request, ChatSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $lastAssistant = ChatMessage::where('session_id', $session->id)
                                    ->where('role', 'assistant')
                                    ->orderBy('id', 'desc')
                                    ->first();

        if (!$lastAssistant) {
            return back()->with('error', 'No hay contenido para exportar.');
        }

        $content  = $lastAssistant->content;
        $html     = $this->markdownToHtml($content);
        $filename = 'yachaplanner_' . $session->module . '_' . date('Ymd_His') . '.doc';

        $fullHtml = '
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 11pt; color: #111827; margin: 2cm; }
    h1 { font-size: 16pt; color: #1a7a4a; border-bottom: 2px solid #d1fae5; padding-bottom: 4px; }
    h2 { font-size: 13pt; color: #1a7a4a; margin-top: 20px; border-bottom: 1px solid #d1fae5; padding-bottom: 3px; }
    h3 { font-size: 11pt; color: #374151; margin-top: 14px; }
    p  { line-height: 1.6; margin: 6px 0; }
    ul, ol { padding-left: 20px; margin: 6px 0; }
    li { margin-bottom: 4px; line-height: 1.5; }
    table { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 10pt; }
    th { background-color: #1a7a4a; color: #ffffff; padding: 6px 10px; text-align: left; font-weight: bold; }
    td { padding: 5px 10px; border: 1px solid #d1fae5; }
    tr:nth-child(even) td { background-color: #f0fdf4; }
    strong { font-weight: bold; color: #065f46; }
    em { font-style: italic; }
    hr { border: 1px solid #d1fae5; margin: 12px 0; }
    .header { text-align: right; color: #1a7a4a; font-size: 9pt; font-weight: bold; margin-bottom: 20px; }
    .footer { text-align: center; color: #999999; font-size: 8pt; margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
</style>
</head>
<body>
<div class="header">YachaPlanner — Planificación Curricular STEAM</div>
' . $html . '
<div class="footer">Generado con YachaPlanner · yachaplanner-production.up.railway.app</div>
</body>
</html>';

        return response($fullHtml)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($fullHtml));
    }

    private function markdownToHtml(string $markdown): string
    {
        $lines   = explode("\n", $markdown);
        $html    = '';
        $inTable = false;
        $tableHtml = '';
        $inList  = false

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Tablas
            if (str_starts_with($trimmed, '|')) {
                if (!$inTable) {
                    $inTable   = true;
                    $tableHtml = '<table>';
                }
                // Saltar separadores |---|
                if (preg_match('/^\|[\s\-|:]+\|$/', $trimmed)) continue;

                $cells    = array_map('trim', explode('|', trim($trimmed, '|')));
                $isHeader = ($tableHtml === '<table>');
                $tag      = $isHeader ? 'th' : 'td';
                $tableHtml .= '<tr>';
                foreach ($cells as $cell) {
                    $tableHtml .= "<{$tag}>" . htmlspecialchars($cell) . "</{$tag}>";
                }
                $tableHtml .= '</tr>';
                continue;
            }

            // Cerrar tabla
            if ($inTable) {
                $html   .= $tableHtml . '</table>';
                $tableHtml = '';
                $inTable = false;
            }

            // Cerrar lista
            if ($inList && !preg_match('/^[-*]\s+/', $trimmed)) {
                $html  .= '</ul>';
                $inList = false;
            }

            if (empty($trimmed)) {
                $html .= '<br>';
                continue;
            }

            if (str_starts_with($trimmed, '### ')) {
                $html .= '<h3>' . $this->inlineFormat(substr($trimmed, 4)) . '</h3>';
            } elseif (str_starts_with($trimmed, '## ')) {
                $html .= '<h2>' . $this->inlineFormat(substr($trimmed, 3)) . '</h2>';
            } elseif (str_starts_with($trimmed, '# ')) {
                $html .= '<h1>' . $this->inlineFormat(substr($trimmed, 2)) . '</h1>';
            } elseif (preg_match('/^[-*]\s+(.+)/', $trimmed, $m)) {
                if (!$inList) {
                    $html  .= '<ul>';
                    $inList = true;
                }
                $html .= '<li>' . $this->inlineFormat($m[1]) . '</li>';
            } elseif (str_starts_with($trimmed, '---')) {
                $html .= '<hr>';
            } else {
                $html .= '<p>' . $this->inlineFormat($trimmed) . '</p>';
            }
        }

        // Cerrar tabla o lista al final
        if ($inTable)  $html .= $tableHtml . '</table>';
        if ($inList)   $html .= '</ul>';

        return $html;
    }

    private function inlineFormat(string $text): string
    {
        // Bold
        $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
        // Italic
        $text = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $text);
        // Escapar caracteres HTML excepto los tags que acabamos de crear
        return $text;
    }
}