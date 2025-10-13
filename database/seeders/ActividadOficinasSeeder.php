<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica;

class ActividadOficinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            "Edición de libros",
            "Edición de directorios y listas de correo",
            "Edición de periódicos, revistas y otras publicaciones periódicas",
            "Otras actividades de edición",
            "Edición de programas informáticos",
            "Actividades de producción de películas cinematográficas, videos y programas de televisión",
            "Actividades de postproducción de películas cinematográficas, videos y programas de televisión",
            "Actividades de distribución de películas cinematográficas, videos y programas de televisión",
            "Actividades de exhibición de películas cinematográficas y cintas de video",
            "Actividades de grabación de sonido y edición de música",
            "Transmisiones de radio",
            "Programación y transmisiones de televisión",
            "Actividades de telecomunicaciones alámbricas",
            "Actividades de telecomunicaciones inalámbricas",
            "Actividades de telecomunicaciones por satélite",
            "Otras actividades de telecomunicaciones",
            "Programación informática",
            "Consultoría de informática y gestión de instalaciones informáticas",
            "Otras actividades de tecnología de la información y de servicios informáticos",
            "Procesamiento de datos, hospedaje y actividades conexas",
            "Portales web",
            "Actividades de agencias de noticias",
            "Otras actividades de servicios de información no consideradas principales",
            "Banca central",
            "Otros tipos de intermediación monetaria",
            "Actividades de sociedades de cartera",
            "Fondos y sociedades de inversión y entidades financieras similares",
            "Arrendamiento financiero",
            "Otras actividades de concesión de crédito",
            "Otras actividades de servicios financieros, excepto las de seguros y fondos de pensiones, no consideradas principales",
            "Seguros de vida",
            "Seguros generales",
            "Reaseguros",
            "Fondos de pensiones",
            "Administración de mercados financieros",
            "Corretaje de valores y de contratos de productos básicos",
            "Otras actividades auxiliares de las actividades de servicios financieros",
            "Evaluación de riesgos y daños",
            "Actividades de agentes y corredores de seguros",
            "Otras actividades auxiliares de las actividades de seguros y fondos de pensiones",
            "Actividades de gestión de fondos",
            "Actividades inmobiliarias realizadas con bienes propios o arrendados",
            "Actividades inmobiliarias realizadas a cambio de una retribución o por contrato",
            "Actividades jurídicas",
            "Actividades de contabilidad, teneduría de libros y auditoría, consultoría fiscal",
            "Actividades de oficinas principales",
            "Actividades de consultoría de gestión",
            "Actividades de arquitectura e ingeniería y actividades conexas de consultoría técnica",
            "Ensayos y análisis técnicos",
            "Investigaciones y desarrollo experimental en el campo de las ciencias naturales y la ingeniería",
            "Investigaciones y desarrollo experimental en el campo de las ciencias sociales y las humanidades",
            "Publicidad",
            "Estudios de mercado y encuestas de opinión pública",
            "Actividades especializadas de diseño",
            "Actividades de fotografía",
            "Otras actividades profesionales, científicas y técnicas no consideradas principales",
            "Actividades veterinarias",
            "Alquiler y arrendamiento de vehículos automotores",
            "Alquiler y arrendamiento de equipo recreativo y deportivo",
            "Alquiler de cintas de video y discos",
            "Alquiler y arrendamiento de otros efectos personales y enseres domésticos",
            "Alquiler y arrendamiento de otros tipos de maquinaria, equipo y bienes tangibles",
            "Arrendamiento de propiedad intelectual y productos similares, excepto obras protegidas por derechos de autor",
            "Actividades de agencias de empleo",
            "Actividades de agencias de empleo temporal",
            "Otras actividades de donación de recursos humanos",
            "Actividades de agencias de viajes",
            "Actividades de operadores turísticos",
            "Otros servicios de reservas y actividades conexas",
            "Actividades de seguridad privada",
            "Actividades de servicios de sistemas de seguridad",
            "Actividades de investigación",
            "Actividades combinadas de apoyo a instalaciones",
            "Limpieza general de edificios",
            "Otras actividades de limpieza de edificios y de instalaciones industriales",
            "Actividades de paisajismo y servicios de mantenimiento conexos",
            "Actividades combinadas de servicios administrativos de oficina",
            "Fotocopiado, preparación de documentos y otras actividades especializadas de apoyo de oficina",
            "Actividades de centros de llamadas",
            "Organización de convenciones y exposiciones comerciales",
            "Actividades de agencias de cobro y agencias de calificación crediticia",
            "Actividades de envasado y empaquetado",
            "Otras actividades de servicios de apoyo a las empresas n.c.p.",
        ];

        foreach ($actividades as $actividad) {
            ActividadEconomica::create([
                'descripcion' => $actividad,
                'funcion_id' => 6,
            ]);
        }
    }
}
