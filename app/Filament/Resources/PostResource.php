<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
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
                                Select::make('category_id')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->required()
                                    ->label('Category')
                                    ->searchable(),
                                ColorPicker::make('color'),
                            ])->columns(2),
                            MarkdownEditor::make('content')->columnSpanFull(),
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
        // ->columns([
        //     'sm' => 1,
        //     'lg' => 2,
        //     'xl' => 3,
        // ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->toggleable($isToggledHiddenByDefault = true),
                ImageColumn::make('thumbnail')->toggleable(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('category.name')->searchable()->toggleable(),
                ColorColumn::make('color')->toggleable(),
                TextColumn::make('tags')->searchable()->toggleable(),
                CheckboxColumn::make('published')->toggleable(),
                TextColumn::make('created_at')->date()->label('Published At')->sortable()->toggleable(),
                TextColumn::make('updated_at')->date()->label('Updated At')->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
