<?php

namespace App\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use App\Models\Education;


class AddEducationModal extends ModalComponent
{
    public $school;
    public $degree;
    public $field_of_study;
    public $start_date;
    public $end_date;
    public $grade;
    public $activities;
    public $description;

    protected $rules = [
        'school' => 'required|string|max:255',
        'degree' => 'required|string|max:255',
        'field_of_study' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'grade' => 'nullable|string|max:255',
        'activities' => 'nullable|string',
        'description' => 'nullable|string',
    ];

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function save()
    {
        $this->validate();

        auth()->user()->education()->create([
            'school' => $this->school,
            'degree' => $this->degree,
            'field_of_study' => $this->field_of_study,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'grade' => $this->grade,
            'activities' => $this->activities,
            'description' => $this->description,
        ]);

        $this->closeModal();
        $this->emit('educationAdded');
    }

    public function render()
    {
        return view('livewire.modals.add-education');
    }
}