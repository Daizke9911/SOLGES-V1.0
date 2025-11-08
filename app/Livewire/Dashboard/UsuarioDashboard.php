<?php

namespace App\Livewire\Dashboard;

use App\Models\Solicitud;
use App\Models\Visita;
use App\Models\Ambito;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'dashboard';
    public $solicitudes;

    public function mount()
    {
        $this->activeTab = request()->get('tab', 'dashboard');
        $this->loadData();
    }

    public function loadData()
    {
        $this->solicitudes = Solicitud::with('estatusRelacion', 'subcategoriaRelacion', 'comunidadRelacion')
            ->where('persona_cedula', Auth::user()->persona_cedula)
            ->orderBy('fecha_creacion', 'desc')
            ->get();
            
        $this->visitas = Visita::with(['ambito'])
            ->where('persona_cedula', Auth::user()->persona_cedula)
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function redirectToCreateSolicitud()
    {
        session()->flash('open-tab-event', 'create');
        return redirect()->route('dashboard.usuario.solicitud');
    }

    public function render()
    {
        return view('livewire.dashboard.usuario-dashboard')->layout('components.layouts.rbac');
    }

    // Add method to redirect to CRUD component
    public function redirectToCrud()
    {
        return redirect()->route('dashboard.usuario', ['tab' => 'crud']);
    }
}