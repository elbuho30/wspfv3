<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Ciudad;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ReplicateAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Support\Collection;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $modelLabel ='Cliente';
    protected static ?string $navigationGroup = 'Gestión Comercial';
    // protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    // protected static ?string $navigationIcon = 'feathericon-pie-chart';
    protected static ?string $navigationIcon = 'feathericon-star';
    // protected static ?string $navigationIcon = 'feathericon-shopping-bag';
    
    
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel ='Clientes';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('estado','=',1)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('estado','=','1')->count() < 1
        ? 'warning'
        : 'green';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos Personales')
                    ->description('Datos personales del cliente')
                    ->schema([
                        Forms\Components\TextInput::make('nro_documento')
                            ->label('Nro. Documento')
                            ->numeric()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('nombres')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('apellidos')
                            ->required()
                            ->maxLength(255),
            ])->columnSpan(2)
            ->columns([
                // 'default' =>3,
                'md' =>2,
                'lg' => 3,
                'xl' =>3,
            ])
            ->collapsible(),
            Forms\Components\Section::make('Datos Contacto')
            ->description('Datos de contacto')
            ->schema([
                Forms\Components\TextInput::make('celular')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(100),
            ])->columnSpan(1)->columns(2),
            Forms\Components\Section::make('Datos Ubicación')
                        ->description('Datos de ubicación')
                        ->schema([
                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\Select::make('departamento')
                    ->label('Departamento')
                    ->options(Departamento::query()->pluck('nombre', 'id'))
                    ->live(),
                Forms\Components\Select::make('ciudad_id')
                    ->label('Ciudad')
                    ->options(fn (Get $get): Collection => Ciudad::query()
                        ->where('departamento_id', $get('departamento'))
                        ->pluck('nombre', 'id')),
                        // Forms\Components\Select::make('ciudad_id')
                        //     ->relationship(name: 'ciudad', titleAttribute: 'nombre'),
             ])->columns(3),
                    Forms\Components\Toggle::make('estado')
                    ->required()
                    ->default(1), 
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth()->user()->id)
                    ->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nro_documento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellidos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad.departamento.nombre')
                    ->label('Departamento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad.nombre')
                    ->label('Ciudad')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\IconColumn::make('estado')
                //     ->boolean(), // estado como ícono
                Tables\Columns\ToggleColumn::make('estado'), // estado como interruptor
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable()
                    ->toggleable() //permite ocultar o mostrar
                    ->extraAttributes(['class' => 'bg-gray-200']), // adicionar color de fondo
            ])
            ->filters([
                SelectFilter::make('ciudad_id')
                    ->relationship('ciudad', 'nombre')
                    ->label('Ciudad'),
            
                // SelectFilter::make('departamento')
                //         ->relationship('ciudad.departamento', 'nombre')
                //         ->label('Departamento'),

                SelectFilter::make('estado')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ])
                    ->label('Estado'),
                    
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Usuario'),
            ] //, layout: FiltersLayout::AboveContent // filtro arriba del listado
             //, layout: FiltersLayout::AboveContentCollapsible // filtro arriba del listado pero contraido
             , layout: FiltersLayout::BelowContent // filtro debajo del listado
             )
            ->filtersFormColumns(3) // cantidad de filtros por linea
            ->filtersFormMaxHeight('400px') //alto maximo del filtro cuando es selector
            ->persistFiltersInSession() // mantener filtros en la sesión del usuario
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'view' => Pages\ViewCliente::route('/{record}'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }    
}
