<?php

namespace App\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;

class MenuForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label('Menu Item Name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Select::make('parent_id')
                ->relationship('parent', 'name')
                ->searchable()
                ->preload()
                ->label('Parent Menu'),

            TextInput::make('order')
                ->numeric()
                ->default(0)
                ->required()
                ->readOnly()
                ->helperText('Order is managed via drag and drop on the list page.'),

            ViewField::make('icon')
                ->label('Icon')
                ->view('forms.components.icon-picker')
                ->helperText('Klik kolom di atas untuk memilih icon melalui pop-up.'),

            TextInput::make('route')
                ->label('Route Name')
                ->placeholder('e.g. dashboard'),

            ToggleButtons::make('is_active')
                ->boolean() // keeps "off" as 0; without it false is read back as empty
                ->label('Visibility')
                ->options([
                    1 => 'Visible',
                    0 => 'Hidden',
                ])
                ->icons([
                    1 => 'heroicon-m-eye',
                    0 => 'heroicon-m-eye-slash',
                ])
                ->colors([
                    1 => 'success',
                    0 => 'danger',
                ])
                ->default(1)
                ->extraAttributes(['class' => 'premium-toggle-group'])
                ->inline(),
        ];
    }
}
