<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" style="display: none;">
                Simpan
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
