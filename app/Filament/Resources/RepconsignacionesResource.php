<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepconsignacionesResource\Pages;
use App\Filament\Resources\RepconsignacionesResource\RelationManagers;
use App\Models\Repconsignaciones;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ReplicateAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class RepconsignacionesResource extends Resource
{
    protected static ?string $modelLabel ='Reporte Consignación';
    protected static ?string $navigationGroup = 'Automatización';
    protected static ?string $navigationIcon = 'feathericon-file-text';
    protected static ?string $navigationLabel = 'Reporte Consignaciones';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel ='Consignaciones';

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where('estado_banco','=','PENDIENTE')->count();
    // }

    // public static function getNavigationBadgeColor(): ?string
    // {
    //     return static::getModel()::where('estado_banco','=','PENDIENTE')->count() > 1
    //     ? 'green'
    //     : 'warning';
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cliente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nro_documento')
                    ->required()
                    ->maxLength(24),
                Forms\Components\TextInput::make('canal')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('producto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('valor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cuenta_origen')
                    ->maxLength(20),
                Forms\Components\TextInput::make('banco_consignacion')
                    ->required()
                    ->maxLength(200),
                Forms\Components\TextInput::make('estado_banco')
                    ->default('PENDIENTE')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('estado_erp')
                    ->default('PENDIENTE')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('comentario')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tipo_fuente')
                    ->required()
                    ->maxLength(4),
                Forms\Components\TextInput::make('consecutivo_fuente')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('radicado')
                    //->required()
                    ->maxLength(30),
                Forms\Components\DateTimePicker::make('fecha')
                    ->required(),
                Forms\Components\TextInput::make('adjunto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('comentario_adjunto')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adjunto2')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adjunto3')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adjunto4')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adjunto5')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adjunto6')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('adjunto7')
                    //->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente_id')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nro_documento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('canal')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('producto')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('valor')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('cuenta_origen')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('banco_consignacion')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_banco')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_erp')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('comentario')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tipo_fuente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('consecutivo_fuente')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('radicado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->dateTime()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('adjunto')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('comentario_adjunto')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('adjunto2')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('adjunto3')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('adjunto4')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('adjunto5')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('adjunto6')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('adjunto7')
                //     ->searchable(),
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
                Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                ReplicateAction::make()
                    ->label('Clonar')
                    ->successNotificationTitle('Registro clonado'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                ])->color('info')
                  ->icon('heroicon-s-cog-6-tooth')
                  ->tooltip('Acciones')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()->label('Exportar a Excel'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListRepconsignaciones::route('/'),
            'create' => Pages\CreateRepconsignaciones::route('/create'),
            'view' => Pages\ViewRepconsignaciones::route('/{record}'),
            'edit' => Pages\EditRepconsignaciones::route('/{record}/edit'),
        ];
    }    
}
