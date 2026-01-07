<?php

namespace App\Livewire\Settings;

use App\Forms\SystemSettingForm;
use App\Models\SystemSetting;
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

    public function mount(): void
    {
        $setting = SystemSetting::first();
        if ($setting) {
            $this->form->fill($setting->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(SystemSettingForm::schema())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = SystemSetting::first();
        if ($setting) {
            $setting->update($data);
        } else {
            SystemSetting::create($data);
        }

        // Clear cache explicitly (also cleared by model events)
        SystemSetting::clearCache();

        $this->dispatch('notify', text: 'System settings updated successfully.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.settings.system-setting-index');
    }
}
