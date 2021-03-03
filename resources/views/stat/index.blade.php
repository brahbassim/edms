@extends('layouts.app')

@section('content')
    <stat :categories_data='{{ $categories }}'></stat>
@stop
