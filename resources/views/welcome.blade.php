@extends('layouts.frontend.app')

@section('title', 'Main')

@push('css')
    <link href="{{ asset('assets/frontend/css/home/styles.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/frontend/css/home/responsive.css') }}" rel="stylesheet">  
    <style>
        .favoritePost {
            color: red;
        }
    </style>  
@endpush

@section('content')
{{-- <h3>Categories</h3><br> --}}
<div class="main-slider">
    
    <div class="swiper-container position-static" data-slide-effect="slide" data-autoheight="false"
        data-swiper-speed="500" data-swiper-autoplay="10000" data-swiper-margin="0" data-swiper-slides-per-view="4"
        data-swiper-breakpoints="true" data-swiper-loop="true" >
        <div class="swiper-wrapper">

            @foreach ($categories as $category)
                <div class="swiper-slide">
                    <a class="slider-category" href="#">
                    <div class="blog-image"><img src="{{ Storage::disk('public')->url('category/slider/' . $category->image) }}" alt="Blog Image"></div>

                        <div class="category">
                            <div class="display-table center-text">
                                <div class="display-table-cell">
                                <h3><b>{{ $category->name }}</b></h3>
                                </div>
                            </div>
                        </div>

                    </a>
                </div><!-- swiper-slide -->  
            @endforeach


        </div><!-- swiper-wrapper -->

    </div><!-- swiper-container -->

</div><!-- slider -->

<section class="blog-area section">
    <div class="container">
        <h2>Last News</h2>
        <br>
        @if (session('successMsg'))
            <div class="alert alert-success m-t-15" role="alert">
            {{ session('successMsg') }}  
            </div> 
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger m-t-15" role="alert">
                    {{ $error }}  
                </div> 
            @endforeach
        @endif
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="single-post post-style-1">

                            <div class="blog-image"><img src="{{ Storage::disk('public')->url('post/' . $post->image) }}" alt="Blog Image"></div>

                            <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile/' . $post->user->image) }}" alt="Profile Image"></a>

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
                </div><!-- col-lg-4 col-md-6 -->    
            @endforeach

        </div><!-- row -->

        <a class="load-more-btn" href="#"><b>LOAD MORE</b></a>

    </div><!-- container -->
</section><!-- section -->
@endsection

@push('js')
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        function fav()
        {
            Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: 'Oops...',
                text: 'To liked this, you need login first!'
                })
        }
    </script>
@endpush