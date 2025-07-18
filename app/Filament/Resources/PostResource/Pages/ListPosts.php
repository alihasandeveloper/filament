<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;



class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

//    public function getTabs(): array
//    {
//        return [
//            'All' =>Tab::make(),
//            'Published' =>Tab::make()->modifyQuery(fn (Builder $query): Builder => $query->whereDate('created_at', '>=', $date)),
//        ];
//    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
