@extends('layouts.frontend.app')

@section('title', 'All Author Posts')


@push('css')
<link href="{{ asset('assets/frontend/css/categories/styles.css') }}" rel="stylesheet">

<link href="{{ asset('assets/frontend/css/categories/responsive.css') }}" rel="stylesheet">
<style>
    .autor-block {
        text-align: left;
        padding: 10px;
    }
    .autor-block img {

    }
    .favoritePost {
        color: red;
    }
</style>  
@endpush

@section('content')
<div class="slider display-table center-text">
    <h1 class="title display-table-cell"><b>All {{ $user->name }} Posts</b></h1>
    <p>
        
    </p>
    
</div><!-- slider -->




<section class="blog-area section">
    <div class="container">

        <div class="row">

            <div class="col-lg-8 col-md-12">
                <div class="row">
                    @foreach ($posts as $post)
                    <div class="col-md-6 col-sm-12">
                        <div class="card h-100">
                            <div class="single-post post-style-1">

                                <div class="blog-image"><img src="{{ Storage::disk('public')->url('post/' . $post->image) }}" alt="Blog Image"></div>
    
                                <a class="avatar" href="{{ route('author.profile', $post->user->username ) }}"><img src="{{ Storage::disk('public')->url('profile/' . $post->user->image) }}" alt="Profile Image"></a>
    
                                <div class="blog-info">
    
                                <h4 class="title"><a href="{{ route('post.details', $post->slug) }}"><b>{{ $post->title }}</b></a></h4>
    
                                    <ul class="post-footer">
                                        <li>
                                            {{-- если надо чтоб лайкал любой юзер, убираем условие гость и меняем в роутере post.favorite условие для авторизации, хотя так не сработает, наверно придется делать регистрацию--}}
                                            @guest
                                                <a href="{{ route('post.details', $post->slug) }}" {{-- onclick="fav()" --}}><i class="ion-heart"></i>
                                                {{ $post->favorite_to_users->count() }}
                                                </a>
                                            @else
                                            <a class="{{ !Auth::user()->favorite_posts->where('pivot.post_id', $post->id)->count() == 0 ? 'favoritePost' : 'no' }}" href="{{ route('post.details', $post->slug) }}{{-- javascript::void(0) --}}" {{-- onclick="document.getElementById('favorite-form-{{ $post->id }}').submit();" --}}><i class="ion-heart"></i>
                                                    {{ $post->favorite_to_users->count() }}
                                                </a>
                                            <form method="POST" action="{{ route('post.favorite', $post) }}" id="favorite-form-{{ $post->id }}" style="display: none;">
                                                @csrf
                                            </form>
                                            @endguest
                                        </li>
                                        <li><a href="{{ route('post.details', $post->slug) }}"><i class="ion-chatbubble"></i>{{ $post->comments->count() }}</a></li>
                                    <li><a href="{{ route('post.details', $post->slug) }}"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                                    </ul>
    
                                </div><!-- blog-info -->
                            </div><!-- single-post -->
                        </div><!-- card -->
                    </div><!-- col-md-6 col-sm-12 -->
                    @endforeach
                </div><!-- row -->

                {{ $posts->links() }}

            </div><!-- col-lg-8 col-md-12 -->

            <div class="col-lg-4 col-md-12 ">

                <div class="single-post info-area ">
                    <div class="card autor-block">
                        <img class="card-img-top" src="{{ Storage::disk('public')->url('profile/' . $user->image) }}" alt="Blog Image">
                        <div class="card-body">
                            <h4 class="title"><b>About author</b></h4>
                            <p class="card-text p-2">{{ $user->about }}</p>
                        </div>
                        <ul class="list-group mt-5">
                          <li class="mt-2">Author name: <strong>{{ $user->name }}</strong></li>
                          <li class="mt-2">Post count: <strong>{{ $user->posts->count() }}</strong></li>
                          <li class="mt-2">Author since: <strong>{{ $user->created_at->toFormattedDateString() }}</strong></li>
                        </ul>
                      </div>
                </div><!-- info-area -->

            </div><!-- col-lg-4 col-md-12 -->

        </div><!-- row -->

    </div><!-- container -->
</section><!-- section -->
@endsection

@push('js')
<script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
<script src="{{ asset('assets/frontend/js/scripts.js') }}"></script>
@endpush