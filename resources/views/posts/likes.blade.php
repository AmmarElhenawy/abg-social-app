@extends('layouts.app')

@section('title', 'الإعجابات')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الأشخاص الذين أعجبهم هذا المنشور</h5>
                </div>
                <div class="card-body">
                    @forelse($likes as $like)
                        <div class="d-flex align-items-center p-2 border-bottom">
                            <img src="{{ $like->user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($like->user->name) }}" 
                                 class="rounded-circle me-3" width="50" height="50" alt="{{ $like->user->name }}">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">
                                    <a href="{{ route('user.profile', $like->user) }}" class="text-decoration-none">
                                        {{ $like->user->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $like->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">لا توجد إعجابات بعد</p>
                    @endforelse
                </div>
                <div class="card-footer">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-right"></i> العودة للمنشور
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection