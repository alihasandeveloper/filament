<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Create a posts')
                        ->description('Create posts over here')
                        ->schema([
                            Group::make()
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        // ->rules(['min:5', 'max:50', 'in:it,hi,hello'])
                                        ->rules(['min:5', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/',])
                                        ->maxLength(255),
                                    TextInput::make('slug')
                                        ->unique()
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(2),
                            Group::make()->schema([
                                ColorPicker::make('color'),
                            ])->columns(2),
                            RichEditor::make('content')->columnSpanFull(),
                        ]),
                ])->columnSpan(2)->columns(2),
                Group::make()->schema([
                    Section::make('Image')
                        ->collapsible()
                        ->schema([
                            Group::make()->schema([
                                FileUpload::make('thumbnail'),
                            ]),
                        ]),
                    Section::make('Meta')
                        // ->collapsible()
                        ->schema([
                            TagsInput::make('tags'),
                            Checkbox::make('published'),
                        ]),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('tags')->label('Tags'),
                TextColumn::make('created_at')->dateTime()->label('Published At'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
