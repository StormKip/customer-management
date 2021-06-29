@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Customer Dashboard') }}</div>

                <div class="card-body">
                    <p>Welcome {{ $name }} {{ $surname }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
