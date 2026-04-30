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
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop'    => 1440,
            'marginBottom' => 1440,
            'marginLeft'   => 1440,
            'marginRight'  => 1440,
        ]);

        $content = $this->sanitize($lastAssistant->content);

        $this->parseMarkdownToWord($section, $content);

        $filename = 'yachaplanner_' . $session->module . '_' . date('Ymd_His') . '.docx';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function sanitize(string $text): string
    {
        // Eliminar emojis y símbolos especiales
        $text = preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $text);
        $text = preg_replace('/[\x{2600}-\x{27BF}]/u', '', $text);
        $text = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $text);
        $text = preg_replace('/[\x{2702}-\x{27B0}]/u', '', $text);
        // Eliminar caracteres de control XML inválidos
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        return $text;
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/[*_`#~]/', '', $text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = $this->sanitize($text);
        return trim($text);
    }

    private function parseMarkdownToWord($section, string $markdown): void
    {
        $lines = explode("\n", $markdown);

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Tablas — escribir como texto raw sin procesar
            if (str_starts_with($trimmed, '|')) {
                $raw = preg_replace('/[|]/', ' ', $trimmed);
                $raw = preg_replace('/\s+/', ' ', $raw);
                $raw = trim($raw);
                if ($raw !== '') {
                    $section->addText($raw, ['size' => 9, 'color' => '374151']);
                }
                continue;
            }

            if (empty($trimmed)) {
                $section->addTextBreak(1);
                continue;
            }

            if (preg_match('/^### (.+)/', $trimmed, $m)) {
                $section->addText(
                    $this->cleanText($m[1]),
                    ['bold' => true, 'size' => 11, 'color' => '374151'],
                    ['spaceAfter' => 80, 'spaceBefore' => 160]
                );
            } elseif (preg_match('/^## (.+)/', $trimmed, $m)) {
                $section->addText(
                    $this->cleanText($m[1]),
                    ['bold' => true, 'size' => 13, 'color' => '1a7a4a'],
                    ['spaceAfter' => 120, 'spaceBefore' => 240]
                );
            } elseif (preg_match('/^# (.+)/', $trimmed, $m)) {
                $section->addText(
                    $this->cleanText($m[1]),
                    ['bold' => true, 'size' => 16, 'color' => '1a7a4a'],
                    ['spaceAfter' => 200]
                );
            } elseif (preg_match('/^[-*]\s+(.+)/', $trimmed, $m)) {
                $section->addText(
                    '• ' . $this->cleanText($m[1]),
                    ['size' => 11],
                    ['spaceAfter' => 40, 'indentation' => ['left' => 360]]
                );
            } else {
                $clean = $this->cleanText($trimmed);
                if ($clean !== '') {
                    $section->addText($clean, ['size' => 11], ['spaceAfter' => 60]);
                }
            }
        }
    }

    private function renderTable($section, array $tableLines): void
    {
        $rows = array_values(array_filter(
            $tableLines,
            fn($l) => !preg_match('/^\|[\s\-|:]+\|$/', $l)
        ));

        if (empty($rows)) return;

        foreach ($rows as $i => $row) {

            $cells = array_map('trim', explode('|', trim($row, '|')));

            // 🔥 limpiar contenido (clave)
            $cells = array_map(function ($cell) {
                return $this->cleanText($cell);
            }, $cells);

            $text = implode(' | ', $cells);

            if ($text === '') continue;

            $section->addText($text, ['size' => 10]);
        }
    }
    
}