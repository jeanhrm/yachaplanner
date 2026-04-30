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

        // Obtener el último mensaje del asistente
        $lastAssistant = ChatMessage::where('session_id', $session->id)
                                    ->where('role', 'assistant')
                                    ->latest()
                                    ->first();

        if (!$lastAssistant) {
            return back()->with('error', 'No hay contenido para exportar.');
        }

        $content = $lastAssistant->content;
        $phpWord  = new PhpWord();

        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $phpWord->addTitleStyle(1, [
            'bold' => true, 'size' => 14, 'color' => '1a7a4a',
        ], ['spaceAfter' => 120]);

        $phpWord->addTitleStyle(2, [
            'bold' => true, 'size' => 12, 'color' => '1a7a4a',
        ], ['spaceAfter' => 80, 'spaceBefore' => 160]);

        $phpWord->addTitleStyle(3, [
            'bold' => true, 'size' => 11, 'color' => '333333',
        ], ['spaceAfter' => 60, 'spaceBefore' => 120]);

        $section = $phpWord->addSection([
            'marginTop'    => 1080,
            'marginBottom' => 1080,
            'marginLeft'   => 1080,
            'marginRight'  => 1080,
        ]);

        // Header
        $header = $section->addHeader();
        $headerTable = $header->addTable(['borderSize' => 0, 'cellMargin' => 50]);
        $headerTable->addRow();
        $cell = $headerTable->addCell(9000);
        $cell->addText('YachaPlanner — Planificación Curricular STEAM', [
            'bold' => true, 'size' => 9, 'color' => '1a7a4a'
        ], ['alignment' => Jc::RIGHT]);

        // Footer
        $footer = $section->addFooter();
        $footer->addText('Generado con YachaPlanner · yachaplanner-production.up.railway.app', [
            'size' => 8, 'color' => '999999'
        ], ['alignment' => Jc::CENTER]);

        $this->parseMarkdownToWord($section, $content);

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
        $lines      = explode("\n", $markdown);
        $tableLines = [];
        $inTable    = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Acumular líneas de tabla
            if (str_starts_with($trimmed, '|')) {
                $inTable = true;
                $tableLines[] = $trimmed;
                continue;
            }

            // Fin de tabla
            if ($inTable) {
                $this->renderTable($section, $tableLines);
                $tableLines = [];
                $inTable    = false;
            }

            if (empty($trimmed)) {
                $section->addTextBreak(1);
                continue;
            }

            if (str_starts_with($trimmed, '### ')) {
                $section->addTitle($this->cleanText(substr($trimmed, 4)), 3);
            } elseif (str_starts_with($trimmed, '## ')) {
                $section->addTitle($this->cleanText(substr($trimmed, 3)), 2);
            } elseif (str_starts_with($trimmed, '# ')) {
                $section->addTitle($this->cleanText(substr($trimmed, 2)), 1);
            } elseif (preg_match('/^[-*]\s+(.+)/', $trimmed, $m)) {
                $section->addListItem($this->cleanText($m[1]), 0, ['size' => 11]);
            } else {
                $paragraph = $section->addTextRun(['spaceAfter' => 60]);
                $this->addFormattedText($paragraph, $trimmed);
            }
        }

        // Tabla al final del documento
        if ($inTable && count($tableLines) > 0) {
            $this->renderTable($section, $tableLines);
        }
    }

    private function renderTable($section, array $tableLines): void
    {
        // Filtrar línea separadora |---|---|
        $rows = array_values(array_filter(
            $tableLines,
            fn($l) => !preg_match('/^\|[\s\-|:]+\|$/', $l)
        ));

        if (empty($rows)) return;

        // Calcular número de columnas
        $firstCells = array_map('trim', explode('|', trim($rows[0], '|')));
        $numCols    = count($firstCells);

        // Ancho total disponible en twips (9000 = ~15.24cm)
        $totalWidth = 9000;
        $cellWidth  = (int) floor($totalWidth / max($numCols, 1));

        $tableStyle = [
            'borderSize'  => 6,
            'borderColor' => '1a7a4a',
            'cellMargin'  => 80,
            'unit'        => \PhpOffice\PhpWord\Style\Table::WIDTH_TWIP,
            'width'       => $totalWidth,
        ];

        $table = $section->addTable($tableStyle);

        foreach ($rows as $i => $row) {
            $cells    = array_map('trim', explode('|', trim($row, '|')));
            $isHeader = $i === 0;

            $table->addRow();

            foreach ($cells as $cell) {
                $bgColor = $isHeader ? '1a7a4a' : ($i % 2 === 0 ? 'f0fdf4' : 'ffffff');
                $td = $table->addCell($cellWidth, ['bgColor' => $bgColor]);
                $td->addText(
                    $this->cleanText($cell),
                    [
                        'bold'  => $isHeader,
                        'color' => $isHeader ? 'ffffff' : '111827',
                        'size'  => 10,
                    ]
                );
            }
        }

        $section->addTextBreak(1);
    }

    private function addFormattedText($paragraph, string $text): void
    {
        $parts = preg_split('/(\*\*[^*]+\*\*|\*[^*]+\*)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($parts as $part) {
            if (str_starts_with($part, '**') && str_ends_with($part, '**')) {
                $paragraph->addText($this->cleanText(substr($part, 2, -2)), ['bold' => true, 'size' => 11]);
            } elseif (str_starts_with($part, '*') && str_ends_with($part, '*')) {
                $paragraph->addText($this->cleanText(substr($part, 1, -1)), ['italic' => true, 'size' => 11]);
            } else {
                $paragraph->addText($this->cleanText($part), ['size' => 11]);
            }
        }
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/[*_`#]/', '', $text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        return trim($text);
    }
}