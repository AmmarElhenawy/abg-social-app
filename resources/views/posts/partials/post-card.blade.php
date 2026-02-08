<div class="card mb-3">
    <!-- Post Header -->
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ $post->user->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                     class="rounded-circle me-2" width="40" height="40" alt="{{ $post->user->name }}">
                <div>
                    <a href="{{ route('user.profile', $post->user) }}" class="text-decoration-none fw-bold">
                        {{ $post->user->name }}
                    </a>
                    <br>
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </div>
            
            @if($post->user_id === Auth::id())
                <div class="dropdown">
                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                <i class="bi bi-pencil"></i> تعديل
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنشور؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-trash"></i> حذف
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Post Content -->
    <div class="card-body">
        <p class="card-text">{{ $post->content }}</p>
        
        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded" alt="Post Image">
        @endif
    </div>

    <!-- Post Stats -->
    <div class="card-footer bg-white border-top-0 border-bottom">
        <div class="d-flex justify-content-between text-muted small">
            <span>
                <i class="bi bi-heart-fill text-danger"></i> {{ $post->likes_count }} إعجاب
            </span>
            <span>{{ $post->comments_count }} تعليق</span>
        </div>
    </div>

    <!-- Post Actions -->
    <div class="card-footer bg-white">
        <div class="row text-center">
            <div class="col">
                <form action="{{ route('likes.post.toggle', $post) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 {{ $post->likes->where('user_id', Auth::id())->count() ? 'btn-danger' : 'btn-outline-secondary' }}">
                        <i class="bi bi-heart-fill"></i> إعجاب
                    </button>
                </form>
            </div>
            <div class="col">
                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-chat-fill"></i> تعليق
                </a>
            </div>
        </div>
    </div>
</div>