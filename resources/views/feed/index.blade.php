@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Pages</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    Empty</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-info btn-icon ml-2"><i class="mdi mdi-filter-variant"></i></button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-danger btn-icon ml-2"><i class="mdi mdi-star"></i></button>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <button type="button" class="btn btn-warning  btn-icon ml-2"><i class="mdi mdi-refresh"></i></button>
            </div>
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-primary">14 Aug 2019</button>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                        id="dropdownMenuDate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuDate"
                        data-x-placement="bottom-end">
                        <a class="dropdown-item" href="#">2015</a>
                        <a class="dropdown-item" href="#">2016</a>
                        <a class="dropdown-item" href="#">2017</a>
                        <a class="dropdown-item" href="#">2018</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="container" style="max-width: 850px;">
        <div class="row">
            @foreach($posts as $post)
                <div class="col-12 mb-4">
                    <div class="card">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="mt-2">
                        @endif

                        @if ($post->image === null)
                            <img class="card-img-top w-100" src="{{ asset('assets/img/photos/6.jpg') }}" alt="post image">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title mb-2">
                                <b>{{ $post->user->name }}</b>
                            </h5>

                            <p class="card-text">
                                {{ $post->content }}
                            </p>
                        </div>

                        <div class="mt-2 flex gap-3">
                            <form method="POST" action="{{ route('post.like', $post) }}">
                                @csrf
                                <button>❤️ {{ $post->likes_count }}</button>
                            </form>

                            <a href="{{ route('post.show', $post) }}" class="btn btn-sm btn-primary">
                                <button>
                                    💬 {{ $post->comments_count }}
                                </button>
                            </a>
                            <form method="POST" action="{{ route('friends.send', $post->user_id) }}">
                                @csrf
                                <button class="btn btn-primary">Add Friend</button>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection
@section('js')
@endsection
{{-- <div class="col-xl-4 col-lg-4 col-md-12">
    <div class="card">
        <img class="card-img-top w-100" src="{{URL::asset('assets/img/photos/6.jpg')}}" alt="">
        <div class="card-body">
            <h4 class="card-title mb-3">Card Title</h4>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget
                dolor. Aenean massa.</p>
            <a class="btn btn-primary" href="#">Read More</a>
        </div>
    </div>
</div> --}}
