<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload; // 👈 Pastikan ini ditambahkan
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('level_id')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('content_html')
                    ->required()
                    ->columnSpanFull(),
                
                // 👇 DUA BARIS INI YANG DITAMBAHKAN 👇
                TextInput::make('youtube_url')
                    ->label('Link Video YouTube (Opsional)')
                    ->url()
                    ->placeholder('Contoh: https://www.youtube.com/watch?v=...'),
                    
                FileUpload::make('pdf_file')
                    ->label('Upload File PDF Modul (Opsional)')
                    ->multiple() // 👈 Ini bikin bisa upload banyak
                    ->disk('public') // 👈 Ini mencegah error 403
                    ->directory('materials-pdf')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(5120) // Maksimal 5MB
                    ->columnSpanFull(), 
            ]);
    }
}