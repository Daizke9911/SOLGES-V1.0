<?php

namespace App\Livewire\Dashboard;

use App\Models\Categorias;
use App\Models\Estatus;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;

class SuperAdminReportes extends Component
{
    public $selectedPeriod = 'last_30_days';
    public $selectedPeriodChart2 = 'last_30_days';
    public $selectedPeriodChart3 = 'last_30_days';
    public $selectedPeriodChart4 = 'last_30_days';
    
    public $solicitudes = 0;
    public $solicitudesCategorias = '';
    public $showVista = 0;

    public $estatusSolicitud = 0, $estatusName = 'Todos';

    public function loadData()
    {
        $this->solicitudes = Solicitud::with(['subcategoriaRelacion', 'comunidadRelacion', 'reunionRelacion', 'visitasRelacion'])->orderBy('fecha_creacion', 'desc')
            ->get();

        $this->solicitudesCategorias = Solicitud::query()
            ->join('sub_categorias', 'solicitudes.subcategoria', '=', 'sub_categorias.subcategoria')
            ->join('categorias', 'sub_categorias.categoria', '=', 'categorias.categoria')
            ->select('categorias.categoria as nombre_categoria', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('categorias.categoria')
            ->orderBy('total', 'desc')
            ->get();
    }

    public function mount()
    {
        $this->loadData();
    }

    /* FILTROS DE LAS GRAFICAS */
    public function updatedSelectedPeriod()
    {
        $this->updateChart();
    }
    public function updatedSelectedPeriodChart2()
    {
        $this->updateChart2();
    }
    public function updatedSelectedPeriodChart3()
    {
        $this->updateChart3();
    }

    #[On('refreshChart1')]
    public function updateChart()
    {
        $datosFiltrados = $this->updateChart1();
        $this->dispatch('chart-updated', id: 'chart1', datos: $datosFiltrados);
    }

    #[On('refreshChart2')]
    public function updateChart2()
    {
        $datosFiltrados = $this->getDatosChart2();
        $this->dispatch('chart-updated', id: 'chart2', datos: $datosFiltrados);
    }

    #[On('refreshChart3')]
    public function updateChart3()
    {
        $datosFiltrados = $this->getDatosChart3();
        $this->dispatch('chart-updated', id: 'chart3', datos: $datosFiltrados);
    }

    #[On('refreshChart4')]
    public function updateChart4()
    {
        $datosFiltrados = $this->getDatosChart4();
        $this->dispatch('chart-updated', id: 'chart4', datos: $datosFiltrados);
    }

    public function updateChart1()
    {
        $estado = '';
        $titulo = '';
        $colors = [];
        $labels = [];

        switch ($this->selectedPeriod) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $this->selectedPeriod = 'today';
                break;
            case 'last_7_days':
                $startDate = now()->subDays(7)->startOfDay();
                $endDate = now()->endOfDay();
                $this->selectedPeriod = 'last_7_days';
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                $this->selectedPeriod = 'last_30_days';
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $this->selectedPeriod = 'this_month';
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $this->selectedPeriod = 'this_year';
                break;
        }

        switch ($this->estatusSolicitud) {
            case 1:
                $estado = 1;
                $titulo = 'Fecha con Más Pendientes: ';
                $colors = [
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'bgColor' => 'rgba(255, 206, 86, 0.2)'
                ];
                break;
            case 2:
                $estado = 2;
                $titulo = 'Fecha con Más Aprobadas: ';
                $colors = [
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'bgColor' => 'rgba(75, 192, 192, 0.2)'
                ];
                
                break;
            case 3:
                $estado = 3;
                $titulo = 'Fecha con Más Rechazadas: ';
                $colors = [
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'bgColor' => 'rgba(255, 99, 132, 0.2)'
                ];
                break;
            default:
                $datosGenerales = $this->getDatosChart1($startDate, $endDate);
                $this->dispatch('chart-updated', id: $datosGenerales['canvasId'], datos: $datosGenerales);
                return;
        }

        $resultados = Solicitud::where('estatus', $estado)
            ->whereBetween('fecha_creacion', [$startDate, $endDate])
            ->select(DB::raw('DATE(fecha_creacion) as fecha'), DB::raw('count(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        
        $labels = $resultados->pluck('fecha')->toArray();
        $values = $resultados->pluck('total')->toArray();
        
        $data = [
            'canvasId' => 'chart1',
            'labels' => $labels,
            'values' => $values,
            'titulo' => $titulo . (count($labels) > 0 ? $labels[array_search(max($values), $values)] : 'N/A'),
            'tipo' => 'bar',
            'borderColor' => $colors['borderColor'],
            'bgColor' => $colors['bgColor']
        ];
        
        $this->dispatch('chart-updated', id: $data['canvasId'], datos: $data);
    }

    private function getDatosChart1($startDate, $endDate){
        $resultados = Solicitud::whereBetween('fecha_creacion', [$startDate, $endDate])
            ->get();

        $mapaEstatus = [
            1 => 'Pendiente',
            2 => 'Aprobada',
            3 => 'Rechazada',
        ];

        $conteoInicial = [
            'Pendiente' => 0,
            'Aprobada' => 0,
            'Rechazada' => 0,
        ];

        $conteoPorEstado = $resultados->mapToGroups(function ($item) use ($mapaEstatus) {
            $etiqueta = $mapaEstatus[$item->estatus] ?? 'Otro'; 
            
            return [$etiqueta => 1];
        })->map(function ($group) {
            return $group->count();
        })->toArray();

        $valoresFinales = array_values(array_merge($conteoInicial, $conteoPorEstado));

        $labelsFinales = array_keys($conteoInicial);

        return [
            'canvasId' => 'chart1',
            'labels' => $labelsFinales,
            'values' => $valoresFinales,
            'tipo' => 'bar',
            'titulo' => 'Mayor Porcentaje',
            'bgColor' => ['rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
            'borderColor' => ['rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
        ];
    }

    private function getDatosChart2()
    {
        switch ($this->selectedPeriodChart2) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'last_7_days':
                $startDate = now()->subDays(7)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }

        $solicitudes = DB::table('solicitudes')
            ->whereBetween('fecha_creacion', [$startDate, $endDate])
            ->select(DB::raw('DATE(fecha_creacion) as fecha'), DB::raw('count(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        $labels = $solicitudes->pluck('fecha')->toArray();
        $values = $solicitudes->pluck('total')->toArray();

        if($this->selectedPeriodChart2 === 'today'){
            $chartType = 'bar';
        }else{
            $chartType = 'line';
        }

        return [
            'canvasId' => 'chart2',
            'labels' => $labels,
            'values' => $values,
            'titulo' => 'Fecha con Más Solicitudes Ingresadas: ' . (count($labels) > 0 ? $labels[array_search(max($values), $values)] : 'N/A'),
            'tipo' => $chartType,
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'bgColor' => 'rgba(75, 192, 192, 0.2)',
        ];
    }

    public function getDatosChart3()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        switch ($this->selectedPeriodChart3) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'last_7_days':
                $startDate = now()->subDays(7)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }
        
        $resultados = Solicitud::query()
            ->whereBetween('solicitudes.fecha_creacion', [$startDate, $endDate])
            ->join('sub_categorias', 'solicitudes.subcategoria', '=', 'sub_categorias.subcategoria')
            ->join('categorias', 'sub_categorias.categoria', '=', 'categorias.categoria')
            ->select('categorias.categoria as nombre_categoria', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('categorias.categoria')
            ->orderBy('total', 'desc')
            ->get();

            
        $labels = $resultados->pluck('nombre_categoria')->toArray();
        $values = $resultados->pluck('total')->toArray();

        $labelsFinales = array_map('ucfirst', $labels);
        
        $bgColors = [
            'rgba(255, 99, 133, 0.31)', 'rgba(54, 163, 235, 0.31)', 'rgba(255, 207, 86, 0.29)', 
            'rgba(75, 192, 192, 0.3)', 'rgba(153, 102, 255, 0.29)', 'rgba(255, 160, 64, 0.29)'
        ];
        $borderColors = [
            'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 
            'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
        ];

        return [
            'canvasId' => 'chart3',
            'labels' => $labelsFinales,
            'values' => $values,
            'tipo' => 'bar',
            'titulo' => 'Distribución de Solicitudes por Categoría',
            'bgColor' => array_slice($bgColors, 0, count($labels)),
            'borderColor' => array_slice($borderColors, 0, count($labels)),
        ];
    }

    public function getDatosChart4()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        switch ($this->selectedPeriodChart4) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'last_7_days':
                $startDate = now()->subDays(7)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }
        
        $resultados = Solicitud::query()
            ->whereBetween('solicitudes.fecha_creacion', [$startDate, $endDate])
            ->join('comunidades', 'solicitudes.comunidad', '=', 'comunidades.comunidad')
            ->join('parroquias', 'comunidades.parroquia', '=', 'parroquias.parroquia')
            ->select('parroquias.parroquia as nombre_parroquia', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('parroquias.parroquia')
            ->orderBy('total', 'desc')
            ->get();

            
        $labels = $resultados->pluck('nombre_parroquia')->toArray();
        $values = $resultados->pluck('total')->toArray();

        $labelsFinales = array_map('ucfirst', $labels);
        
        $bgColors = [
            'rgba(75, 192, 192, 0.3)', 'rgba(153, 102, 255, 0.29)', 'rgba(255, 160, 64, 0.29)',
            'rgba(255, 99, 133, 0.31)', 'rgba(54, 163, 235, 0.31)', 'rgba(255, 207, 86, 0.29)', 
        ];
        $borderColors = [
            'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)',
            'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 
        ];

        return [
            'canvasId' => 'chart4',
            'labels' => $labelsFinales,
            'values' => $values,
            'tipo' => 'bar',
            'titulo' => 'Distribución de Solicitudes por Parroquia',
            'bgColor' => array_slice($bgColors, 0, count($labels)),
            'borderColor' => array_slice($borderColors, 0, count($labels)),
        ];
    }

    public function cambiarVista($vista)
    {
        $this->showVista = $vista;
        
        $this->dispatch('cambiarVistaEvento');
    }

    #[On('cambiarVistaEvento')]
    public function renderChartVistaEvento(){
        switch($this->showVista){
            case 0:
                $this->updateChart();
                $this->updateChart2();
                break;
            case 1:
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();

                $resultados = Solicitud::whereBetween('fecha_creacion', [$startDate, $endDate])
                    ->get();

                $conteoInicial = [
                    'Pendiente' => 0,
                    'Aprobada' => 0,
                    'Rechazada' => 0,
                    'Asignada' => 0,
                ];

                $conteoPorEstado = $resultados->mapToGroups(function ($item) {
                    return [$item->estatus => 1];
                })->map(function ($group) {
                    return $group->count();
                })->toArray();

                $valoresFinales = array_values(array_merge($conteoInicial, $conteoPorEstado));

                $labelsFinales = ['Pendientes', 'Aprobadas', 'Rechazadas', 'Asignadas'];
                
                $data = [
                    'canvasId' => 'chartVisitas',
                    'labels' => $labelsFinales,
                    'values' => $valoresFinales,
                    'tipo' => 'bar',
                    'titulo' => 'Mayor Porcentaje',
                    'bgColor' => ['rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                    'borderColor' => ['rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)']
                    ];

                $this->dispatch('chart-updated', id: 'chartVisitas', datos: $data);
                break;
            case 2:
                break;
            case 3:
                break;
            default:
                break;
        }
    }

    private function getSolicitudesEstaSemana()
    {
        $inicioSemana = Carbon::now()->startOfWeek();
        
        $finSemana = Carbon::now()->endOfWeek();

        return Solicitud::whereBetween('fecha_creacion', [$inicioSemana, $finSemana])
                                ->get();
    }

    private function getSolicitudesEsteMes()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        
        $finMes = Carbon::now()->endOfMonth();

        return Solicitud::whereBetween('fecha_creacion', [$inicioMes, $finMes])
                                ->get();
    }

    private function getSolicitudesEsteAno()
    {
        $inicioAno = Carbon::now()->startOfYear();
        
        $finAno = Carbon::now()->endOfYear();

        return Solicitud::whereBetween('fecha_creacion', [$inicioAno, $finAno])
                                ->get();
    }
    
    private function getSolicitudesPorCategoria($startDate, $endDate)
    {
        $resultados = Solicitud::query()
            ->join('sub_categorias', 'solicitudes.subcategoria', '=', 'sub_categorias.subcategoria')
            ->join('categorias', 'sub_categorias.categoria', '=', 'categorias.categoria')
            ->select('categorias.categoria as nombre_categoria', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('categorias.categoria')
            ->orderBy('total', 'desc')
            ->get();

        return $resultados;
    }
    
    private function getTop5Subcategorias($startDate, $endDate)
    {
        $resultados = Solicitud::query()
            ->join('sub_categorias', 'solicitudes.subcategoria', '=', 'sub_categorias.subcategoria')
            ->select('sub_categorias.subcategoria as nombre_subcategoria', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('sub_categorias.subcategoria')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return $resultados;
    }

    private function getUltimas5Subcategorias()
    {
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        $resultados = Solicitud::query()
            ->whereBetween('solicitudes.fecha_creacion', [$startDate, $endDate])
            ->join('sub_categorias', 'solicitudes.subcategoria', '=', 'sub_categorias.subcategoria')
            ->select('sub_categorias.subcategoria as nombre_subcategoria', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('sub_categorias.subcategoria')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return $resultados;
    }

    private function getSolicitudesPorParroquia($startDate, $endDate)
    {
        $resultados = Solicitud::query()
            ->join('comunidades', 'solicitudes.comunidad', '=', 'comunidades.comunidad')
            ->join('parroquias', 'comunidades.parroquia', '=', 'parroquias.parroquia')
            ->select('parroquias.parroquia as nombre_parroquia', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('parroquias.parroquia')
            ->orderBy('total', 'desc')
            ->get();

        return $resultados;
    }

    private function getTop5Comunidades($startDate, $endDate)
    {
        $resultados = Solicitud::query()
            ->join('comunidades', 'solicitudes.comunidad', '=', 'comunidades.comunidad')
            ->select('comunidades.comunidad as nombre_comunidad', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('comunidades.comunidad')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return $resultados;
    }

        private function getUltimas5Comunidades()
    {
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        $resultados = Solicitud::query()
            ->whereBetween('solicitudes.fecha_creacion', [$startDate, $endDate])
            ->join('comunidades', 'solicitudes.comunidad', '=', 'comunidades.comunidad')
            ->select('comunidades.comunidad as nombre_comunidad', DB::raw('count(solicitudes.solicitud_id) as total'))
            ->groupBy('comunidades.comunidad')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return $resultados;
    }

    public function ordenEstados($estado){
        
        $this->estatusSolicitud = $estado;

        $this->updateChart1($estado);

        if($estado === 0){
            $this->estatusName = 'Todos';
        }else{
            $name = Estatus::find($estado);
            $this->estatusName = Str::title($name->estatus);
        }
    }

    public function render()
    {
        $estatus = Estatus::where('sector_sistema', 'solicitudes')->get();

        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        $solicitudesPorCategoria = $this->getSolicitudesPorCategoria($startDate, $endDate);
        $top5Subcategorias = $this->getTop5Subcategorias($startDate, $endDate);

        $solicitudesParroquias = $this->getSolicitudesPorParroquia($startDate, $endDate);
        $top5Comunidades = $this->getTop5Comunidades($startDate, $endDate);

        return view('livewire.dashboard.super-admin-reportes', [
            'estatus' => $estatus,
            'solicitudesSemana' => $this->getSolicitudesEstaSemana(),
            'solicitudesMes' => $this->getSolicitudesEsteMes(),
            'solicitudesAno' => $this->getSolicitudesEsteAno(),
            'solicitudesPorCategoria' => $solicitudesPorCategoria,
            'top5Subcategorias' => $top5Subcategorias,
            'ultimas5Subcategorias' => $this->getUltimas5Subcategorias(),
            'solicitudesParroquias' => $solicitudesParroquias,
            'top5Comunidades' => $top5Comunidades,
            'ultimas5Comunidades' => $this->getUltimas5Comunidades(),
        ])->layout('components.layouts.rbac');
    }
}
