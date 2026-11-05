<?php

namespace App\Support;

/**
 * Convierte las respuestas del asistente paso a paso del planificador en:
 *  - los placeholders que esperan los prompts de ModuleConfig ({{area}}, {{grade}}, ...)
 *  - un bloque de "condiciones reales del aula" que se añade al system prompt
 *  - instrucciones de modo revisión cuando el docente trae su propia propuesta
 */
class PlanningContext
{
    /** Campos de texto permitidos => longitud máxima */
    private const TEXT_FIELDS = [
        'nivel' => 30, 'num_estudiantes' => 30, 'nee' => 200,
        'area' => 60, 'tema' => 500, 'competencia' => 300, 'producto' => 200, 'bimestre' => 20,
        'recursos_otros' => 200, 'duracion_min' => 10, 'semanas' => 10,
        'zona' => 30, 'festividad' => 200, 'sabios' => 20,
        'modo' => 10, 'propuesta' => 2500,
    ];

    /** Campos de lista permitidos */
    private const LIST_FIELDS = ['grados', 'lengua', 'areas', 'recursos', 'actividades'];

    /** Lo que se recuerda en el perfil para no volver a preguntarlo */
    public const REMEMBERED = [
        'nivel', 'grados', 'num_estudiantes', 'lengua', 'nee', 'area',
        'recursos', 'recursos_otros', 'zona', 'actividades', 'sabios', 'duracion_min',
    ];

    /** Recursos que, si NO se marcan, la IA no debe asumir */
    private const KEY_RESOURCES = [
        'Electricidad', 'Internet', 'Celulares o tablets', 'Proyector o TV', 'Laptop o computadora', 'Laboratorio', 'Impresora',
    ];

    public static function sanitize(?array $raw): array
    {
        $raw   = $raw ?? [];
        $clean = [];

        foreach (self::TEXT_FIELDS as $key => $max) {
            if (isset($raw[$key]) && is_scalar($raw[$key])) {
                $value = trim(mb_substr((string) $raw[$key], 0, $max));
                if ($value !== '') $clean[$key] = $value;
            }
        }

        foreach (self::LIST_FIELDS as $key) {
            if (isset($raw[$key]) && is_array($raw[$key])) {
                $items = array_values(array_filter(array_map(
                    fn($v) => is_scalar($v) ? trim(mb_substr((string) $v, 0, 60)) : '',
                    array_slice($raw[$key], 0, 15)
                )));
                if ($items) $clean[$key] = $items;
            }
        }

        if (($clean['modo'] ?? '') !== 'revisar') {
            $clean['modo'] = 'generar';
            unset($clean['propuesta']);
        }

        return $clean;
    }

    public static function remembered(array $planning): array
    {
        return array_intersect_key($planning, array_flip(self::REMEMBERED));
    }

    /** Valores para los {{placeholders}} de los prompts de cada módulo */
    public static function placeholders(array $p, string $institution): array
    {
        $grados = implode(', ', $p['grados'] ?? []) ?: 'no especificado';
        $areas  = implode(', ', $p['areas'] ?? []) ?: ($p['area'] ?? 'las que mejor se articulen');
        $tema   = $p['tema'] ?? 'no indicado: propón uno pertinente al contexto de la comunidad';
        $local  = implode(', ', array_filter([
            $p['zona'] ?? null,
            isset($p['actividades']) ? 'actividades: ' . implode(', ', $p['actividades']) : null,
            $p['festividad'] ?? null,
        ])) ?: 'no especificado';

        return [
            'institution_context' => $institution,
            'area'              => $p['area'] ?? $areas,
            'grade'             => $grados,
            'bimester'          => $p['bimestre'] ?? 'no especificado',
            'weeks'             => $p['semanas'] ?? '9',
            'situation'         => $tema,
            'context_tags'      => $local,
            'duration'          => $p['duracion_min'] ?? '90',
            'methodology'       => 'la más adecuada al tema y a los recursos disponibles (justifícala en una línea)',
            'topic'             => $tema,
            'level'             => $p['nivel'] ?? 'no especificado',
            'grades'            => $grados,
            'areas'             => $areas,
            'duration_weeks'    => $p['semanas'] ?? '4',
            'problem_context'   => $tema,
            'competency'        => $p['competencia'] ?? 'la más pertinente del CNEB para el área y el producto',
            'evaluated_product' => $p['producto'] ?? 'no especificado: propón uno coherente',
        ];
    }

    /** Bloque que se añade al final del system prompt */
    public static function conditionsBlock(array $p): string
    {
        $lines = [];

        // Estudiantes
        $grados = $p['grados'] ?? [];
        if ($grados) {
            $lines[] = '- Grado(s): ' . implode(', ', $grados)
                . (count($grados) > 1 ? ' → AULA MULTIGRADO: diferencia actividades y desempeños por grado dentro de la misma sesión.' : '');
        }
        if (!empty($p['num_estudiantes'])) $lines[] = "- Número de estudiantes: {$p['num_estudiantes']}. Ajusta los agrupamientos a ese número.";
        if (!empty($p['lengua'])) {
            $lenguas = implode(', ', $p['lengua']);
            $lines[] = "- Lengua materna de los estudiantes: {$lenguas}."
                . (count(array_diff($p['lengua'], ['Castellano'])) ? ' Incluye palabras, preguntas o textos breves en esa lengua (enfoque EIB) cuando sea pertinente.' : '');
        }
        if (!empty($p['nee']) && mb_strtolower($p['nee']) !== 'no') {
            $lines[] = "- Estudiantes con necesidades educativas especiales: {$p['nee']}. Incluye adaptaciones concretas para ellos.";
        }

        // Recursos
        $recursos = $p['recursos'] ?? [];
        if (!empty($p['recursos_otros'])) $recursos[] = $p['recursos_otros'];
        if ($recursos) {
            $lines[] = '- Recursos con los que SÍ cuenta: ' . implode(', ', $recursos) . '.';
            $faltan = array_diff(self::KEY_RESOURCES, $p['recursos'] ?? []);
            if ($faltan) {
                $lines[] = '- NO cuenta con: ' . implode(', ', $faltan) . '. No propongas ninguna actividad que los necesite.';
            }
        }

        // Tiempo
        if (!empty($p['duracion_min'])) $lines[] = "- Duración de la sesión: {$p['duracion_min']} minutos. Los tiempos de la secuencia deben sumar exactamente eso.";
        if (!empty($p['semanas']))      $lines[] = "- Duración: {$p['semanas']} semanas.";
        if (!empty($p['bimestre']))     $lines[] = "- Bimestre: {$p['bimestre']}.";

        // Comunidad
        if (!empty($p['zona']))        $lines[] = "- Zona: {$p['zona']}.";
        if (!empty($p['actividades'])) $lines[] = '- Actividades económicas de la comunidad: ' . implode(', ', $p['actividades']) . '. Úsalas como contexto real de los problemas y ejemplos.';
        if (!empty($p['festividad']))  $lines[] = "- Calendario comunal / festividad cercana: {$p['festividad']}. Aprovéchala si encaja con el tema.";
        if (($p['sabios'] ?? '') === 'Sí') $lines[] = '- Hay sabios o yachaq en la comunidad que pueden participar: incluye un momento para que compartan sus saberes.';

        $block = "\n\n## CONDICIONES REALES DEL AULA (respétalas siempre)\n"
            . ($lines ? implode("\n", $lines) : '- No se indicaron condiciones específicas: asume recursos básicos (pizarra, cuadernos, materiales del entorno).')
            . "\n\n## CÓMO ESCRIBIR\n"
            . "- Escribe en lenguaje sencillo y directo, para que el docente lo aplique mañana sin dudas.\n"
            . "- Cada actividad debe poder hacerse con los recursos indicados arriba.\n"
            . "- Cuando el docente pida un ajuste, devuelve SIEMPRE el documento COMPLETO actualizado (no solo la parte cambiada), para que pueda exportarlo.";

        if (($p['modo'] ?? '') === 'revisar') {
            $block .= "\n\n## MODO REVISIÓN (el docente trae su propia propuesta)\n"
                . "El docente quiere aprender a planificar. NO reemplaces su idea: respétala y mejórala.\n"
                . "Empieza tu respuesta con esta sección:\n"
                . "## RETROALIMENTACIÓN A TU PROPUESTA\n"
                . "- ✅ Lo que está bien (2 o 3 puntos concretos)\n"
                . "- 🔧 Lo que puedes mejorar (máximo 4 puntos). En cada uno explica en una línea el PORQUÉ pedagógico (CNEB, enfoque por competencias, evaluación formativa).\n"
                . "- 💡 Un consejo para la próxima vez que planifiques tú solo/a.\n"
                . "Después genera el documento completo con la estructura indicada, construido sobre la propuesta del docente e incorporando las mejoras.";
        }

        return $block;
    }

    /** Título legible para "Recientes" */
    public static function title(string $moduleName, array $p): string
    {
        $detalle = $p['tema'] ?? $p['competencia'] ?? trim(($p['area'] ?? '') . ' ' . implode(', ', $p['grados'] ?? []));
        return $detalle ? "{$moduleName}: {$detalle}" : $moduleName;
    }
}
