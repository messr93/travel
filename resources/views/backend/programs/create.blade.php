@extends('layouts.admin')
@push('css')
    <link rel="stylesheet" href="{{asset('css/jquery.loadingModal.css')}}">
@endpush
@section('content')
    <div style="margin-top: 50px">
        <div id="success_alert" class="alert alert-success" style="display: none" ></div>

        <div class="form-check-inline mb-3">
            @foreach($activeLangs as $lang)
                @if($lang->id == 1)
                    <label class="form-check- mr-2"><input type="checkbox" class="form-check-input" value="1" disabled checked>English</label>
                @else
                    <label class="form-check-label mr-2"><input type="checkbox" class="form-check-input" value="{{$lang->id}}">{{$lang->name}}</label>
                @endif
            @endforeach
        </div>

        <form id="form_data" enctype="multipart/form-data">
            @csrf
            {{--Start Program--}}
            <input type="hidden" name="programs_counter" id="programs_counter" value="1">
            <input type="hidden" name="programs_langs" id="programs_langs" value="1">
            {{--Start All Programs--}}
            <div id="all_programs" class="row">
                {{--Start Program--}}
                <div id="program_1" class="col-sm-5 p-3 mx-3 mb-3 new_program" style="border: 2px dashed gray; border-radius: 10px">
                    <h4>English : </h4>
                    <div class="form-row form-group">
                        <input type="text" class="form-control program_name" name="program_name_1" id="program_name_1" placeholder="Program Name">     {{--Add is-invalid class--}}
                        <small id="valErr_program_name_1" class="text-danger"></small>
                    </div>
                    <div class="form-row form-group">
                        <textarea rows="5" class="form-control program_description" name="program_description_1" id="program_description_1" placeholder="Program Description"></textarea>
                        <small id="valErr_program_description_1" class="text-danger"></small>
                    </div>
                </div>
                {{--End Program--}}
            </div>
            {{--End All Programs--}}
            <div class="form-row form-group">
                <input type="file" name="program_photo" id="program_photo" class="form-control program_photo" >
                <small id="valErr_program_photo" class="text-danger valErr_program_photo"></small>
            </div>
            <img src="" id="previewImg"  style="width: 150px; height: 150px; margin-bottom: 10px; display: none">

            <button type="submit" class="btn btn-primary btn-block">Insert</button>
        </form>

    </div>
@stop

@push('scripts')

    <script src="{{asset('js/jquery.loadingModal.js')}}"></script>

    <script>

        $(document).ready(function(){
            var counter = 1;
            var langs = ["1"];
            /////////////////////////////////////////////// Begin Send Data ////////////////////////////////////////////

            $(document).on('submit', '#form_data', function(e){
                e.preventDefault();
                var formData = new FormData($('#form_data')[0]);
                $('#form_data input,textarea').removeClass('is-invalid');
                $("small[id*='valErr']").text('');

                $.ajax({
                    url: "{{ route('programs.insert') }}",
                    method: "post",
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
/*                    beforeSend:function(){
                        $('#form_data').loadingModal({
                            position:'auto',
                            text:'Loading ...',
                            color:'#fff',
                            opacity:'0.7',
                            backgroundColor:'rgb(0,0,0)',
                            animation:'doubleBounce'
                        });

                    },*/
                    success: function(data){
                        if(data.status == true){                 // validation passes
/*                            $('#form_data').loadingModal('destroy');*/
                            $('#success_alert').text(data.msg).fadeIn(2000);
                            $('#form_data .form-control').val('');
                            $('#previewImg').attr('src', '').hide();

                            $('html, body').animate({scrollTop: '0px'}, 300);
                        }else if(data.status == false){              // validation fails
/*                            $('#form_data').loadingModal('destroy');*/
                            $.each(data.msg, function(index, value){
                                $('#'+index).addClass('is-invalid');
                                $('#valErr_'+index).text(value[0]);

                                if(index.includes('offer_photo_') && index.includes('.')){
                                    $('.images_album').empty();
                                    var arr = index.split('.');
                                    $('#'+arr[0]).addClass('is-invalid');
                                    $('#valErr_'+arr[0]).text(value[0]);
                                }
                            });
                            //console.log(data.msg.name_ar[0]);
                        }

                    },
                    error: function (reject) {
                        $('#error_modal .modal-title').text("server Error");
                        $('#error_modal .modal-body').text(reject.responseJSON.message);
                        $('#error_modal').modal('show');
                    },
                });

            });

            /////////////////////////////////////////////// End Send Data ////////////////////////////////////////////


            //////////////////////////////////////////////// Begin Add new Program //////////////////////////////////////////

            $(document).on('change', '.form-check-input', function(){

                var newLang = $(this).val();
                var newLangText = $(this).parent().text();

                if(this.checked){

                    var newProgram = "<div id=\"program_"+newLang+"\" class=\"col-sm-5 p-3 mx-3 mb-3 new_program\" style=\"border: 2px dashed gray; border-radius: 10px\">\n" +
                        "                    <div class=\"form-row form-group\">\n" +
                        "                    <h4>"+newLangText+" : </h4>\n" +
                        "                        <input type=\"text\" class=\"form-control program_name\" name=\"program_name_"+newLang+"\" id=\"program_name_"+newLang+"\" placeholder=\"Program Name\">     {{--Add is-invalid class--}}\n" +
                        "                        <small id=\"valErr_program_name_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                    </div>\n" +
                        "                    <div class=\"form-row form-group\">\n" +
                        "                        <textarea rows=\"5\" class=\"form-control program_description\" name=\"program_description_"+newLang+"\" id=\"program_description_"+newLang+"\" placeholder=\"Program Description\"></textarea>\n" +
                        "                        <small id=\"valErr_program_description_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                    </div>\n" +
                        "                </div>";

                    $('#all_programs').append(newProgram);
                    langs.push($(this).val());
                    $('#programs_langs').val(langs);
                    counter++;
                    $('#programs_counter').val(counter);
                    console.log(langs);
                }else{
                    $('#all_programs #program_'+newLang).remove();
                    langs.splice(langs.indexOf($(this).val()), 1);
                    $('#programs_langs').val(langs);
                    counter--;
                    $('#programs_counter').val(counter);
                    console.log(langs);
                }
            });


            //////////////////////////////////////////////// Begin Add new Program //////////////////////////////////////////

            //////////////////////////////////////////////// Begin show selected Photo /////////////////////////////////
            function readURL(input) {

                if (input.files && input.files[0] && input.files[0]['type'].includes('image/')) {
                    var reader;
                    reader= new FileReader();
                    reader.onload = function(e) {
                        $('#previewImg').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string


                }

            }

            $(document).on("change", "input[id*='photo']", function(){
                readURL(this);
            });

            ///////////////////////////////////////// End show selected Photo /////////////////////////////////////////////////////////

        });

    </script>
@endpush


