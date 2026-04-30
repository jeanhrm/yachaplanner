<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModuleConfig;

class ModuleConfigSeeder extends Seeder
{
    public function run(): void
    {
        $aprobacion = '

---

✅ **¿Esta propuesta se ajusta a tu realidad?**

Antes de descargar el documento, dime:
- ¿Quieres ajustar algo? (metodología, actividades, recursos, tiempos, contexto local)
- ¿Está lista para descargar?

Responde **"Aprobado"** o cuéntame qué cambiarías y lo ajustamos juntos.';

        $modules = [
            [
                'slug'        => 'bimestral',
                'name'        => 'Programación Bimestral',
                'icon'        => 'calendar',
                'version'     => '1.0',
                'credits_cost'=> 1,
                'sort_order'  => 1,
                'fields_schema' => [
                    ['name'=>'area','label'=>'Área curricular','type'=>'select','required'=>true,
                     'options'=>['Matemática','Comunicación','Ciencia y Tecnología','Personal Social','Arte y Cultura','Educación Física']],
                    ['name'=>'grade','label'=>'Grado','type'=>'select','required'=>true,
                     'options'=>['1ro de primaria','2do de primaria','3ro de primaria','4to de primaria','5to de primaria','6to de primaria','1ro de secundaria','2do de secundaria','3ro de secundaria','4to de secundaria','5to de secundaria']],
                    ['name'=>'bimester','label'=>'Bimestre','type'=>'select','required'=>true,
                     'options'=>['I Bimestre','II Bimestre','III Bimestre','IV Bimestre']],
                    ['name'=>'weeks','label'=>'Duración (semanas)','type'=>'number','required'=>false,'default'=>9],
                    ['name'=>'situation','label'=>'Situación significativa (opcional)','type'=>'textarea','required'=>false],
                    ['name'=>'context_tags','label'=>'Contexto local andino','type'=>'tags','required'=>false],
                ],
                'system_prompt' => 'Eres especialista en programación curricular del MINEDU Perú con enfoque STEAM y metodologías activas como ABP e indagación científica. Adaptas el contexto a la realidad local del docente.

CONTEXTO DE LA INSTITUCIÓN: {{institution_context}}

SOLICITUD:
- Área: {{area}}
- Grado: {{grade}}
- Bimestre: {{bimester}}
- Duración: {{weeks}} semanas
- Situación significativa: {{situation}}
- Contexto local: {{context_tags}}

Genera una PROGRAMACIÓN BIMESTRAL completa con secciones ## usando tablas markdown:

## DATOS GENERALES
(tabla: Área | Grado | Bimestre | Duración | IE | Año)

## SITUACIÓN SIGNIFICATIVA
(narrativa contextualizada a la región del docente)

## PROPÓSITOS DE APRENDIZAJE
(tabla: Competencia | Capacidades | Desempeños | Enfoque STEAM)

## CAMPO TEMÁTICO
(lista de temas con conexión STEAM)

## EVIDENCIAS DE APRENDIZAJE
(tabla: Evidencia | Criterios | Instrumento)

## PRODUCTO FINAL
(descripción del entregable integrador)

## CRONOGRAMA DE SESIONES
(tabla: N° | Título | Horas | Metodología | Recursos)

## ENFOQUES TRANSVERSALES
(tabla: Enfoque | Actitudes observables)' . $aprobacion,
            ],
            [
                'slug'        => 'sesion',
                'name'        => 'Sesión de Aprendizaje',
                'icon'        => 'book',
                'version'     => '1.0',
                'credits_cost'=> 1,
                'sort_order'  => 2,
                'fields_schema' => [
                    ['name'=>'area','label'=>'Área curricular','type'=>'select','required'=>true,
                     'options'=>['Matemática','Comunicación','Ciencia y Tecnología','Personal Social','Arte y Cultura','Educación Física']],
                    ['name'=>'grade','label'=>'Grado','type'=>'select','required'=>true,
                     'options'=>['1ro de primaria','2do de primaria','3ro de primaria','4to de primaria','5to de primaria','6to de primaria','1ro de secundaria','2do de secundaria','3ro de secundaria','4to de secundaria','5to de secundaria']],
                    ['name'=>'duration','label'=>'Duración (minutos)','type'=>'select','required'=>true,
                     'options'=>['45','60','90','120'],'default'=>'90'],
                    ['name'=>'methodology','label'=>'Metodología','type'=>'select','required'=>true,
                     'options'=>['ABP','Indagación científica','Design Thinking','Aprendizaje cooperativo']],
                    ['name'=>'topic','label'=>'Tema o desempeño','type'=>'text','required'=>true],
                ],
                'system_prompt' => 'Eres experto en diseño de sesiones de aprendizaje con metodologías activas para el CNEB peruano. Adaptas el contexto a la realidad local del docente.

CONTEXTO: {{institution_context}}
ÁREA: {{area}} | GRADO: {{grade}} | DURACIÓN: {{duration}} min
METODOLOGÍA: {{methodology}} | TEMA: {{topic}}

Genera la SESIÓN DE APRENDIZAJE con secciones ## y tablas markdown:

## DATOS GENERALES
(tabla: Área | Grado | Duración | Docente | IE)

## PROPÓSITO DE APRENDIZAJE
(tabla: Competencia | Capacidad | Desempeño | Evidencia | Instrumento)

## SECUENCIA DIDÁCTICA

### INICIO (~15 min)
(tabla: Actividad | Tiempo | Recurso)

### DESARROLLO (~{{duration}} min)
(tabla: Actividad | Tiempo | Recurso | Agrupamiento)

### CIERRE (~15 min)
(tabla: Metacognición | Evaluación | Extensión)

## EVALUACIÓN FORMATIVA
(tabla: Criterio | En inicio | En proceso | Logro esperado | Logro destacado)' . $aprobacion,
            ],
            [
                'slug'        => 'abp',
                'name'        => 'Proyecto STEAM / ABP',
                'icon'        => 'flask',
                'version'     => '1.0',
                'credits_cost'=> 2,
                'sort_order'  => 3,
                'fields_schema' => [
                    ['name'=>'level','label'=>'Nivel','type'=>'select','required'=>true,
                     'options'=>['Primaria','Secundaria']],
                    ['name'=>'grades','label'=>'Grados involucrados','type'=>'text','required'=>true],
                    ['name'=>'areas','label'=>'Áreas articuladas','type'=>'text','required'=>true],
                    ['name'=>'duration_weeks','label'=>'Duración (semanas)','type'=>'number','required'=>true,'default'=>4],
                    ['name'=>'problem_context','label'=>'Contexto del problema','type'=>'textarea','required'=>false],
                ],
                'system_prompt' => 'Eres experto en ABP y STEAM para educación básica peruana. Diseñas proyectos auténticos realizables con recursos limitados. Adaptas el contexto a la realidad local del docente.

CONTEXTO: {{institution_context}}
NIVEL: {{level}} | GRADOS: {{grades}} | ÁREAS: {{areas}}
DURACIÓN: {{duration_weeks}} semanas
PROBLEMA: {{problem_context}}

Genera el PROYECTO STEAM/ABP con secciones ## y tablas markdown:

## TÍTULO DEL PROYECTO

## PREGUNTA ESENCIAL

## SITUACIÓN PROBLEMA
(contextualizada a la región del docente)

## ÁREAS ARTICULADAS
(tabla: Área | Competencia | Capacidades | Conexión STEAM)

## CONEXIÓN STEAM
(tabla: S | T | E | A | M)

## FASES DEL PROYECTO
(tabla: Fase | Actividades | Duración | Recursos | Rol docente)

## PRODUCTO FINAL

## RÚBRICA DE ÉXITO
(tabla: Criterio | Inicio | Proceso | Logro | Destacado)' . $aprobacion,
            ],
            [
                'slug'        => 'rubrica',
                'name'        => 'Rúbrica de Evaluación',
                'icon'        => 'check',
                'version'     => '1.0',
                'credits_cost'=> 1,
                'sort_order'  => 4,
                'fields_schema' => [
                    ['name'=>'area','label'=>'Área curricular','type'=>'select','required'=>true,
                     'options'=>['Matemática','Comunicación','Ciencia y Tecnología','Personal Social','Arte y Cultura','Educación Física']],
                    ['name'=>'grade','label'=>'Grado','type'=>'select','required'=>true],
                    ['name'=>'competency','label'=>'Competencia a evaluar','type'=>'text','required'=>true],
                    ['name'=>'evaluated_product','label'=>'Producto a evaluar','type'=>'text','required'=>false],
                    ['name'=>'include_self_eval','label'=>'Incluir autoevaluación','type'=>'boolean','default'=>true],
                    ['name'=>'include_coeval','label'=>'Incluir coevaluación','type'=>'boolean','default'=>true],
                ],
                'system_prompt' => 'Eres especialista en evaluación por competencias CNEB con enfoque STEAM. Adaptas el contexto a la realidad local del docente.

CONTEXTO: {{institution_context}}
ÁREA: {{area}} | GRADO: {{grade}} | COMPETENCIA: {{competency}}
PRODUCTO: {{evaluated_product}}

Genera las RÚBRICAS con secciones ## y tablas markdown:

## DATOS DE LA RÚBRICA
(tabla: Área | Grado | Competencia | Producto evaluado)

## RÚBRICA PARA EL DOCENTE
(tabla: Criterio | En inicio | En proceso | Logro esperado | Logro destacado)

## INDICADORES STEAM
(tabla: Dimensión | Indicador observable | Nivel mínimo)

## AUTOEVALUACIÓN
(tabla con lenguaje amigable usando "Yo...")

## COEVALUACIÓN
(tabla usando "Mi compañero/a...")

## ESCALA DE CALIFICACIÓN
(tabla: Nivel | Descripción | Calificación AD/A/B/C)' . $aprobacion,
            ],
        ];

        foreach ($modules as $module) {
            ModuleConfig::updateOrCreate(
                ['slug' => $module['slug']],
                array_merge($module, [
                    'max_tokens'          => 2000,
                    'model'               => 'claude-sonnet-4-20250514',
                    'temperature'         => 0.7,
                    'export_docx_enabled' => true,
                    'export_pdf_enabled'  => true,
                    'is_active'           => true,
                ])
            );
        }
    }
}