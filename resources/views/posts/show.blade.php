@extends('layouts.app')

@section('title', 'منشور')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Post -->
            @include('posts.partials.post-card', ['post' => $post])

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">التعليقات ({{ $post->comments_count }})</h5>
                </div>
                
                <!-- Add Comment Form -->
                <div class="card-body border-bottom">
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="d-flex">
                            <img src="{{ Auth::user()->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                                 class="rounded-circle me-2" width="40" height="40" alt="You">
                            <div class="flex-grow-1">
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" 
                                          rows="2" placeholder="اكتب تعليقاً..." required></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-primary btn-sm">نشر التعليق</button>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div class="card-body">
                    @forelse($comments as $comment)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex">
                                <img src="{{ $comment->user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                     class="rounded-circle me-2" width="40" height="40" alt="{{ $comment->user->name }}">
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-2">
                                        <a href="{{ route('user.profile', $comment->user) }}" class="fw-bold text-decoration-none">
                                            {{ $comment->user->name }}
                                        </a>
                                        <p class="mb-0 mt-1">{{ $comment->content }}</p>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mt-1 small text-muted">
                                        <form action="{{ route('likes.comment.toggle', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0 me-2">
                                                <i class="bi bi-heart{{ $comment->likes->where('user_id', Auth::id())->count() ? '-fill text-danger' : '' }}"></i>
                                                إعجاب ({{ $comment->likes_count }})
                                            </button>
                                        </form>
                                        <span class="me-2">{{ $comment->created_at->diffForHumans() }}</span>
                                        
                                        @if($comment->user_id === Auth::id())
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا التعليق؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0">
                                                    حذف
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">لا توجد تعليقات بعد. كن أول من يعلق!</p>
                    @endforelse

                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection