<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

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

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Solo agregar una línea fija para probar
        $section->addText('Contenido exportado correctamente.');

        $filename = 'test_' . date('Ymd_His') . '.docx';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function parseMarkdownToWord($section, string $markdown): void
    {
        // Limpiar agresivamente todo
        $clean = preg_replace('/[^\x20-\x7E\x0A\x0D]/u', '', $markdown);
        
        $lines = explode("\n", $clean);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                $section->addTextBreak(1);
                continue;
            }
            // Solo texto, sin ningún formato
            $section->addText($line, ['size' => 11]);
        }
    }


    private function renderTable($section, array $tableLines): void
    {
        // Filtrar separadores
        $rows = array_values(array_filter(
            $tableLines,
            fn($l) => !preg_match('/^\|[\s\-|:]+\|$/', $l)
        ));

        if (empty($rows)) return;

        foreach ($rows as $i => $row) {
            $cells   = array_map('trim', explode('|', trim($row, '|')));
            $cells   = array_filter($cells, fn($c) => $c !== '');
            $cells   = array_values($cells);
            $isHeader = ($i === 0);
            $text    = implode('   |   ', array_map([$this, 'cleanText'], $cells));

            if ($text === '') continue;

            $section->addText(
                $text,
                [
                    'bold'  => $isHeader,
                    'size'  => $isHeader ? 11 : 10,
                    'color' => $isHeader ? '1a7a4a' : '111827',
                ],
                [
                    'spaceAfter'  => $isHeader ? 40 : 20,
                    'spaceBefore' => $isHeader ? 80 : 0,
                ]
            );
        }

        $section->addTextBreak(1);
    }

    private function addFormattedText($run, string $text): void
    {
        $parts = preg_split('/(\*\*[^*]+\*\*|\*[^*]+\*)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($parts as $part) {
            if (str_starts_with($part, '**') && str_ends_with($part, '**')) {
                $run->addText(
                    $this->cleanText(substr($part, 2, -2)),
                    ['bold' => true, 'size' => 11]
                );
            } elseif (str_starts_with($part, '*') && str_ends_with($part, '*')) {
                $run->addText(
                    $this->cleanText(substr($part, 1, -1)),
                    ['italic' => true, 'size' => 11]
                );
            } else {
                $cleaned = $this->cleanText($part);
                if ($cleaned !== '') {
                    $run->addText($cleaned, ['size' => 11]);
                }
            }
        }
    }

    private function sanitizeContent(string $content): string
    {
        // Eliminar emojis completamente
        $content = preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $content);
        $content = preg_replace('/[\x{2600}-\x{27BF}]/u', '', $content);
        $content = preg_replace('/[\x{1F300}-\x{1F64F}]/u', '', $content);
        $content = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $content);
        $content = preg_replace('/[\x{2702}-\x{27B0}]/u', '', $content);

        // Eliminar caracteres de control inválidos en XML
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $content);

        // Reemplazar caracteres especiales problemáticos
        $content = str_replace(['✅', '❌', '⚡', '🌱', '📝', '📅', '🔬', '✅', '🏃', '🎨', '💼', '🕊️', '🗺️', '🤝', '🏘️', '🌐', '📰', '📐'], '', $content);

        return $content;
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/[*_`#~]/', '', $text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = $this->sanitizeContent($text);
        return trim($text);
    }
}