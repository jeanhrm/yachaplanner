<?php

namespace App\Support;

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * Convierte el markdown que genera la IA en un .docx real.
 * Las tablas se construyen celda por celda con la API de PhpWord (no con HTML),
 * para que se vean ordenadas en Word, Google Docs, WPS y celulares.
 */
class DocxExporter
{
    private const GREEN      = '1A7A4A';
    private const DARK_GREEN = '065F46';
    private const BORDER     = 'A7D8BF';
    private const ZEBRA      = 'F0FDF4';

    // A4 en twips (1 cm = 567 twips), márgenes de 2 cm
    private const PAGE_W   = 11906;
    private const PAGE_H   = 16838;
    private const MARGIN   = 1134;

    /** A partir de cuántas columnas la hoja va en horizontal */
    private const LANDSCAPE_COLS = 6;

    public function build(string $markdown, string $title): string
    {
        Settings::setOutputEscapingEnabled(true);   // sin esto un "&" corrompe el archivo

        $blocks    = $this->parse($markdown);
        $maxCols   = max([0, ...array_map(fn($b) => $b['type'] === 'table' ? count($b['rows'][0] ?? []) : 0, $blocks)]);
        $landscape = $maxCols >= self::LANDSCAPE_COLS;

        $word = new PhpWord();
        $word->setDefaultFontName('Arial');
        $word->setDefaultFontSize(11);
        $word->getDocInfo()->setTitle($title)->setCreator('Yachaycito STEAM 2.0');

        // keepNext: un título nunca queda solo al pie de una página, separado de su contenido
        $word->addTitleStyle(1, ['size' => 16, 'bold' => true, 'color' => self::GREEN], ['spaceBefore' => 240, 'spaceAfter' => 120, 'keepNext' => true]);
        $word->addTitleStyle(2, ['size' => 13, 'bold' => true, 'color' => self::GREEN], ['spaceBefore' => 240, 'spaceAfter' => 100,
            'borderBottomSize' => 6, 'borderBottomColor' => self::BORDER, 'keepNext' => true]);
        $word->addTitleStyle(3, ['size' => 11.5, 'bold' => true, 'color' => '374151'], ['spaceBefore' => 180, 'spaceAfter' => 80, 'keepNext' => true]);
        $word->addTitleStyle(4, ['size' => 11, 'bold' => true, 'color' => '374151'], ['spaceBefore' => 120, 'spaceAfter' => 60, 'keepNext' => true]);

        $word->addTableStyle('Yachay', [
            'borderSize'  => 6,
            'borderColor' => self::BORDER,
            'cellMargin'  => 70,
            'layout'      => TableStyle::LAYOUT_FIXED,
            'unit'        => 'dxa',
        ]);

        $section = $word->addSection([
            'orientation'  => $landscape ? 'landscape' : 'portrait',
            'pageSizeW'    => $landscape ? self::PAGE_H : self::PAGE_W,
            'pageSizeH'    => $landscape ? self::PAGE_W : self::PAGE_H,
            'marginTop'    => self::MARGIN, 'marginBottom' => self::MARGIN,
            'marginLeft'   => self::MARGIN, 'marginRight'  => self::MARGIN,
        ]);
        $usable = ($landscape ? self::PAGE_H : self::PAGE_W) - 2 * self::MARGIN;

        $section->addHeader()->addText('Yachaycito STEAM 2.0 — Planificación Curricular STEAM',
            ['size' => 8, 'bold' => true, 'color' => self::GREEN], ['alignment' => Jc::END]);
        $section->addFooter()->addPreserveText('Generado con Yachaycito STEAM 2.0 · Página {PAGE} de {NUMPAGES}',
            ['size' => 8, 'color' => '999999'], ['alignment' => Jc::CENTER]);

        foreach ($blocks as $block) {
            match ($block['type']) {
                'heading' => $section->addTitle($this->plain($block['text']), $block['level']),
                'para'    => $this->addParagraph($section, $block['text']),
                'bullet'  => $this->addParagraph($section, $block['text'], '•', $block['indent']),
                'number'  => $this->addParagraph($section, $block['text'], $block['marker'], $block['indent']),
                'table'   => $this->addTable($section, $block['rows'], $usable),
                'hr'      => $section->addText('', [], ['borderBottomSize' => 6, 'borderBottomColor' => self::BORDER, 'spaceAfter' => 120]),
            };
        }

        $base = tempnam(sys_get_temp_dir(), 'yachay');
        @unlink($base);                    // tempnam crea el archivo; solo queremos el nombre único
        $path = $base . '.docx';
        IOFactory::createWriter($word, 'Word2007')->save($path);
        return $path;
    }

    // ---------------------------------------------------------------- parseo

    /** Divide el markdown en bloques: títulos, párrafos, listas, tablas y separadores */
    private function parse(string $md): array
    {
        $md     = str_replace(["\r\n", "\r"], "\n", $md);
        $lines  = explode("\n", $md);
        $blocks = [];
        $table  = null;

        foreach ($lines as $raw) {
            $line = trim($raw);

            if (str_starts_with($line, '|')) {
                $cells = $this->splitRow($line);
                if ($this->isSeparator($cells)) continue;
                $table ??= [];
                $table[] = $cells;
                continue;
            }
            if ($table !== null) {
                $blocks[] = ['type' => 'table', 'rows' => $this->normalizeTable($table)];
                $table    = null;
            }

            if ($line === '' || str_starts_with($line, '```')) continue;

            $indent = intdiv(strlen($raw) - strlen(ltrim($raw)), 2);

            if (preg_match('/^(#{1,6})\s+(.+)$/u', $line, $m)) {
                $blocks[] = ['type' => 'heading', 'level' => min(strlen($m[1]), 4), 'text' => $m[2]];
            } elseif (preg_match('/^([-*_])(\s*\1){2,}$/', $line)) {
                $blocks[] = ['type' => 'hr'];
            } elseif (preg_match('/^[-*•]\s+(.+)$/u', $line, $m)) {
                $blocks[] = ['type' => 'bullet', 'text' => $m[1], 'indent' => min($indent, 3)];
            } elseif (preg_match('/^(\d+[.)])\s+(.+)$/u', $line, $m)) {
                $blocks[] = ['type' => 'number', 'marker' => $m[1], 'text' => $m[2], 'indent' => min($indent, 3)];
            } else {
                $blocks[] = ['type' => 'para', 'text' => $line];
            }
        }
        if ($table !== null) {
            $blocks[] = ['type' => 'table', 'rows' => $this->normalizeTable($table)];
        }
        return $blocks;
    }

    private function splitRow(string $line): array
    {
        $line  = str_replace('\|', "\u{E000}", $line);   // pipes escapados dentro de una celda
        $line  = preg_replace('/^\||\|$/', '', $line);
        return array_map(fn($c) => str_replace("\u{E000}", '|', trim($c)), explode('|', $line));
    }

    private function isSeparator(array $cells): bool
    {
        foreach ($cells as $c) {
            if (!preg_match('/^:?-{2,}:?$/', $c)) return false;
        }
        return (bool) $cells;
    }

    /** Todas las filas con el mismo número de columnas que el encabezado */
    private function normalizeTable(array $rows): array
    {
        $cols = count($rows[0]);
        return array_map(function ($r) use ($cols) {
            if (count($r) > $cols) {
                $r = [...array_slice($r, 0, $cols - 1), implode(' | ', array_slice($r, $cols - 1))];
            }
            return array_pad($r, $cols, '');
        }, $rows);
    }

    // -------------------------------------------------------------- escritura

    private function addParagraph(Section $section, string $text, ?string $marker = null, int $indent = 0): void
    {
        $style = ['spaceAfter' => 80, 'lineHeight' => 1.15];
        if ($marker !== null) {
            $left  = 360 + $indent * 360;
            $style += ['indentation' => ['left' => $left, 'hanging' => 280], 'spaceAfter' => 40];
        }
        $run = $section->addTextRun($style);
        if ($marker !== null) $run->addText($marker . "\t", ['bold' => $marker !== '•', 'color' => self::GREEN]);
        $this->addInline($run, $text);
    }

    private function addTable(Section $section, array $rows, int $usable): void
    {
        $widths = $this->columnWidths($rows, $usable);
        $table  = $section->addTable('Yachay');

        foreach ($rows as $i => $cells) {
            $header = $i === 0;
            // El encabezado se repite en cada página; las filas cortas no se parten entre páginas
            $table->addRow(null, ['tblHeader' => $header, 'cantSplit' => $this->rowLength($cells) < 400]);
            foreach ($cells as $c => $text) {
                $cell = $table->addCell($widths[$c], [
                    'bgColor' => $header ? self::GREEN : ($i % 2 === 0 ? self::ZEBRA : 'FFFFFF'),
                    'valign'  => $header ? 'center' : 'top',
                ]);
                $this->addCellContent($cell, $text, $header);
            }
        }
        $section->addText('', [], ['spaceAfter' => 60]);
    }

    /** Ancho de cada columna proporcional a su contenido, con un mínimo para que nada quede aplastado */
    private function columnWidths(array $rows, int $usable): array
    {
        $cols    = count($rows[0]);
        $weights = array_fill(0, $cols, 0.0);
        foreach ($rows as $r) {
            foreach ($r as $c => $text) {
                $weights[$c] += min(mb_strlen($this->plain($text)), 300);
            }
        }
        $weights = array_map(fn($w) => sqrt(max($w, 1)), $weights);   // sqrt suaviza: una columna larga no aplasta a las demás
        $total   = array_sum($weights);
        $min     = $usable / ($cols * 2.2);

        $widths = array_map(fn($w) => max($min, $usable * $w / $total), $weights);
        $scale  = $usable / array_sum($widths);
        return array_map(fn($w) => (int) floor($w * $scale), $widths);
    }

    private function rowLength(array $cells): int
    {
        return array_sum(array_map(fn($c) => mb_strlen($c), $cells));
    }

    private function addCellContent(Cell $cell, string $text, bool $header): void
    {
        $font = $header ? ['bold' => true, 'color' => 'FFFFFF', 'size' => 10] : ['size' => 10];
        $para = ['spaceAfter' => 0, 'spaceBefore' => 0, 'lineHeight' => 1.1];

        // <br> y viñetas "•" dentro de la celda pasan a ser líneas separadas
        $text  = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text  = preg_replace('/\s*•\s*/u', "\n• ", $text);
        $lines = array_values(array_filter(array_map('trim', explode("\n", $text)), fn($l) => $l !== ''));
        if (!$lines) $lines = [''];

        foreach ($lines as $line) {
            $line = preg_replace('/^- /', '• ', $line);
            $run  = $cell->addTextRun($para);
            $this->addInline($run, $line, $font);
        }
    }

    /** Escribe texto con **negrita**, *cursiva* y saltos <br> */
    private function addInline(AbstractContainer $run, string $text, array $base = []): void
    {
        $text  = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text  = strip_tags($text);
        $parts = preg_split('/(\*\*[^*]+\*\*|(?<![*\w])\*[^*\s][^*]*\*(?!\*))/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        foreach ($parts as $part) {
            $style = $base;
            if (str_starts_with($part, '**') && str_ends_with($part, '**') && mb_strlen($part) > 4) {
                $part  = mb_substr($part, 2, -2);
                $style += ['bold' => true] + (isset($base['color']) ? [] : ['color' => self::DARK_GREEN]);
            } elseif (str_starts_with($part, '*') && str_ends_with($part, '*') && mb_strlen($part) > 2) {
                $part  = mb_substr($part, 1, -1);
                $style += ['italic' => true];
            }
            $pieces = explode("\n", $part);
            foreach ($pieces as $k => $piece) {
                if ($k > 0) $run->addTextBreak();
                if ($piece !== '') $run->addText($piece, $style);
            }
        }
    }

    /** Texto sin marcas de markdown (para títulos y medir columnas) */
    private function plain(string $text): string
    {
        return trim(preg_replace(['/<br\s*\/?>/i', '/\*\*|__|`/', '/(?<!\w)\*(?!\s)|(?<!\s)\*(?!\w)/'], [' ', '', ''], $text));
    }
}
