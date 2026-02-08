@extends('layouts.app')

@section('title', 'أصدقائي')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">أصدقائي ({{ $friends->total() }})</h5>
                    <a href="{{ route('friends.requests') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-bell-fill"></i> طلبات الصداقة
                    </a>
                </div>
                <div class="card-body">
                    @forelse($friends as $friend)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="{{ $friend->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($friend->name) }}" 
                                     class="rounded-circle me-3" width="60" height="60" alt="{{ $friend->name }}">
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('user.profile', $friend) }}" class="text-decoration-none">
                                            {{ $friend->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-people"></i> {{ $friend->friends_count }} صديق
                                    </small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('user.profile', $friend) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="bi bi-person"></i> الملف الشخصي
                                </a>
                                <form action="{{ route('friends.unfriend', $friend) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('هل أنت متأكد من إلغاء الصداقة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-person-x"></i> إلغاء الصداقة
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">ليس لديك أصدقاء بعد</p>
                            <a href="{{ route('search.index') }}" class="btn btn-primary">
                                <i class="bi bi-search"></i> ابحث عن أصدقاء
                            </a>
                        </div>
                    @endforelse
                </div>
                @if($friends->hasPages())
                    <div class="card-footer">
                        {{ $friends->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection