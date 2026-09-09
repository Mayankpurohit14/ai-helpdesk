@extends('layouts.app')
@section('content')
<div x-data x-init="window.location.href = localStorage.getItem('token') ? '/dashboard' : '/login'"></div>
@endsection
