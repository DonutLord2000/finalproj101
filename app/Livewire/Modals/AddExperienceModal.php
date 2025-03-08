<?php

namespace App\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use App\Models\Experience;

class AddExperienceModal extends ModalComponent
{
    public $title;
    public $company;
    public $employment_type;
    public $start_date;
    public $end_date;
    public $current_role = false;
    public $location;
    public $location_type;
    public $description;

    protected $rules = [
        'title' => 'required|string|max:255',
        'company' => 'required|string|max:255',
        'employment_type' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'current_role' => 'boolean',
        'location' => 'required|string|max:255',
        'location_type' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function save()
    {
        $this->validate();

        auth()->user()->experiences()->create([
            'title' => $this->title,
            'company' => $this->company,
            'employment_type' => $this->employment_type,
            'start_date' => $this->start_date,
            'end_date' => $this->current_role ? null : $this->end_date,
            'current_role' => $this->current_role,
            'location' => $this->location,
            'location_type' => $this->location_type,
            'description' => $this->description,
        ]);

        $this->closeModal();
        $this->emit('experienceAdded');
    }

    public function render()
    {
        return view('livewire.modals.add-experience');
    }
}