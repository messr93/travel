@extends('layouts.admin')

@section('content')
    <div class="container-fluid" style="margin-top: 50px">
        <div id="success_alert" class="alert alert-success" style="display: none" ></div>
        <form id="form_data">
            @csrf
            {{--Start Trip--}}
            <div class="form-row form-group">
                <div class="col">
                    <input type="text" class="form-control" name="trip_name" id="trip_name" placeholder="Trip Name">     {{--Add is-invalid class--}}
                    <small id="valErr_trip_name" class="text-danger"></small>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="trip_description" id="trip_description" placeholder="Trip description">     {{--Add is-invalid class--}}
                    <small id="valErr_trip_description" class="text-danger"></small>
                </div>
            </div>
            {{--End Trip--}}

            <img src="" id="previewImg" style="width: 150px; height: 150px; display: none">
            <button type="submit" class="btn btn-primary btn-block mt-3">Insert</button>
        </form>

    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function(){

            /////////////////////////////////////////////// Begin Send Data ////////////////////////////////////////////
            $(document).on('submit', '#form_data', function(e){
                e.preventDefault();
                var formData = new FormData($('#form_data')[0]);
                //clear valiErrors from last check
                $('#form_data input').removeClass('is-invalid');
                $("small[id*='valErr']").text('');

                $.ajax({
                    url: "{{ route('trips.insert') }}",
                    method: "post",
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    success: function(data){
                        if(data.status == true){                 // validation passes
                            $('#success_alert').text(data.msg).fadeIn(2000);
                            $('#form_data input.form-control').val('');
                            $('#previewImg').attr('src', '').removeClass("d-block mx-auto mb-3").hide();
                        }else if(data.status == false){              // validation fails
                            $.each(data.msg, function(index, value){
                                $('#'+index).addClass('is-invalid');
                                $('#valErr_'+index).text(value[0]);
                            });
                            //console.log(data.msg.name_ar[0]);
                        }

                    },
                    error: function (reject) {
                        //console.log(reject.msg);
                    },
                });

            });

            /////////////////////////////////////////////// End Send Data ////////////////////////////////////////////

            //////////////////////////////////////////////// Begin show selected Photo /////////////////////////////////
            function readURL(input) {
                if (input.files && input.files[0] && input.files[0]['type'].includes('image/')) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#previewImg').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                    $('#previewImg').addClass("d-block mx-auto mb-3").show();
                }
            }
            $("#photo").change(function() {
                readURL(this);

            });

            ///////////////////////////////////////// End show selected Photo /////////////////////////////////////////////////////////

        });

    </script>
@endpush


