@extends('layouts.app')

@section('title', 'منشورات ' . $user->name)

@section('content')
<div class="container">
    <!-- Profile Header -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="{{ $user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                     class="rounded-circle me-3" width="80" height="80" alt="{{ $user->name }}">
                <div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted mb-0">{{ $posts->total() }} منشور</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            @forelse($posts as $post)
                @include('posts.partials.post-card', ['post' => $post])
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">لا توجد منشورات بعد</p>
                    </div>
                </div>
            @endforelse

            @if($posts->hasPages())
                <div class="mt-3">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection