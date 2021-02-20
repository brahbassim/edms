@extends('layouts.app')

@section('content')
   <dashboard :documents_nbr='{{ $documents_nbr }}' :folders_nbr='{{ $folders_nbr }}' :users_nbr='{{ $users_nbr }}'></dashboard>
@stop
