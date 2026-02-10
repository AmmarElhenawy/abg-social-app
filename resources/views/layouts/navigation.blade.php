<nav class="bg-gray-800 p-4 text-white flex gap-4">
    <a href="{{ url('/') }}">Home</a>
    <a href="{{ route('post.index') }}">Posts</a>
    {{-- <a href="{{ route('profile.show') }}">Profile</a> --}}

    {{-- <form method="POST" action="{{ route('logout') }}" class="ml-auto">
        @csrf
        <button>Logout</button>
    </form> --}}
</nav>
