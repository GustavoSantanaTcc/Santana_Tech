<x-filament-panels::page>
    @if ($show_form == 'list')
        <div class="mt-4">
            <div class="flex  justify-end">
                <x-filament::button type="button" size="sm" wire:click="showForm('create')">
                    Novo Atendimento
                </x-filament::button>
            </div>
        </div>
        {{ $this->table }}
    @endif
    @if ($show_form == 'create')
        <div class="flex justify-end mb-4">
            <x-filament::button type="button" size="sm" wire:click="back">
                Voltar
            </x-filament::button>
        </div>
        <x-filament::section>
            <form wire:submit="submit">
                {{ $this->form }}
        </x-filament::section>
        <div class="mt-4 justify-start">
            <x-filament::button type="button" size="sm" wire:click="submit">
                Salvar
            </x-filament::button>
        </div>
        </form>
    @endif
    @if ($show_form == 'edit')
        <div class="flex justify-end mb-4">
            <x-filament::button type="button" size="sm" wire:click="back">
                Voltar
            </x-filament::button>
        </div>
        <x-filament::section>
            <form wire:submit="submit">
                {{ $this->form }}
        </x-filament::section>
        <div class="mt-4 justify-start">
            <x-filament::button type="button" size="sm" wire:click="submit">
                Salvar
            </x-filament::button>
        </div>
        </form>
    @endif
    @if ($show_form == 'view' && $id != null)
        {{ $this->form }}
    @endif
</x-filament-panels::page>
