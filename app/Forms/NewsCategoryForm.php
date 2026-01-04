<?php

namespace App\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class NewsCategoryForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')
                ->label('Category Name')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->label('Slug')
                ->helperText('Leave empty to auto-generate from name')
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->rows(3),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ];
    }
}
