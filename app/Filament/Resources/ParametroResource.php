<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParametroResource\Pages;
use App\Filament\Resources\ParametroResource\RelationManagers;
use App\Models\Parametro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ParametroResource extends Resource
{
    protected static ?string $model = Parametro::class;

    protected static ?string $modelLabel ='Parámetro';
    protected static ?string $navigationGroup = 'Entidad';
    protected static ?string $navigationIcon = 'feathericon-tool';
    protected static ?string $navigationLabel = 'Parámetros';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel ='Parámetros';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nit')
                    ->numeric()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('razon_social')
                    ->label('Razón Social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sigla')
                    ->maxLength(100),
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción')
                    ->maxLength(255),
                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255),
                Forms\Components\Select::make('ciudad_id')
                    ->relationship(name: 'ciudad', titleAttribute: 'nombre'),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('celular')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(100),
                Forms\Components\TextInput::make('url_api_meta')
                    ->maxLength(255),
                Forms\Components\TextInput::make('auth_api_key_meta')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url_webhook')
                    ->maxLength(255),
                Forms\Components\TextInput::make('token_webhook')
                    ->maxLength(255),
                Forms\Components\TextInput::make('puerto')
                    ->label('Puerto servicio')
                    ->required(),
                Forms\Components\Toggle::make('estado')
                    ->required()
                    ->default(1),                   
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth()->user()->id)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->label('Razón Social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sigla')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad.nombre')
                    ->label('Ciudad')
                    ->sortable()
                    ->searchable(), 
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url_api_meta')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('auth_api_key_meta')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('url_webhook')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('token_webhook')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('puerto')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListParametros::route('/'),
            'create' => Pages\CreateParametro::route('/create'),
            'view' => Pages\ViewParametro::route('/{record}'),
            'edit' => Pages\EditParametro::route('/{record}/edit'),
        ];
    }    
}
