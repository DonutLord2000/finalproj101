<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900">
        Add Experience
    </h2>

    <form wire:submit.prevent="save" class="mt-6 space-y-6">
        <div>
            <x-label for="title" value="{{ __('Title') }}" />
            <x-input id="title" type="text" class="mt-1 block w-full" wire:model.defer="title" />
            <x-input-error for="title" class="mt-2" />
        </div>

        <div>
            <x-label for="company" value="{{ __('Company') }}" />
            <x-input id="company" type="text" class="mt-1 block w-full" wire:model.defer="company" />
            <x-input-error for="company" class="mt-2" />
        </div>

        <div>
            <x-label for="employment_type" value="{{ __('Employment Type') }}" />
            <select id="employment_type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" wire:model.defer="employment_type">
                <option value="">Select Type</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Contract">Contract</option>
                <option value="Internship">Internship</option>
            </select>
            <x-input-error for="employment_type" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-label for="start_date" value="{{ __('Start Date') }}" />
                <x-input id="start_date" type="date" class="mt-1 block w-full" wire:model.defer="start_date" />
                <x-input-error for="start_date" class="mt-2" />
            </div>

            <div>
                <x-label for="end_date" value="{{ __('End Date') }}" />
                <x-input id="end_date" type="date" class="mt-1 block w-full" wire:model.defer="end_date" :disabled="current_role" />
                <x-input-error for="end_date" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center">
            <input id="current_role" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="current_role">
            <label for="current_role" class="ml-2 text-sm text-gray-600">I currently work here</label>
        </div>

        <div>
            <x-label for="location" value="{{ __('Location') }}" />
            <x-input id="location" type="text" class="mt-1 block w-full" wire:model.defer="location" />
            <x-input-error for="location" class="mt-2" />
        </div>

        <div>
            <x-label for="location_type" value="{{ __('Location Type') }}" />
            <select id="location_type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" wire:model.defer="location_type">
                <option value="">Select Type</option>
                <option value="On-site">On-site</option>
                <option value="Hybrid">Hybrid</option>
                <option value="Remote">Remote</option>
            </select>
            <x-input-error for="location_type" class="mt-2" />
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