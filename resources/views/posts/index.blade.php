<x-app-layout>
<div class="max-w-2xl mx-auto mt-6">

    <a href="{{ route('post.create') }}" class="btn btn-primary mb-4">
        Create Post
    </a>

    @foreach($posts as $post)
        <div class="border p-4 mb-4 rounded">
            <h4 class="font-bold">{{ $post->user->name }}</h4>
            <p>{{ $post->content }}</p>

            @if($post->image)
                <img src="{{ asset('storage/'.$post->image) }}" class="mt-2">
            @endif

            <div class="mt-2 flex gap-3">
                <form method="POST" action="{{ route('post.like', $post) }}">
                    @csrf
                    <button>❤️ {{ $post->likes_count }}</button>
                </form>

                <a href="{{ route('posts.show ', $post) }}">
                    Comments ({{ $post->comments_count }})
                </a>
            </div>
        </div>
    @endforeach

    {{ $posts->links() }}
</div>
</x-app-layout>
