<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Kullanıcı')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('agent_id')
                    ->relationship('agent', 'name')
                    ->label('Atanan Temsilci')
                    ->searchable(),
                Forms\Components\TextInput::make('subject')
                    ->label('Konu')
                    ->required(),
                Forms\Components\Textarea::make('message')
                    ->label('Mesaj')
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Durum')
                    ->options([
                        'open' => 'Açık',
                        'answered' => 'Cevaplandı',
                        'in_progress' => 'İşlemde',
                        'resolved' => 'Çözüldü',
                        'closed' => 'Kapalı',
                    ])
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->label('Öncelik')
                    ->options([
                        'low' => 'Düşük',
                        'normal' => 'Normal',
                        'high' => 'Yüksek',
                        'urgent' => 'Acil',
                    ])
                    ->required(),
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'technical' => 'Teknik Destek',
                        'billing' => 'Fatura & Ödeme',
                        'general' => 'Genel Bilgi',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Atanan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Konu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->colors([
                        'danger' => 'closed',
                        'warning' => 'in_progress',
                        'success' => 'resolved',
                        'primary' => 'open',
                    ]),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Öncelik')
                    ->badge()
                    ->colors([
                        'danger' => 'urgent',
                        'warning' => 'high',
                        'success' => 'normal',
                        'secondary' => 'low',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
