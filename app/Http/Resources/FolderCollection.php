<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FolderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($folder){
                return [
                    'id' => $folder->id,
                    'number' => $folder->number,
                    'description' => $folder->description,
                    'date_decret' => $folder->date_decret,
                    'date_decoration' => $folder->date_decoration,
                ];
            })
        ];
    }
}
