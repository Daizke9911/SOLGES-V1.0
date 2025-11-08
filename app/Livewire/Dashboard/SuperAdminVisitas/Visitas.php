<?php

namespace App\Livewire\Dashboard\SuperAdminVisitas;

use App\Models\User;
use App\Models\Personas;
use App\Models\Solicitud;
use Livewire\WithPagination;
use Livewire\Component;


class Visitas extends Component
{
    use WithPagination;

    public $search;
    public $solicitud;
    public $paso=1;
    public $selectedSolicitud;
    
    public $MINIMUN_PERSON=1;
    public $MAXIMUN_PERSON=5;
    
    public $selectedSolicitudId = null;
    public $personasSelected = [];

    protected $paginationTheme = 'tailwind';

    public function solicitudAproved()
    {
        return Solicitud::query()
            ->where('asignada_visita', null) 
            ->whereNull('deleted_at')
            
            ->when($this->search, function ($query) {
                $query->where('titulo', 'like', '%' . $this->search . '%')
                ->orWhere('solicitud_id', 'like', '%' . $this->search . '%');
                
            })
            
            ->paginate(5);
    }    
   
    public function selectSolicitud($solicitudId)
    {
        $solicitud = Solicitud::where('solicitud_id', $solicitudId)
                              ->whereNull('deleted_at')
                              ->first();

        if ($solicitud) {
            $this->selectedSolicitudId = $solicitudId;
            $this->selectedSolicitud = $solicitud;
            $this->paso = 2; 

            $this->dispatch('show-toast', [
                'message' => 'Solicitud seleccionada exitosamente.' ,
                'type' => 'success']);
        } else {
            $this->dispatch('error-toast', [
                'message' => 'Ha ocurrido un error al seleccionar la solicitud seleccionada exitosamente.' ,
                'type' => 'success']);
        }
    }

        public function getPersonalAproved()
    {
        return User::with(["persona" , "roleModel"])
        ->where("role" , "<" , 3)
        ->get();
    }

    public function previousStep()
    {
        $this->paso--;
    }
    public function render()
    {
        return view('livewire.dashboard.super-admin-visitas.visitas' , 
        ['solicitudAproved' => $this->solicitudAproved(), 
         'visitors' => $this->getPersonalAproved()]);
    }
}
