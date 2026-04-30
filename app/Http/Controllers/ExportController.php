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

        $phpWord->addTitleStyle(1, [
            'bold' => true, 'size' => 16, 'color' => '1a7a4a',
        ], ['spaceAfter' => 200]);

        $phpWord->addTitleStyle(2, [
            'bold' => true, 'size' => 13, 'color' => '1a7a4a',
        ], ['spaceAfter' => 120, 'spaceBefore' => 240]);

        $phpWord->addTitleStyle(3, [
            'bold' => true, 'size' => 11, 'color' => '374151',
        ], ['spaceAfter' => 80, 'spaceBefore' => 160]);

        $section = $phpWord->addSection([
            'marginTop'    => 1440,
            'marginBottom' => 1440,
            'marginLeft'   => 1440,
            'marginRight'  => 1440,
        ]);

        $header = $section->addHeader();
        $header->addText(
            'YachaPlanner — Planificación Curricular STEAM',
            ['bold' => true, 'size' => 9, 'color' => '1a7a4a'],
            ['alignment' => Jc::RIGHT]
        );

        $footer = $section->addFooter();
        $footer->addText(
            'Generado con YachaPlanner · yachaplanner-production.up.railway.app',
            ['size' => 8, 'color' => '999999'],
            ['alignment' => Jc::CENTER]
        );

        $this->parseMarkdownToWord($section, $lastAssistant->content);

        $filename = 'yachaplanner_' . $session->module . '_' . date('Ymd_His') . '.docx';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function parseMarkdownToWord($section, string $markdown): void
    {
        $markdown   = $this->sanitizeContent($markdown);
        $lines      = explode("\n", $markdown);
        $tableLines = [];
        $inTable    = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (str_starts_with($trimmed, '|')) {
                $inTable      = true;
                $tableLines[] = $trimmed;
                continue;
            }

            if ($inTable) {
                $this->renderTable($section, $tableLines);
                $tableLines = [];
                $inTable    = false;
            }

            if (empty($trimmed)) {
                $section->addTextBreak(1);
                continue;
            }

            // Headings
            if (preg_match('/^### (.+)/', $trimmed, $m)) {
                $section->addText($this->cleanText($m[1]), ['bold' => true, 'size' => 11, 'color' => '374151'], ['spaceAfter' => 80, 'spaceBefore' => 160]);
            } elseif (preg_match('/^## (.+)/', $trimmed, $m)) {
                $section->addText($this->cleanText($m[1]), ['bold' => true, 'size' => 13, 'color' => '1a7a4a'], ['spaceAfter' => 120, 'spaceBefore' => 240, 'borderBottomSize' => 4, 'borderBottomColor' => 'd1fae5']);
            } elseif (preg_match('/^# (.+)/', $trimmed, $m)) {
                $section->addText($this->cleanText($m[1]), ['bold' => true, 'size' => 16, 'color' => '1a7a4a'], ['spaceAfter' => 200]);
            } elseif (preg_match('/^[-*]\s+(.+)/', $trimmed, $m)) {
                $section->addText('• ' . $this->cleanText($m[1]), ['size' => 11], ['spaceAfter' => 40, 'indentation' => ['left' => 360]]);
            } elseif (str_starts_with($trimmed, '---')) {
                $section->addTextBreak(1);
            } else {
                $clean = preg_replace('/\*\*([^*]+)\*\*/', '$1', $trimmed);
                $clean = preg_replace('/\*([^*]+)\*/', '$1', $clean);
                $clean = $this->cleanText($clean);
                if ($clean !== '') {
                    $section->addText($clean, ['size' => 11], ['spaceAfter' => 60]);
                }
            }
        }

        if ($inTable && count($tableLines) > 0) {
            $this->renderTable($section, $tableLines);
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

        $firstCells = array_map('trim', explode('|', trim($rows[0], '|')));
        $numCols    = count($firstCells);
        if ($numCols < 1) return;

        $totalWidth = 8640;
        $cellWidth  = (int) floor($totalWidth / $numCols);

        try {
            $table = $section->addTable([
                'borderSize'  => 4,
                'borderColor' => '1a7a4a',
                'cellMargin'  => 80,
            ]);

            foreach ($rows as $i => $row) {
                $cells    = array_map('trim', explode('|', trim($row, '|')));
                $isHeader = ($i === 0);
                $bgColor  = $isHeader ? '1a7a4a' : ($i % 2 === 0 ? 'f0fdf4' : 'ffffff');

                $table->addRow();

                foreach ($cells as $cellText) {
                    $clean = $this->cleanText($cellText);
                    $td    = $table->addCell($cellWidth, ['bgColor' => $bgColor]);
                    $td->addText(
                        $clean !== '' ? $clean : ' ',
                        [
                            'bold'  => $isHeader,
                            'color' => $isHeader ? 'ffffff' : '111827',
                            'size'  => 10,
                        ]
                    );
                }
            }

            $section->addTextBreak(1);

        } catch (\Exception $e) {
            // Si la tabla falla, escribir como texto
            foreach ($rows as $row) {
                $cells = array_map('trim', explode('|', trim($row, '|')));
                $section->addText($this->cleanText(implode(' | ', $cells)), ['size' => 10], ['spaceAfter' => 40]);
            }
            $section->addTextBreak(1);
        }
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