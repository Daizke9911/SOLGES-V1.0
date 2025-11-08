<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class SuperAdminInfogeneral extends Component
{
    public function render()
    {
        return view('livewire.dashboard.super-admin-infogeneral')->layout('components.layouts.rbac');
    }
}
