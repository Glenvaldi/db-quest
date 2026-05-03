<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('level_id')
                    ->required()
                    ->numeric()
                    ->label('Level Target (Contoh: 1)'),
                    
                TextInput::make('title')
                    ->required()
                    ->label('Judul Kuis (Contoh: Misi Modul 1)'),

                // 👇 INI DIA FITUR BARU UNTUK MEMILIH TIPE KUIS 👇
                Select::make('type')
                    ->label('Tipe Kuis')
                    ->options([
                        'pre_test' => 'Pre-Test (Diagnostic Awal)',
                        'post_test' => 'Quest Utama (Ujian Akhir)',
                    ])
                    ->required()
                    ->default('post_test'),

                TextInput::make('minimum_score_to_pass')
                    ->required()
                    ->numeric()
                    ->label('Nilai Minimum Lulus')
                    ->default(80),
            ]);
    }
}