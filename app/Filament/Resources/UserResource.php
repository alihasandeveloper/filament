<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Support\Colors\Color;


use PHPUnit\Util\Filter;
use function Laravel\Prompts\select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                Select::make('role')->options([
                    'Admin' => 'Admin',
                    'Super Admin' => 'Super Admin',
                    'Editor' => 'Editor',
                    'Viewer' => 'Viewer',
                ])->required(),
                TextInput::make('email')->maxLength(255),
                TextInput::make('password')->maxLength(255)->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->searchable()
                    ->badge()
//                    ->color(fn($state) => $state === 'Super Admin' ? 'danger' : ($state === 'Admin' ? 'success' : ($state === 'Editor' ? 'info' : 'primary'))
                    ->color(fn($state) => match ($state) {
                        'Admin' => 'success',
                        'Super Admin' => 'success',
                        'Editor' => 'info',
                        'Viewer' => Color::Zinc,
                    }),

                TextColumn::make('created_at')->dateTime()->label('Created At'),
                TextColumn::make('updated_at')->dateTime()->label('Updated At'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'Super Admin' => 'Super Admin',
                        'Admin' => 'Admin',
                        'Editor' => 'Editor',
                        'Viewer' => 'Viewer',
                    ])
                    ->searchable()
                    ->label('Role')
                    ->multiple(),
                Tables\Filters\Filter::make('email_verified_at')
                    ->query(fn(Builder $query): Builder => $query->where('email_verified_at', true))
                    ->toggle()
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
            PostsRelationManager::class
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
