<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaisResource\Pages;
use App\Filament\Resources\PaisResource\RelationManagers;
use App\Models\Pais;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ReplicateAction;

class PaisResource extends Resource
{
    protected static ?string $model = Pais::class;

    protected static ?string $modelLabel ='País';
    protected static ?string $navigationGroup = 'Maestros';
    protected static ?string $navigationIcon = 'feathericon-flag';
    protected static ?string $navigationLabel = 'Países';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel ='Países';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('abreviatura')
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_int_call')
                    ->maxLength(255),
                Forms\Components\Toggle::make('estado')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->label('País')
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nombre copiado')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('abreviatura')
                    ->searchable()
                    ->sortable()
                    ->color('primary')
                    ->icon('heroicon-m-flag')
                    //->iconPosition(IconPosition::After)
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('id_int_call')->label('Código Llamadas')
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
                Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                /* These lines of code are defining the actions that can be performed on each record in
                the table. */
                ReplicateAction::make()->label('Clonar')->successNotificationTitle('Registro clonado'),
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
            'index' => Pages\ListPais::route('/'),
            'create' => Pages\CreatePais::route('/create'),
            'view' => Pages\ViewPais::route('/{record}'),
            'edit' => Pages\EditPais::route('/{record}/edit'),
        ];
    }    
}
