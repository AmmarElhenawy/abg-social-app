@extends('layouts.app')

@section('title', 'نتائج البحث')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        نتائج البحث عن: <strong>"{{ $query }}"</strong>
                        <span class="badge bg-primary">{{ $users->total() }} نتيجة</span>
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($users as $user)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                     class="rounded-circle me-3" width="60" height="60" alt="{{ $user->name }}">
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('user.profile', $user) }}" class="text-decoration-none">
                                            {{ $user->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-people"></i> {{ $user->friends_count }} صديق
                                    </small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('user.profile', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> عرض الملف
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">لم يتم العثور على نتائج</p>
                            <p class="text-muted">حاول البحث بكلمات مختلفة</p>
                        </div>
                    @endforelse
                </div>
                @if($users->hasPages())
                    <div class="card-footer">
                        {{ $users->appends(['q' => $query])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection