<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica;

class ActividadComercioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            "Venta de vehículos automotores",
            "Mantenimiento y reparación de vehículos automotores",
            "Venta de partes, piezas y accesorios para vehículos automotores",
            "Venta, mantenimiento y reparación de motocicletas y sus partes, piezas y accesorios",
            "Venta al por mayor a cambio de una retribución o por contrato",
            "Venta al por mayor de materias primas agropecuarias y animales vivos",
            "Venta al por mayor de alimentos, bebidas y tabaco",
            "Venta al por mayor de productos textiles, prendas de vestir y calzado",
            "Venta al por mayor de otros enseres domésticos",
            "Venta al por mayor de ordenadores (computadoras), equipo periférico y programas de informática",
            "Venta al por mayor de equipo, partes y piezas electrónicos y de telecomunicaciones",
            "Venta al por mayor de maquinaria, equipo y materiales agropecuarios",
            "Venta al por mayor de otros tipos de maquinaria y equipo",
            "Venta al por mayor de combustibles sólidos, líquidos y gaseosos y productos conexos",
            "Venta al por mayor de metales y minerales metaliferos",
            "Venta al por mayor de materiales de construcción, artículos de ferretería y equipo y materiales de fontanería y calefacción",
            "Venta al por mayor de desperdicios, desechos y chatarra y otros productos no considerados principales",
            "Venta al por mayor no especializada",
            "Venta al por menor en comercios no especializados con predominio de la venta de alimentos, bebidas o tabaco",
            "Otras actividades de venta al por menor en comercios no especializados",
            "Venta al por menor de alimentos en comercios especializados",
            "Venta al por menor de bebidas en comercios especializados",
            "Venta al por menor de productos de tabaco en comercios especializados",
            "Venta al por menor de combustibles para vehículos automotores en comercios especializados",
            "Venta al por menor de ordenadores (computadoras), equipo periférico, programas de informática y equipo de telecomunicaciones en comercios especializados",
            "Venta al por menor de equipo de sonido y de video en comercios especializados",
            "Venta al por menor de productos textiles en comercios especializados",
            "Venta al por menor de artículos de ferretería, pinturas y productos de vidrio en comercios especializados",
            "Venta al por menor de tapices, alfombras y cubrimientos para paredes y pisos en comercios especializados",
            "Venta al por menor de aparatos eléctricos de uso doméstico, muebles, equipo de iluminación y otros enseres domésticos en comercios especializados",
            "Venta al por menor de libros, periódicos y artículos de papelería en comercios especializados",
            "Venta al por menor de grabaciones de música y de video en comercios especializados",
            "Venta al por menor de equipo de deporte en comercios especializados",
            "Venta al por menor de juegos y juguetes en comercios especializados",
            "Venta al por menor de prendas de vestir, calzado y artículos de cuero en comercios especializados",
            "Venta al por menor de productos farmacéuticos y médicos, cosméticos y artículos de tocador en comercios especializados",
            "Venta al por menor de otros productos nuevos en comercios especializados",
            "Venta al por menor de artículos de segunda mano",
            "Venta al por menor de alimentos, bebidas y tabaco en puestos de venta y mercados",
            "Venta al por menor de productos textiles, prendas de vestir y calzado en puestos de venta y mercados",
            "Venta al por menor de otros productos en puestos de venta y mercados",
            "Venta al por menor por correo y por internet",
            "Otras actividades de venta al por menor no realizadas en comercios, puestos de venta o mercados",
            "Manipulación de la carga",
            "Actividades postales",
            "Actividades de mensajería",
            "Actividades de restaurantes y de servicio móvil de comidas",
            "Suministro de comidas por encargo",
            "Otras actividades de servicio de comidas",
            "Actividades de servicio de bebidas",
            "Lavado y limpieza, incluida la limpieza en seco, de productos textiles y de piel",
            "Peluquería y otros tratamientos de belleza",
        ];

        foreach ($actividades as $actividad) {
            ActividadEconomica::create([
                'descripcion' => $actividad,
                'funcion_id' => 7,
            ]);
        }
    }
}
