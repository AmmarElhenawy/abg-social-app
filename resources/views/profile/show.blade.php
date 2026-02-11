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
    <!-- row -->
    <div class="row row-sm">
        <div class="col-lg-4">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="pl-0">
                        <div class="main-profile-overview">
                            @if(session('success'))
                                <div class="bg-green-100 p-3 rounded mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="main-img-user profile-user">
                                {{-- @if($user->profile_picture)
                                <img src="{{ asset('storage/profiles/' . $user->profile_picture) }}" class="mt-2">
                                @endif

                                @if ($user->profile_picture === null)
                                <img class="card-img-top w-100" src="{{ asset('assets/img/photos/6.jpg') }}"
                                    alt="post image">
                                @endif
                            </div> --}}
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="mb-4">
                            @endif


                            <div class="d-flex justify-content-between mg-b-20">
                                <div>
                                    <h2 class="text-xl font-bold">{{ $user->name }}</h2>

                                </div>
                            </div>
                            <h6>Bio</h6>
                            <div class="main-profile-bio">
                                <p class="text-gray-600">{{ $user->bio }}</p>
                            </div><!-- main-profile-bio -->

                            {{-- <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                Edit Profile
                            </a> --}}
                            <div class="row">
                                <div class="col-md-4 col mb20">
                                    <h5><a>({{ $user->friends_count ?? $user->friends->count() }})</a></h5>


                                    <h6 class="text-small text-muted mb-0">Friends</h6>
                                </div>
                                <div class="col-md-4 col mb20">
                                    <h5>({{ $user->posts_count ?? $user->posts->count() }})</a></h5>
                                    <h6 class="text-small text-muted mb-0">Posts</h6>
                                </div>
                            </div>
                            <hr class="mg-y-30">


                            <!--skill bar-->
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                Edit Profile
                            </a>
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">
                                    Delete Account
                                </button>
                            </form>
                            <!--skill bar-->
                        </div><!-- main-profile-overview -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-xl font-bold mb-2">Recent Posts</h3>
                    @forelse($posts as $post)
                        <div class="border p-3 rounded mb-3">
                            <p>{{ $post->content ?? 'No content' }}</p>
                            <div class="text-gray-500 text-sm">
                                Likes: {{ $post->likes_count ?? $post->likes->count() }},
                                Comments: {{ $post->comments_count ?? $post->comments->count() }}
                            </div>
                        </div>
                    @empty
                        <p>No posts yet.</p>
                    @endforelse
                    {{-- <div class="tab-content border-left border-bottom border-right border-top-0 p-4">
                        <div class="tab-pane active" id="home">
                            <h4 class="tx-15 text-uppercase mb-3">BIOdata</h4>
                            <p class="m-b-5">Hi I'm Petey Cruiser,has been the industry's standard dummy text ever since the
                                1500s, when an unknown printer took a galley of type. Donec pede justo, fringilla vel,
                                aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae,
                                justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus
                                elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu,
                                consequat vitae, eleifend ac, enim.</p>
                            <div class="m-t-30">
                                <h4 class="tx-15 text-uppercase mt-3">Experience</h4>
                                <div class=" p-t-10">
                                    <h5 class="text-primary m-b-5 tx-14">Lead designer / Developer</h5>
                                    <p class="">websitename.com</p>
                                    <p><b>2010-2015</b></p>
                                    <p class="text-muted tx-13 m-b-0">Lorem Ipsum is simply dummy text of the printing and
                                        typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                                        since the 1500s, when an unknown printer took a galley of type and scrambled it to
                                        make a type specimen book.</p>
                                </div>
                                <hr>
                                <div class="">
                                    <h5 class="text-primary m-b-5 tx-14">Senior Graphic Designer</h5>
                                    <p class="">coderthemes.com</p>
                                    <p><b>2007-2009</b></p>
                                    <p class="text-muted tx-13 mb-0">Lorem Ipsum is simply dummy text of the printing and
                                        typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                                        since the 1500s, when an unknown printer took a galley of type and scrambled it to
                                        make a type specimen book.</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="border p-1 card thumb">
                                        <a href="#" class="image-popup" title="Screenshot-2"> <img
                                                src="{{URL::asset('assets/img/photos/7.jpg')}}" class="thumb-img"
                                                alt="work-thumbnail"> </a>
                                        <h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Photography</small></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class=" border p-1 card thumb">
                                        <a href="#" class="image-popup" title="Screenshot-2"> <img
                                                src="{{URL::asset('assets/img/photos/8.jpg')}}" class="thumb-img"
                                                alt="work-thumbnail"> </a>
                                        <h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Photography</small></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class=" border p-1 card thumb">
                                        <a href="#" class="image-popup" title="Screenshot-2"> <img
                                                src="{{URL::asset('assets/img/photos/9.jpg')}}" class="thumb-img"
                                                alt="work-thumbnail"> </a>
                                        <h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Photography</small></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class=" border p-1 card thumb  mb-xl-0">
                                        <a href="#" class="image-popup" title="Screenshot-2"> <img
                                                src="{{URL::asset('assets/img/photos/10.jpg')}}" class="thumb-img"
                                                alt="work-thumbnail"> </a>
                                        <h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Photography</small></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class=" border p-1 card thumb  mb-xl-0">
                                        <a href="#" class="image-popup" title="Screenshot-2"> <img
                                                src="{{URL::asset('assets/img/photos/6.jpg')}}" class="thumb-img"
                                                alt="work-thumbnail"> </a>
                                        <h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Photography</small></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class=" border p-1 card thumb  mb-xl-0">
                                        <a href="#" class="image-popup" title="Screenshot-2"> <img
                                                src="{{URL::asset('assets/img/photos/5.jpg')}}" class="thumb-img"
                                                alt="work-thumbnail"> </a>
                                        <h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Photography</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings">
                            <form role="form">
                                <div class="form-group">
                                    <label for="FullName">Full Name</label>
                                    <input type="text" value="John Doe" id="FullName" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="Email">Email</label>
                                    <input type="email" value="first.last@example.com" id="Email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="Username">Username</label>
                                    <input type="text" value="john" id="Username" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="Password">Password</label>
                                    <input type="password" placeholder="6 - 15 Characters" id="Password"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="RePassword">Re-Password</label>
                                    <input type="password" placeholder="6 - 15 Characters" id="RePassword"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="AboutMe">About Me</label>
                                    <textarea id="AboutMe"
                                        class="form-control">Loren gypsum dolor sit mate, consecrate disciplining lit, tied diam nonunion nib modernism tincidunt it Loretta dolor manga Amalia erst volute. Ur wise denim ad minim venial, quid nostrum exercise ration perambulator suspicious cortisol nil it applique ex ea commodore consequent.</textarea>
                                </div>
                                <button class="btn btn-primary waves-effect waves-light w-md" type="submit">Save</button>
                            </form>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection

@section('js')
@endsection