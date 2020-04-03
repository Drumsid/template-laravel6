@extends('layouts.frontend.app')

@section('title')
{{$post->title}}
@endsection

@push('css')
<link href="{{ asset('assets/frontend/css/single-post/styles.css') }}" rel="stylesheet">

<link href="{{ asset('assets/frontend/css/single-post/responsive.css') }}" rel="stylesheet">
<style>
    .header-post-image {
        height: 60%;
        width: 100%;
        background-image: url({{ Storage::disk('public')->url('post/' . $post->image) }});
        background-size: cover;
        background-position: center 35%;
    }
    .favoritePost {
        color: red;
    }
</style>  
@endpush

@section('content')
<div class="header-post-image">

</div>

<section class="post-area section">
    <div class="container">
        @if (session('successMsg'))
            <div class="alert alert-success m-t-15" role="alert">
            {{ session('successMsg') }}  
            </div> 
        @endif
        <div class="row">

            <div class="col-lg-8 col-md-12 no-right-padding">

                <div class="main-post">

                    <div class="blog-post-inner">

                        <div class="post-info">

                            <div class="left-area">
                                <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile/' . $post->user->image) }}" alt="Profile Image"></a>
                            </div>

                            <div class="middle-area">
                            <a class="name" href="#"><b>{{ $post->user->name }}</b></a>
                                <h6 class="date">on {{ $post->created_at->toFormattedDateString() }}</h6>
                            </div>

                        </div><!-- post-info -->

                        <h3 class="title"><b>{{ $post->title }}</b></h3>
                        {{-- нужно сделать для боди правила форматирования для всех тегов внутри дива post-body --}}
                        <div class="content-post-body">
                          {{--  {!! html_entity_decode($post->body) !!}  еще есть такой вариант --}}

                            {!! $post->body !!}
                        </div>
                        


                        <ul class="tags">
                            @foreach ($post->tags as $tag)
                        <li><a href="#">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div><!-- blog-post-inner -->

                    <div class="post-icons-area">
                        <ul class="post-icons">
                            <li>
                                {{-- если надо чтоб лайкал любой юзер, убираем условие гость и меняем в роутере post.favorite условие для авторизации, хотя так не сработает, наверно придется делать регистрацию--}}
                                @guest
                                    <a href="#" onclick="fav()"><i class="ion-heart"></i>
                                    {{ $post->favorite_to_users->count() }}
                                    </a>
                                @else
                                <a class="{{ !Auth::user()->favorite_posts->where('pivot.post_id', $post->id)->count() == 0 ? 'favoritePost' : 'no' }}" href="javascript::void(0)" onclick="document.getElementById('favorite-form-{{ $post->id }}').submit();"><i class="ion-heart"></i>
                                        {{ $post->favorite_to_users->count() }}
                                    </a>
                                <form method="POST" action="{{ route('post.favorite', $post) }}" id="favorite-form-{{ $post->id }}" style="display: none;">
                                    @csrf
                                </form>
                                @endguest
                            </li>
                        <li><a href="#"><i class="ion-chatbubble"></i>{{ $post->comments->count() }}</a></li>
                        <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                        </ul>

                        <ul class="icons">
                            <li>SHARE : </li>
                            <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                            <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                            <li><a href="#"><i class="ion-social-pinterest"></i></a></li>
                        </ul>
                    </div>


                </div><!-- main-post -->
            </div><!-- col-lg-8 col-md-12 -->

            <div class="col-lg-4 col-md-12 no-left-padding">

                <div class="single-post info-area">

                    <div class="sidebar-area about-area">
                        <h4 class="title"><b>ABOUT AUTHOR</b></h4>
                        <p>{{$post->user->about}}</p>
                    </div>

                    <div class="sidebar-area subscribe-area">

                        <h4 class="title"><b>Случайное место писания</b></h4>
                        {{-- <div class="input-area">
                            <form>
                                <input class="email-input" type="text" placeholder="Enter your email">
                                <button class="submit-btn" type="submit"><i class="icon ion-ios-email-outline"></i></button>
                            </form>
                        </div> --}}

                    </div><!-- subscribe-area -->

                    <div class="tag-area">

                        <h4 class="title"><b>TAG CLOUD</b></h4>
                        <ul>
                            @foreach ($tags as $tag)
                                <li><a href="#">{{ $tag->name }}</a></li>
                            @endforeach
                            
                        </ul>

                    </div><!-- subscribe-area -->
                    <hr>
                    {{-- <div class="tag-area">

                        <h4 class="title"><b>Catigories</b></h4>
                        <ul>
                            @foreach ($post->categories as $category)
                        <li><a href="#">{{ $category->name }}</a></li>
                            @endforeach
                            
                        </ul>

                    </div><!-- subscribe-area --> --}}

                </div><!-- info-area -->

            </div><!-- col-lg-4 col-md-12 -->

        </div><!-- row -->

    </div><!-- container -->
</section><!-- post-area -->


<section class="recomended-area section">
    <h4 style="margin-bottom: 10px;">Random posts</h4>
    <div class="container">
        <div class="row">
            @foreach ($randomPosts as $randomPost)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="single-post post-style-1">

                        <div class="blog-image"><img src="{{ Storage::disk('public')->url('post/' . $randomPost->image) }}" alt="Blog Image"></div>

                        <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile/' . $randomPost->user->image) }}" alt="Profile Image"></a>

                        <div class="blog-info">

                        <h4 class="title"><a href="{{ route('post.details', $randomPost->slug) }}"><b>{{ $randomPost->title }}</b></a></h4>

                            <ul class="post-footer">
                                <li>
                                    <a href="{{ route('post.details', $randomPost->slug) }}" {{-- onclick="fav()" --}} ><i class="ion-heart"></i>
                                        {{ $randomPost->favorite_to_users->count() }}
                                    </a> 
                                </li>
                                <li><a href="{{ route('post.details', $randomPost->slug) }}"><i class="ion-chatbubble"></i>{{ $randomPost->comments->count() }}</a></li>
                            <li><a href="{{ route('post.details', $randomPost->slug) }}"><i class="ion-eye"></i>{{ $randomPost->view_count }}</a></li>
                            </ul>

                        </div><!-- blog-info -->
                    </div><!-- single-post -->
                </div><!-- card -->
            </div><!-- col-md-6 col-sm-12 -->  
            @endforeach



        </div><!-- row -->

    </div><!-- container -->
</section>

<section class="comment-section">
    <div class="container">
        <h4><b>POST COMMENT</b></h4>
        <div class="row">
            @if ($errors->any())

            @endif
            <div class="col-lg-8 col-md-12">
                <div class="comment-form">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger m-t-15" role="alert">
                            {{ $error }}  
                        </div> 
                    @endforeach
                    @guest
                        <p>For post a new comment. You need  to login first. <br><br><a class="btn btn-primary" href="{{ route('login') }}">Login</a></p>
                    @else
                        <form method="post" action="{{ route('comment.store', $post) }}">
                    @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <textarea name="comment" rows="2" class="text-area-messge form-control"
                                        placeholder="Enter your comment" aria-required="true" aria-invalid="false"></textarea >
                                </div><!-- col-sm-12 -->
                                <div class="col-sm-12">
                                    <button class="submit-btn" type="submit" id="form-submit"><b>POST COMMENT</b></button>
                                </div><!-- col-sm-12 -->

                            </div><!-- row -->
                        </form>
                    @endguest

                </div><!-- comment-form -->

                <h4></a><b>COMMENTS({{ $post->comments->count() }})</b></h4>
                @foreach ($post->comments as $comment)
            <div id="comments{{$comment->id}}" class="commnets-area ">

                    <div class="comment">

                        <div class="post-info">

                            <div class="left-area">
                                <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile/' . $comment->user->image) }}" alt="Profile Image"></a>
                            </div>

                            <div class="middle-area">
                                <a class="name" href="#"><b>{{ $comment->user->name }}</b></a>
                                <h6 class="date">on {{ $comment->created_at->toFormattedDateString() }}</h6>
                            </div>

                        </div><!-- post-info -->

                    <p>{{ $comment->comment }}</p>

                    </div>

                </div><!-- commnets-area --> 
                @endforeach

                @if ($post->comments->count())
                    <a class="more-comment-btn" href="#"><b>VIEW MORE COMMENTS</a>
                @else
                <div class="commnets-area">
                    <p>No comments. Post first comment!!!</p>
                </div>
                
                @endif
                

            </div><!-- col-lg-8 col-md-12 -->

        </div><!-- row -->

    </div><!-- container -->
</section>
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