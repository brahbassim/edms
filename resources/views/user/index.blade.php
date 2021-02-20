@extends('layouts.app')

@section('content')
    <user-manage :user_permissions='{{$user_permissions}}' :user_roles='{{$user_roles}}'></user-manage>
@stop
