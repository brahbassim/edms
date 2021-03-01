@extends('layouts.app')

@section('content')
    <document-manage :id='{{ json_encode($id) }}' :folder_data='{{ $folder }}'></document-manage>
@stop
