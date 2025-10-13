<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubfuncionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subfunciones')->insert([
            ## FUNCION 1 - SALUD
            [
                'funcion_id' => 1,
                'codigo' => 'I-1',
                'descripcion' => 'Puesto o posta de salud, consultorio de profesional de la salud (no médico).',
                'riesgo_incendio' => 1,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 1,
                'codigo' => 'I-2',
                'descripcion' => 'Puesto o posta de salud, consultorio médico.',
                'riesgo_incendio' => 1,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 1,
                'codigo' => 'I-3',
                'descripcion' => 'Centro de salud, centro médico, centro médico especializado, policlínico.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 1,
                'codigo' => 'I-4',
                'descripcion' => 'Centro de salud o centro médico con camas de internamiento, tiene     usuarios no autosuficientes.',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 1,
                'codigo' => 'II',
                'descripcion' => ' Hospitales y clínicas de atención general',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 1,
                'codigo' => 'III',
                'descripcion' => 'Hospitales y clínicas de atención especializada. Institutos Especializados.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            ## FUNCION 2 - ENCUENTRO
            [
                'funcion_id' => 2,
                'codigo' => '2.1',
                'descripcion' => 'Edificación con carga de ocupantes hasta 50 personas.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 2,
                'codigo' => '2.2',
                'descripcion' => 'Edificación con carga de ocupantes mayor 50 personas.',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 2,
                'codigo' => '2.3',
                'descripcion' => 'La actividad de encuentro se realiza en el sótano',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 2,
                'codigo' => '2.4',
                'descripcion' => 'Edificación donde se desarrolla los siguientes usos: discotecas, casinos, tragamonedas, teatros, cines, salas de concierto, anfiteatros, auditorios, centros de convenciones, clubes, estadios, plazas de toro, coliseos, hipódromos,velódromos, autódromos, polideportivos, parques de diversión, zoológicos y templos.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            ## FUNCION 3 - HOSPEDAJE
            [
                'funcion_id' => 3,
                'codigo' => '3.1',
                'descripcion' => 'Establecimientos de Hospedaje de o hasta 3 estrellas y hasta 4 pisos, ecolodge, albergue o establecimiento ubicado en cualquiera de los cuatro (4) pisos, sin sótano.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 3,
                'codigo' => '3.2',
                'descripcion' => 'Establecimientos de Hospedaje de o hasta 3 estrellas y hasta 4 pisos, ecolodge, albergue o establecimiento ubicado en cualquiera de los cuatro (4) pisos, con sótano.',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 3,
                'codigo' => '3.3',
                'descripcion' => 'Hospedaje con más de cuatro (4) pisos, o establecimiento ubicado en piso superior al cuarto.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 3,
                'codigo' => '3.4',
                'descripcion' => 'Para todo tipo de hospedaje que cuenta con sótanos de estacionamiento con área mayor a 500 m2 o 250 m2 de depósitos o servicios generales.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            ## FUNCION 4 - EDUCACION
            [
                'funcion_id' => 4,
                'codigo' => '4.1',
                'descripcion' => 'Centros de educación inicial, primaria y secundaria, para personas con discapacidad: hasta tres (3) pisos.',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 4,
                'codigo' => '4.2',
                'descripcion' => 'Toda edificación educativa mayor a (3) pisos',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 4,
                'codigo' => '4.3',
                'descripcion' => 'Centro de Educación superior: Universidades, Institutos, Centros y Escuelas Superiores',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 4,
                'codigo' => '4.4',
                'descripcion' => 'Toda edificación remodelada o acondicionada para uso educativo.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            ## FUNCION 5 - INDUSTRIAL
            [
                'funcion_id' => 5,
                'codigo' => '5.1',
                'descripcion' => 'Taller artesanal, donde se transforma manualmente o con ayuda de herramientas manuales, materiales o sustancias en nuevos productos. El establecimiento puede incluir un área destinada a comercialización.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 5,
                'codigo' => '5.2',
                'descripcion' => 'Industria en General',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 5,
                'codigo' => '5.3',
                'descripcion' => 'Fábricas de productos explosivos o materiales relacionados. Talleres o Fabricas de productos pirotécnicos',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],

            ## FUNCION 6 - OFICINAS ADMINISTRATIVAS
            [
                'funcion_id' => 6,
                'codigo' => '6.1',
                'descripcion' => 'Edificación hasta cuatro (4) pisos y/o planta techada por piso igual o menor a 560 m2.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 6,
                'codigo' => '6.2',
                'descripcion' => 'Edificación con conformidad de obra de una antigüedad no mayor a (5) años donde se desarrolla la actividad o giro correspondiente al diseño o habiéndose realizado remodelaciones, ampliaciones o cambios de giros, se cuenta con conformidades de obra correspondientes.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 6,
                'codigo' => '6.3',
                'descripcion' => 'Establecimiento ubicado en cualquier piso de edificaciones cuya áreas e instalaciones de uso común cuentan con Certificado de ITSE vigente.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 6,
                'codigo' => '6.4',
                'descripcion' => 'Establecimiento ubicado en cualquier piso de edificaciones cuya áreas e instalaciones de uso común no cuentan con Certificado de ITSE vigente.',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 6,
                'codigo' => '6.5',
                'descripcion' => 'Edificación con cualquier número de pisos con planta techada por piso mayor 560m2',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],

            ## FUNCION 7 - COMERCIO
            [
                'funcion_id' => 7,
                'codigo' => '7.1',
                'descripcion' => 'Edificación hasta tres (3) pisos y/o área techada total hasta 750 m2',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 7,
                'codigo' => '7.2',
                'descripcion' => 'Módulos, stands o puestos, cuyo mercado de abastos, galerías comerciales o centro comercial cuenten con una licencia de funcionamiento en forma corporativa.',
                'riesgo_incendio' => 2,
                'riesgo_colapso' => 1,
            ],
            [
                'funcion_id' => 7,
                'codigo' => '7.3',
                'descripcion' => 'Edificación mayor a tres (3) pisos y/o área techada total mayor a 750m2',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 7,
                'codigo' => '7.4',
                'descripcion' => 'Áreas e instalaciones de usos común de las edificaciones de usos mixto, mercados de abastos, galerías comerciales y centros comerciales.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 7,
                'codigo' => '7.5',
                'descripcion' => 'Mercado minorista, mercado mayorista, supermercados, tiendas por departamentos, complejo comercial, centros comerciales y galerías comerciales.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 7,
                'codigo' => '7.6',
                'descripcion' => 'Comercialización de productos explosivos, pirotécnicos y relacionados.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            
            ## FUNCION 8 - ALMACEN
            [
                'funcion_id' => 8,
                'codigo' => '8.1',
                'descripcion' => 'Almacén o estacionamiento no techado: puede incluir áreas administrativas y de servicios techadas.',
                'riesgo_incendio' => 3,
                'riesgo_colapso' => 2,
            ],
            [
                'funcion_id' => 8,
                'codigo' => '8.2',
                'descripcion' => 'Almacén o estacionamiento techado.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],
            [
                'funcion_id' => 8,
                'codigo' => '8.3',
                'descripcion' => 'Almacén de productos explosivos, pirotécnicos y relacionados.',
                'riesgo_incendio' => 4,
                'riesgo_colapso' => 3,
            ],

            
        ]);
    }
}
