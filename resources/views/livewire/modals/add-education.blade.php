<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900">
        Add Education
    </h2>

    <form wire:submit.prevent="save" class="mt-6 space-y-6">
        <div>
            <x-label for="school" value="{{ __('School') }}" />
            <x-input id="school" type="text" class="mt-1 block w-full" wire:model.defer="school" />
            <x-input-error for="school" class="mt-2" />
        </div>

        <div>
            <x-label for="degree" value="{{ __('Degree') }}" />
            <x-input id="degree" type="text" class="mt-1 block w-full" wire:model.defer="degree" />
            <x-input-error for="degree" class="mt-2" />
        </div>

        <div>
            <x-label for="field_of_study" value="{{ __('Field of Study') }}" />
            <x-input id="field_of_study" type="text" class="mt-1 block w-full" wire:model.defer="field_of_study" />
            <x-input-error for="field_of_study" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-label for="start_date" value="{{ __('Start Date') }}" />
                <x-input id="start_date" type="date" class="mt-1 block w-full" wire:model.defer="start_date" />
                <x-input-error for="start_date" class="mt-2" />
            </div>

            <div>
                <x-label for="end_date" value="{{ __('End Date') }}" />
                <x-input id="end_date" type="date" class="mt-1 block w-full" wire:model.defer="end_date" />
                <x-input-error for="end_date" class="mt-2" />
            </div>
        </div>

        <div>
            <x-label for="grade" value="{{ __('Grade') }}" />
            <x-input id="grade" type="text" class="mt-1 block w-full" wire:model.defer="grade" />
            <x-input-error for="grade" class="mt-2" />
        </div>

        <div>
            <x-label for="activities" value="{{ __('Activities and Societies') }}" />
            <textarea id="activities" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" rows="3" wire:model.defer="activities"></textarea>
            <x-input-error for="activities" class="mt-2" />
        </div>

        <div>
            <x-label for="description" value="{{ __('Description') }}" />
            <textarea id="description" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" rows="3" wire:model.defer="description"></textarea>
            <x-input-error for="description" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6 space-x-2">
            <x-button type="button" wire:click="$emit('closeModal')" class="bg-gray-50">
                Cancel
            </x-button>
            <x-button type="submit">
                Save
            </x-button>
        </div>
    </form>
</div>