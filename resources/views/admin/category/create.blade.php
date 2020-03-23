@extends('layouts.backend.app')

@section('title', 'Create Category')

@push('css')
   
@endpush

@section('content')
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                    add new category
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
                <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                    <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.category.index') }}">BACK</a>
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')

@endpush