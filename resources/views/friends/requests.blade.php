@extends('layouts.app')

@section('title', 'طلبات الصداقة')

@section('content')
<div class="container">
    <div class="row">
        <!-- Received Requests -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-inbox-fill"></i> الطلبات الواردة ({{ $receivedRequests->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($receivedRequests as $request)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="{{ $request->sender->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->sender->name) }}" 
                                     class="rounded-circle me-3" width="50" height="50" alt="{{ $request->sender->name }}">
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('user.profile', $request->sender) }}" class="text-decoration-none">
                                            {{ $request->sender->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('friends.accept', $request->sender) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> قبول
                                    </button>
                                </form>
                                <form action="{{ route('friends.reject', $request->sender) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i> رفض
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">لا توجد طلبات واردة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sent Requests -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-send-fill"></i> الطلبات المرسلة ({{ $sentRequests->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($sentRequests as $request)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="{{ $request->receiver->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->receiver->name) }}" 
                                     class="rounded-circle me-3" width="50" height="50" alt="{{ $request->receiver->name }}">
                                <div>
                                    <h6 class="mb-0">
                                        <a href="{{ route('user.profile', $request->receiver) }}" class="text-decoration-none">
                                            {{ $request->receiver->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('friends.cancel', $request->receiver) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-lg"></i> إلغاء الطلب
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-send" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">لا توجد طلبات مرسلة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection