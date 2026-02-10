@extends('layout')

@section('content')
<form method="POST">
    @csrf
    <input name="q" placeholder="Search users">
    <button>Search</button>
</form>

<hr>

@if(isset($users))
@foreach($users as $user)
    <p>
        {{ $user->name }}
        <a href="/profile/{{ $user->id }}">View</a>
    </p>
@endforeach
@endif
@endsection
