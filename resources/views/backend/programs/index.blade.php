@extends('layouts.admin')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/plugins/datatables-mine/css/jquery.dataTables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/plugins/datatables-mine/css/buttons.dataTables.min.css')}}"/>
    {{--<link href="{{ asset('assets/admin/plugins/summernote/summernote.min.css') }}" rel="stylesheet">--}}
@endpush
@section('content')
    <div class="container-fluid" style="margin-top: 50px;">
        @include('backend.includes.sessionsFlash')

        <a href="{{ route('programs.create') }}" class="btn btn-primary mb-3 text-light ">Create new Program &nbsp;<span class="fa fa-plus"></span></a>
        <table class="table table-bordered table-hover" id="programs-table">
            <thead>
            <tr>
                <th>Photo</th>                {{-- <th style="background: none">Photo</th>--}}
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>

    </div>
@stop
@push('scripts')
    <script type="text/javascript" src="{{asset('assets/admin/plugins/datatables-mine/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/admin/plugins/datatables-mine/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('js/buttons.server-side.js') }}"></script>
    {{--<script src="{{ asset('assets/admin/plugins/summernote/summernote.min.js') }}"></script>--}}

    <script>
        $(document).ready(function(){
            /////////////////////////////// Begin Fetching All Data ////////////////////////////////////

            var table = $('#programs-table').DataTable({                         //show all categories
                processing: true,
                serverSide: true,
                order: [[ 2, 'asc' ]],
                ajax: {
                    url: " {{ route('programs.allData') }}",
                    method: "post",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'photo', name: 'photo', width: "90px", orderable: false },
                    { data: 'name', name: 'name' },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false,  },
                ]
            });

           /////////////////////////////// Ending Fetching All Data ////////////////////////////////////


            /////////////////////////////// Begin show Assurance message ////////////////////////////////////

            $(document).on('click', '.delete-btn', function(e){
                e.preventDefault();
                var id = $(this).attr('id');
                var name = $(this).attr('name');

                $('#deletion_modal .modal-title').text('Warning');
                $('#deletion_modal .modal-body').text('Sure wanna delete '+name+'\'s Program?');
                $('#deletion_modal #id_holder').val(id);
                $('#deletion_modal').modal('show');

            });

            ////////////////////////////////// End show Assurance message ////////////////////////////////////


            ////////////////////////////////// Begin Deletion //////////////////////////////////////////

            $(document).on('click', '#sure_delete', function(){

                var id = $("#id_holder").val();
                $.ajax({
                    url: "{{ route('programs.delete') }}",
                    method: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(data){

                        if(data.status == true){
                            table.ajax.reload();                            //refreshing table after deleting
                            $('#deletion_modal').modal('hide');
                        }else if(data.status == false){
                            alert(data.msg);
                            $('#deletion_modal').modal('hide');
                        }

                    },
                    error: function(reject){
                        $('#deletion_modal').modal('hide');
                        $('#error_modal .modal-title').text("server Error");
                        $('#error_modal .modal-body').text(reject.responseJSON.message);
                        $('#error_modal').modal('show');

                    }
                });

            });

            ///////////////////////////////// End Deletion ////////////////////////////////////


            ///////////////////////////// Begin change status ////////////////////////////////////////////
            $(document).on('click', '.change-status-btn', function(e){
                e.preventDefault();
                var id = $(this).attr('id');
                $.ajax({
                    url: "{{ route('programs.changeStatus') }}" ,
                    method: "post",
                    data:{
                        "_token": "{{csrf_token()}}",
                        "id": id
                    },
                    success: function(data){
                        if(data.status == true){
                            table.ajax.reload();
                        }else{
                            alert(data.msg);
                        }
                    },
                    error: function(reject){
                        $('#error_modal .modal-title').text("server Error");
                        $('#error_modal .modal-body').text(reject.responseJSON.message);
                        $('#error_modal').modal('show');

                    }
                });
            });

            ///////////////////////////// End change status ////////////////////////////////////////////
        });
    </script>
@endpush
