@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="container">
    <!-- Profile Header -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="{{ $user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200' }}" 
                         class="rounded-circle img-thumbnail" width="150" height="150" alt="{{ $user->name }}">
                </div>
                <div class="col-md-9">
                    <h3>{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        <a href="{{ route('user.friends', $user) }}" class="text-decoration-none me-3">
                            <strong>{{ $user->friends_count }}</strong> صديق
                        </a>
                        <a href="{{ route('user.posts', $user) }}" class="text-decoration-none">
                            <strong>{{ $user->posts_count }}</strong> منشور
                        </a>
                    </div>

                    @if(Auth::id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> تعديل الملف الشخصي
                        </a>
                    @else
                        @php
                            $friendship = \App\Models\Friend::where(function($q) use ($user) {
                                $q->where('user_id', Auth::id())
                                  ->where('friend_id', $user->id);
                            })->orWhere(function($q) use ($user) {
                                $q->where('user_id', $user->id)
                                  ->where('friend_id', Auth::id());
                            })->first();
                        @endphp

                        @if(!$friendship)
                            <form action="{{ route('friends.send', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> إضافة صديق
                                </button>
                            </form>
                        @elseif($friendship->status === 'pending')
                            @if($friendship->user_id === Auth::id())
                                <form action="{{ route('friends.cancel', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> إلغاء الطلب
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('friends.accept', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success me-2">
                                        <i class="bi bi-check-circle"></i> قبول
                                    </button>
                                </form>
                                <form action="{{ route('friends.reject', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-circle"></i> رفض
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="badge bg-success me-2">
                                <i class="bi bi-check-circle"></i> أصدقاء
                            </span>
                            <form action="{{ route('friends.unfriend', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-person-x"></i> إلغاء الصداقة
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('user.profile', $user) }}">
                <i class="bi bi-grid"></i> المنشورات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.friends', $user) }}">
                <i class="bi bi-people"></i> الأصدقاء
            </a>
        </li>
    </ul>

    <!-- User Posts -->
    <div class="row">
        <div class="col-md-8">
            @forelse($user->posts()->with(['user', 'likes', 'comments'])->withCount(['likes', 'comments'])->latest()->paginate(10) as $post)
                @include('posts.partials.post-card', ['post' => $post])
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">لا توجد منشورات</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">معلومات</h6>
                </div>
                <div class="card-body">
                    <p><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                    <p><i class="bi bi-calendar"></i> انضم {{ $user->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection