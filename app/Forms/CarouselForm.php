<?php

namespace App\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CarouselForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->rows(3),

            FileUpload::make('image')
                ->label('Image')
                ->image()
                ->directory('carousels')
                ->required()
                ->columnSpanFull(),

            TextInput::make('button_text')
                ->label('Button Text')
                ->maxLength(100),

            TextInput::make('button_link')
                ->label('Button Link')
                ->url()
                ->maxLength(255),

            TextInput::make('order')
                ->label('Order')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ];
    }
}
