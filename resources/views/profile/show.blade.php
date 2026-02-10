@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Pages</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    Empty</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-info btn-icon ml-2"><i class="mdi mdi-filter-variant"></i></button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-danger btn-icon ml-2"><i class="mdi mdi-star"></i></button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-warning  btn-icon ml-2"><i class="mdi mdi-refresh"></i></button>
            </div>
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-primary">14 Aug 2019</button>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                        id="dropdownMenuDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuDate"
                        data-x-placement="bottom-end">
                        <a class="dropdown-item" href="#">2015</a>
                        <a class="dropdown-item" href="#">2016</a>
                        <a class="dropdown-item" href="#">2017</a>
                        <a class="dropdown-item" href="#">2018</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="max-w-xl mx-auto mt-6">
            @if(session('success'))
                <div class="bg-green-100 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <img src="{{ $user->profile_picture
        ? asset('storage/' . $user->profile_picture)
        : asset('images/default.png') }}" width="120" class="rounded-full mb-3">

            <h2 class="text-xl font-bold">{{ $user->name }}</h2>
            <p class="text-gray-600">{{ $user->bio }}</p>

            <div class="mt-4 flex gap-3">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    Edit Profile
                </a>
                {{-- <a href="{{ route('profile.friends', $user->id) }}" class="btn btn-secondary">Friends --}}
                    ({{ $user->friends_count ?? $user->friends->count() }})</a>
                {{-- <a href="{{ route('profile.posts', $user->id) }}" class="btn btn-secondary">Posts --}}
                    ({{ $user->posts_count ?? $user->posts->count() }})</a>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>

    </div>
    <!-- row closed -->
    <hr class="my-4">

        <h3 class="text-xl font-bold mb-2">Recent Posts</h3>
        @forelse($posts as $post)
            <div class="border p-3 rounded mb-3">
                <p>{{ $post->content ?? 'No content' }}</p>
                <div class="text-gray-500 text-sm">
                    Likes: {{ $post->likes_count ?? $post->likes->count() }},
                    Comments: {{ $post->comments_count ?? $post->comments->count() }}
                </div>
            </div>
        @empty
            <p>No posts yet.</p>
        @endforelse

    </div>
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection
