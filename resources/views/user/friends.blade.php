@extends('layouts.app')

@section('title', 'أصدقاء ' . $user->name)

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
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.profile', $user) }}">
                <i class="bi bi-grid"></i> المنشورات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('user.friends', $user) }}">
                <i class="bi bi-people"></i> الأصدقاء
            </a>
        </li>
    </ul>

    <!-- Pending Requests (Only visible to profile owner) -->
    @if($user->id === Auth::id() && $pendingRequests->count() > 0)
        <div class="alert alert-info">
            <h6><i class="bi bi-bell-fill"></i> لديك {{ $pendingRequests->count() }} طلب صداقة جديد</h6>
            <a href="{{ route('friends.requests') }}" class="btn btn-sm btn-primary">
                عرض الطلبات
            </a>
        </div>
    @endif

    <!-- Friends List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">الأصدقاء ({{ $friends->total() }})</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($friends as $friend)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <img src="{{ $friend->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($friend->name) }}" 
                                     class="rounded-circle mb-2" width="80" height="80" alt="{{ $friend->name }}">
                                <h6>
                                    <a href="{{ route('user.profile', $friend) }}" class="text-decoration-none">
                                        {{ $friend->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $friend->friends_count }} صديق</small>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('user.profile', $friend) }}" class="btn btn-sm btn-primary">
                                    عرض الملف
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">لا يوجد أصدقاء بعد</p>
                    </div>
                @endforelse
            </div>
        </div>
        @if($friends->hasPages())
            <div class="card-footer">
                {{ $friends->links() }}
            </div>
        @endif
    </div>
</div>
@endsection