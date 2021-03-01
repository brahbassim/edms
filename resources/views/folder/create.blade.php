@extends('layouts.app')

@section('content')
    <folder-create :categories_data='{{ $categories }}'></folder-create>
@stop
