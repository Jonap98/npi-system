<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MovimientosModel;

class Movimientos extends Component
{
    public function render()
    {
        return view('livewire.movimientos');
    }

    public function store() {
        
        $this->validate([
            'proyecto' => 'required',
            'numero_parte' => 'required',
            'descripcion' => 'required',
            'cantidad' => 'required',
            'unidad_de_medida' => 'required',
            // 'tipo' => 'required',
            
        ]);

        MovimientosModel::create([
            'proyecto' => $this->proyecto,
            'numero_parte' => $this->numero_parte,
            'descripcion' => $this->descripcion,
            'cantidad' => $this->cantidad,
            'unidad_de_medida' => $this->unidad_de_medida,
            'tipo' => $this->tipo,
            'comentario' => $this->comentario,
        ]);

        $this->reset();
    }
}
