<?php

namespace App\Livewire\Settings;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('System Settings')]
class SystemSettingIndex extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected \App\Repositories\Contracts\SystemSettingRepositoryInterface $systemSettingRepository;

    public function boot(\App\Repositories\Contracts\SystemSettingRepositoryInterface $systemSettingRepository): void
    {
        $this->systemSettingRepository = $systemSettingRepository;
    }

    public function mount(): void
    {
        $setting = $this->systemSettingRepository->getSettings();
        if ($setting) {
            $this->form->fill($setting->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(\App\Forms\SystemSettingForm::schema())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->systemSettingRepository->updateSettings($data);

        $this->dispatch('notify', text: 'System settings updated successfully.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.settings.system-setting-index');
    }
}
