<?php

namespace App\Forms;

use App\Models\NewsCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class NewsForm
{
    public static function schema(): array
    {
        return [
            Select::make('news_category_id')
                ->label('Category')
                ->options(NewsCategory::active()->pluck('name', 'id'))
                ->required()
                ->searchable(),

            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->label('Slug')
                ->helperText('Leave empty to auto-generate from title')
                ->maxLength(255),

            Textarea::make('excerpt')
                ->label('Excerpt')
                ->helperText('Short summary, leave empty to auto-generate')
                ->rows(2),

            RichEditor::make('content')
                ->label('Content')
                ->required()
                ->columnSpanFull(),

            FileUpload::make('image')
                ->label('Featured Image')
                ->image()
                ->directory('news')
                ->columnSpanFull(),

            DateTimePicker::make('published_at')
                ->label('Publish Date')
                ->default(now()),

            Toggle::make('is_featured')
                ->label('Featured')
                ->default(false),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ];
    }
}
