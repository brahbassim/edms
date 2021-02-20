@extends('layouts.app')

@section('content')
    <document-manage :id='{{ json_encode($id) }}'></document-manage>
@stop
