@extends('layouts.backend.app')

@section('title', 'Author Comments')

@push('css')
    <link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="block-header">
    @if (session('successMsg'))
        <div class="alert alert-success m-t-15" role="alert">
          {{ session('successMsg') }}  
        </div> 
    @endif

    </div>
    <!-- Basic Examples -->

    <!-- #END# Basic Examples -->
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        All Comments
                        <span class="badge bg-green">{{ $comments->count() }}</span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>Comments info</th>
                                    <th>Post info</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Comments info</th>
                                    <th>Post info</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @foreach ($comments as $key => $comment)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="">
                                                        <img src="{{ Storage::disk('public')->url('profile/' . $comment->user->image) }}" height="64" width="64" class="media-object">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h4>{{ $comment->user->name }}
                                                    <small>{{ $comment->created_at->toFormattedDateString() }}</small>
                                                    </h4>
                                                <p>{{ $comment->comment }}</p>
                                                <a target="/blank" href="{{ route('post.details', $comment->post->slug . '#comments' . $comment->id) }}">Replay</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-left">
                                                    <a target="/blank" href="{{ route('post.details', $comment->post->slug) }}">
                                                        <img src="{{ Storage::disk('public')->url('post/' . $comment->post->image) }}" height="64" width="64" class="media-object">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <a target="/blank" href="{{ route('post.details', $comment->post->slug) }}">
                                                        <h4 class="media-heading">
                                                            {{ str_limit($comment->post->title, 40) }}
                                                        </h4>
                                                    </a>
                                                    <p>by <strong>{{ $comment->post->user->name }}</strong></p>
                                                </div>

                                            </div>
                                        </td>
                                        <td class="text-center">
                                        <button class="btn btn-danger waves-effect" onclick="deleteComment({{ $comment->id }})">
                                            <i class="material-icons">delete</i>
                                        </button>

                                    <form id="delete-form-{{ $comment->id }}" action="{{ route('admin.comments.destroy', $comment) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Exportable Table -->
</div>
@endsection

@push('js')
    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('assets/backend/js/pages/tables/jquery-datatable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        function deleteComment(id)
        {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success m-l-15',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
            }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-form-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your imaginary file is safe :)',
                'error'
                )
            }
            })
        }
    </script>
@endpush