<?php

namespace Database\Seeders;

use App\Models\Solicitud;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitudesSeeder extends Seeder
{

    public function run(): void
    {
        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 1,
                'persona_cedula' => 1234567,
                'derecho_palabra' => true,
                'subcategoria' => 'alimentación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 123, Ciudad, País',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 1,
                'persona_cedula' => 1234567,
                'derecho_palabra' => true,
                'subcategoria' => 'alimentación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 123, Ciudad, País',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 1,
                'persona_cedula' => 1234567,
                'derecho_palabra' => true,
                'subcategoria' => 'alimentación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 123, Ciudad, País',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 2,
                'persona_cedula' => 1234567,
                'derecho_palabra' => false,
                'subcategoria' => 'alimentación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 123, Ciudad, País',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 2,
                'persona_cedula' => 1234567,
                'derecho_palabra' => true,
                'subcategoria' => 'maltrato animal',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 123, Ciudad, País',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 3,
                'persona_cedula' => 1234567,
                'derecho_palabra' => false,
                'subcategoria' => 'alimentación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 123, Ciudad, País',
                'fecha_creacion' => now(),
            ]
        );
    }
}
