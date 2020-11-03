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
                    @if(in_array($lang->id, $programLangs))
                        <label class="form-check-label mr-2"><input type="checkbox" class="form-check-input" value="{{$lang->id}}" checked>{{$lang->name}}</label>
                    @else
                        <label class="form-check-label mr-2"><input type="checkbox" class="form-check-input" value="{{$lang->id}}">{{$lang->name}}</label>
                    @endif
                @endif
            @endforeach
        </div>

        <form id="form_data" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="programs_counter" id="programs_counter" value="{{ count($programLangs) }}">
            <input type="hidden" name="old_langs" id="old_langs" value="{{ implode(',', $programLangs) }}">
            <input type="hidden" name="new_langs" id="new_langs" value="{{ implode(',', $programLangs) }}">
            <input type="hidden" name="deleted_photo" id="deleted_photo" value="empty">
            {{--Start All Programs--}}
            <div id="all_programs" class="row">
                {{--Start Program--}}
                @foreach($program->programContents as $programContent)
                    <div id="program_{{$programContent->lang_id}}" class="col-sm-5 p-3 mx-3 mb-3 new_program" style="border: 2px dashed gray; border-radius: 10px">
                        <h4>{{ \App\Models\Lang::where('id', $programContent->lang_id)->first()->name }} : </h4>
                        <div class="form-row form-group">
                            <input type="text" value="{{$programContent->name}}" class="form-control program_name" name="program_name_{{$programContent->lang_id}}" id="program_name_{{$programContent->lang_id}}" placeholder="Program Name">
                            <small id="valErr_program_name_{{$programContent->lang_id}}" class="text-danger"></small>
                        </div>
                        <div class="form-row form-group">
                            <textarea rows="5" value="{{$programContent->description}}" class="form-control program_description" name="program_description_{{$programContent->lang_id}}" id="program_description_{{$programContent->lang_id}}" placeholder="Program Description">{{$programContent->description}}</textarea>
                            <small id="valErr_program_description_{{$programContent->lang_id}}" class="text-danger"></small>
                        </div>
                    </div>
                @endforeach
                {{--End Program--}}
            </div>
            {{--End All Programs--}}
            <div class="form-row form-group">
                <input type="file" name="program_photo" id="program_photo" class="form-control program_photo" >
                <small id="valErr_program_photo" class="text-danger valErr_program_photo"></small>
            </div>
            <div id="images_album" class="images_album">
                <div class="old_photo d-inline-block" id="{{$program->photo}}">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <img src="{{ url('uploads/backend/programs/images/250x250') }}/{{$program->photo}}" style='width: 150px; height: 150px; margin: 0 10px 10px 0'>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Insert</button>
        </form>

    </div>
@stop

@push('scripts')

    <script src="{{asset('js/jquery.loadingModal.js')}}"></script>

    <script>

        $(document).ready(function(){
            var counter = "{{ count($program->programContents) }}";
            var langs =  $('#new_langs').val().split(',');
                /////////////////////////////////////////////// Begin Send Data ////////////////////////////////////////////

            $(document).on('submit', '#form_data', function(e){
                e.preventDefault();
                var formData = new FormData($('#form_data')[0]);
                $('#form_data input,textarea').removeClass('is-invalid');
                $("small[id*='valErr']").text('');

                $.ajax({
                    url: "{{ route('programs.update', ['id' => $program->id]) }}",
                    method: "post",
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    success: function(data){
                        if(data.status == true){                 // validation passes
                            $('#success_alert').text(data.msg).fadeIn(2000);
                            $('html, body').animate({scrollTop: '0px'}, 300);
                        }else if(data.status == false){              // validation fails
                            $.each(data.msg, function(index, value){
                                $('#'+index).addClass('is-invalid');
                                $('#valErr_'+index).text(value[0]);

                                if(index.includes('program_photo')){
                                    $('.new_photo').remove();
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
                    $('#new_langs').val(langs);
                    counter++;
                    $('#programs_counter').val(counter);
                    console.log(langs);
                }else{
                    $('#all_programs #program_'+newLang).remove();
                    langs.splice(langs.indexOf($(this).val()), 1);
                    $('#new_langs').val(langs);
                    counter--;
                    $('#programs_counter').val(counter);
                    console.log(langs);
                }
            });


            //////////////////////////////////////////////// Begin Add new Program //////////////////////////////////////////

            //////////////////////////////////////////////// Begin show selected Photo /////////////////////////////////
            function readURL(input) {
                var images_album = $('#images_album');
                $(images_album).empty();

                if (input.files && input.files[0] &&input.files[0]['type'].includes('image/')) {
                    var reader;
                    reader= new FileReader();
                    reader.onload = function(e) {
                        $(images_album).append("<div class='new_photo d-inline-block'><img src="+e.target.result+" style=\'width: 150px; height: 150px; margin: 0 10px 10px 0\'></div>");
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }
            $("#program_photo").change(function() {
                readURL(this);

            });

            $(document).on('click', '.close', function(){               // delete photo
                parentDiv = $(this).parent();
                $('#deleted_photo').val($(parentDiv).attr('id'));
                parentDiv.remove();
            });

            ///////////////////////////////////////// End show selected Photo /////////////////////////////////////////////////////////

        });

    </script>

@endpush


