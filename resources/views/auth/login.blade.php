@extends('layouts.auth')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-8 col-md-8 text-center">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0 ">
                    <img class="text-center" src="{{asset('/img/logo.jpeg')}}" width="250" alt="">
                    <div class="row">
                        <div class="col-lg-8 offset-2 text-center">
                            <div class="p-5">
                                <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Gestion Electronique De Documents</h1>
                                    <h2 class="h4 text-gray-900 mb-4"><span id="date_time"></span></h2>
                                </div>
                                <auth-login :auth="'{{ route('store-login') }}'" :redirect="'{{ route('index-dashboard') }}'"></auth-login>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
