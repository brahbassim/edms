@extends('layouts.app')

@section('content')
    <folder-edit :categories_data='{{ $categories }}' :members_data='{{ $members }}' :folder_data='{{json_encode($folder)}}'></folder-edit>
@stop
