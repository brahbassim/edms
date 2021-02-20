@extends('layouts.app')

@section('content')
    <user-role :user_permissions='{{$user_permissions}}'></user-role>
@stop
