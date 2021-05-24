@extends('layouts.app')

@section('content')
    <grade-manage :categories_data='{{ $categories }}'></grade-manage>
@stop
