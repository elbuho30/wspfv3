<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TagsColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel ='Usuario';
    protected static ?string $navigationGroup = 'Filament Shield';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?int $navigationSort = 5;
    protected static ?string $pluralModelLabel ='Usuarios';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(100),
                // Forms\Components\DateTimePicker::make('email_verified_at')
                //     ->label('Fecha Verificación'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->minLength(8)
                ->same('passwordConfirmation')
                ->dehydrateStateUsing(fn($state) => bcrypt($state))
                ->hidden(function (?Model $record) {
                    return $record;
                }),
                TextInput::make('passwordConfirmation')
                ->label('Confirmar contraseña')
                ->password()
                ->required()
                ->minLength(8)
                ->dehydrateStateUsing(fn($state) => bcrypt($state))
                ->hidden(function (?Model $record) {
                    return $record;
                }),
                MultiSelect::make('roles')
                            ->required()
                            ->label('Roles')
                            ->relationship('roles', 'name')
                            ->preload(),
                MultiSelect::make('permissions')
                            ->label('Permisos')
                            ->relationship('permissions', 'name')
                            ->preload(),

                Select::make('oficina_id')
                ->required()
                ->label('Oficina')
                ->relationship('oficina', 'nombre')
                ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TagsColumn::make('roles.name')->label('Roles')
                    ->separator('-'),
    
                TextColumn::make('oficina.nombre')
                    ->label('Oficina'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
