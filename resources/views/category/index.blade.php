@extends('layouts.frontend.app')

@section('title', 'All Categories')


@push('css')
<link href="{{ asset('assets/frontend/css/categories/styles.css') }}" rel="stylesheet">

<link href="{{ asset('assets/frontend/css/categories/responsive.css') }}" rel="stylesheet">
<style>
    .favoritePost {
        color: red;
    }
</style>  
@endpush

@section('content')
<div class="slider display-table center-text">
    <h1 class="title display-table-cell"><b>All Categories</b></h1>
</div><!-- slider -->

<section class="blog-area section">
    <div class="container">

        <div class="row">
            @foreach ($categories as $category)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="single-post post-style-1">

                            <div class="blog-image"><img src="{{ Storage::disk('public')->url('category/slider/' . $category->image) }}" alt="Blog Image"></div>

                            <div class="blog-info">

                            <h4 class="title"><a href="{{ route('category.posts', $category->slug) }}"><b>{{ $category->name }}</b></a></h4>


                            </div><!-- blog-info -->
                        </div><!-- single-post -->
                    </div><!-- card -->
                </div><!-- col-lg-4 col-md-6 -->    
            @endforeach
        </div><!-- row -->
    </div><!-- container -->
</section><!-- section -->

</section>
@endsection

@push('js')
<script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
<script src="{{ asset('assets/frontend/js/scripts.js') }}"></script>
@endpush