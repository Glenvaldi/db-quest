<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 👇 FITUR BARU: Dropdown Pilih Kuis Berdasarkan Judul 👇
                Select::make('quiz_id')
                    ->relationship('quiz', 'title') 
                    ->required()
                    ->label('Pilih Target Kuis (Pemasangan Soal)')
                    ->searchable() // Biar bisa diketik nyari judulnya
                    ->preload(),

                Select::make('type')
                    ->label('Tipe Soal')
                    ->options([
                        'multiple_choice' => 'Multiple Choice',
                        'short_answer' => 'Short Answer',
                    ])
                    ->required()
                    ->default('multiple_choice'),

                // 👇 Form JSON yang sudah kita pecah biar gampang ngisinya 👇
                Textarea::make('content.question')
                    ->label('Teks Pertanyaan')
                    ->required()
                    ->columnSpanFull(),

                TagsInput::make('content.options')
                    ->label('Pilihan Jawaban (Khusus Pilihan Ganda)')
                    ->helperText('Ketik satu pilihan, lalu tekan tombol ENTER. Ulangi sampai semua pilihan masuk.')
                    ->placeholder('Contoh: Basis Data [Enter]')
                    ->columnSpanFull(),

                TextInput::make('correct_answer')
                    ->required()
                    ->label('Kunci Jawaban (Correct Answer)')
                    ->helperText('Harus diketik SAMA PERSIS dengan salah satu pilihan jawaban di atas.')
                    ->columnSpanFull(),

                TextInput::make('points')
                    ->required()
                    ->numeric()
                    ->label('Poin Soal')
                    ->default(20),
            ]);
    }
}