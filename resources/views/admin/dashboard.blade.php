@extends('layouts.app')

@section('content')
<div class="container">
    <admin-dashboard :users="{{ $users }}"/>
</div>
@endsection
