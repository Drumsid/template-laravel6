@extends('layouts.backend.app')

@section('title', 'Edit Post')

@push('css')
       <!-- Bootstrap Select Css -->
    <link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid">
    <form action="{{ route('author.post.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    <div class="row clearfix">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                    edit post
                    </h2>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger m-t-15" role="alert">
                                {{ $error }}  
                            </div> 
                        @endforeach
                    @endif

                </div>
                <div class="body">

                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" id="title" class="form-control {{ $errors->has('name') ? 'border-danger' : ''}}" name="title" value="{{ $post->title }}">
                                <label class="form-label">Post Title</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="image">Image</label>
                                <input type="file" id="image" class="form-control {{ $errors->has('name') ? 'border-danger' : ''}}" name="image" value="{{ $post->image }}">
                                
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">  
                                <input type="checkbox" id="publish" class="filled-in" name="status" value="1"
                                {{ $post->status == true ? 'checked' : ''}}>
                                <label for="publish">Publish</label>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                    edit Categories and Tags
                    </h2>
                </div>
                <div class="body">

                        <div class="form-group form-float">
                        <div class="form-line">
                                <label for="category">Select Category</label>
                                <select name="categories[]" id="category" class="form-control show-tick" data-live-search="true" multiple>
                                    @foreach ($categories as $category)
                                    <option
                                    @foreach ($post->categories as $postCategory)
                                        {{ $postCategory->id ==  $category->id ? 'selected' : ''}}
                                    @endforeach
                                         value="{{ $category->id }}"> {{ $category->name }} </option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line {{ $errors->has('categories') ? 'focused error' : '' }}">
                                <label for="tag">Select Tag</label>
                                <select name="tags[]" id="tag" class="form-control show-tick" data-live-search="true" multiple>
                                    @foreach ($tags as $tag)
                                        <option 
                                        @foreach ($post->tags as $postTag)
                                            {{ $postTag->id ==  $tag->id ? 'selected' : ''}}
                                        @endforeach
                                        value="{{ $tag->id }}"> {{ $tag->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('author.post.index') }}">BACK</a>
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">edit</button>

                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    Post body
                </div>
                
                <div class="body">
                    <textarea name="body" id="tinymce">{{ $post->body }}</textarea>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection

@push('js')
    <!-- Select Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
        <!-- TinyMCE -->
    <script src="{{ asset('assets/backend/plugins/tinymce/tinymce.js') }}"></script>
    <script>
        $(function () {
            //TinyMCE
            tinymce.init({
                selector: "textarea#tinymce",
                theme: "modern",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = '{{ asset('assets/backend/plugins/tinymce') }}';
        });
    </script>
@endpush