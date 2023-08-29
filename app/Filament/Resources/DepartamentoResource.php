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
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Filters\SelectFilter;

class DepartamentoResource extends Resource
{
    protected static ?string $model = Departamento::class;

    protected static ?string $modelLabel ='Departamento';
    protected static ?string $navigationGroup = 'Maestros';
    protected static ?string $navigationIcon = 'feathericon-map';
    protected static ?string $navigationLabel = 'Departamentos';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel ='Departamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('pais_id')
                    ->relationship(name: 'pais', titleAttribute: 'nombre')
                    ->label('País'),
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth()->user()->id)
                    ->required(),
                Forms\Components\Toggle::make('estado')
                    ->required()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([              
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Departamento')
                    ->sortable()    
                    ->searchable(),
                Tables\Columns\TextColumn::make('pais.nombre')
                    ->sortable()
                    ->searchable()
                    ->label('País'),
                Tables\Columns\IconColumn::make('estado')
                    ->sortable()
                    ->searchable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Usuario'),
            ])
            ->filters([
                SelectFilter::make('pais_id')
                    ->relationship('pais', 'nombre')
                    ->label('País'),
                SelectFilter::make('estado')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ])
                    ->label('Estado'),
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Usuario')
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
            'index' => Pages\ListDepartamentos::route('/'),
            'create' => Pages\CreateDepartamento::route('/create'),
            'view' => Pages\ViewDepartamento::route('/{record}'),
            'edit' => Pages\EditDepartamento::route('/{record}/edit'),
        ];
    }    
}
