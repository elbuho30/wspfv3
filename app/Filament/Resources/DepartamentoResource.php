<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartamentoResource\Pages;
use App\Filament\Resources\DepartamentoResource\RelationManagers;
use App\Models\Departamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DepartamentoResource extends Resource
{
    protected static ?string $model = Departamento::class;

    protected static ?string $modelLabel ='Departamento';
    protected static ?string $navigationGroup = 'Maestros';
    protected static ?string $navigationIcon = 'feathericon-map';
    protected static ?string $navigationLabel = 'Departamentos';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel ='Departamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pais_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('estado')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pais.nombre')
                    ->numeric()
                    ->sortable()->label('País'),                
                Tables\Columns\TextColumn::make('nombre')->label('Departamento')
                    ->sortable()    
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()->label('Usuario'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListDepartamentos::route('/'),
            'create' => Pages\CreateDepartamento::route('/create'),
            'view' => Pages\ViewDepartamento::route('/{record}'),
            'edit' => Pages\EditDepartamento::route('/{record}/edit'),
        ];
    }    
}
