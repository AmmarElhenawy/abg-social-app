@extends('layouts')

@section('content')
<div class="container">
    <h2>Welcome {{ auth()->user()->name }} 👋</h2>
    <p>This is your feed.</p>
</div>
@endsection
