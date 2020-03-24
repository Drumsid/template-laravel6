@extends('layouts.backend.app')

@section('title', 'Create Post')

@push('css')
       <!-- Bootstrap Select Css -->
    <link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row clearfix">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                    add new post
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
                                <input type="text" id="title" class="form-control {{ $errors->has('name') ? 'border-danger' : ''}}" name="title" value="{{ old('name') }}">
                                <label class="form-label">Post Title</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="image">Image</label>
                                <input type="file" id="image" class="form-control {{ $errors->has('name') ? 'border-danger' : ''}}" name="image" value="{{ old('image') }}">
                                
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">  
                                <input type="checkbox" id="publish" class="filled-in" name="status" value="1">
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
                    Categories and Tags
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
                                <label for="category">Select Category</label>
                                <select name="categories[]" id="category" class="form-control show-tick" data-live-search="true" multiple>
                                    @foreach ($categories as $category)
                                        <option value="$category->id"> {{ $category->name }} </option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                        {{-- <div class="form-group form-float">
                            <div class="form-line">
                                <label for="tag">Select Tag</label>
                                <select name="tags[]" id="tag" class="form-control show-tick" data-live-search="true" multiple>

                                </select>
                            </div>
                        </div> --}}
                    <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.post.index') }}">BACK</a>
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">Add</button>

                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header"></div>
                
                <div class="body">

                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" id="name" class="form-control {{ $errors->has('name') ? 'border-danger' : ''}}" name="name" value="{{ old('name') }}">
                                <label class="form-label">Category name</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="file" id="image" class="form-control {{ $errors->has('name') ? 'border-danger' : ''}}" name="image" value="{{ old('image') }}">
                                {{-- <label class="form-label">Category image</label> --}}
                            </div>
                        </div>
                    <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.post.index') }}">BACK</a>
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">Add</button>

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
@endpush