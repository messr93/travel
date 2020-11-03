@extends('layouts.admin')
@push('css')
    <link rel="stylesheet" href="{{asset('css/jquery.loadingModal.css')}}">
@endpush
@section('content')
    <div class="container-fluid" style="margin-top: 50px">
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

        <form id="form_data" enctype="multipart/form-data" class="form_data">
            @csrf
            <input type="hidden" id="offer_langs" name="offer_langs" value="1">
            <input type="hidden" id="offer_counter" name="offer_counter" value="1">
            <input type="hidden" class="offer_lat" name="offer_lat" id="offer_lat">
            <input type="hidden" class="offer_lng" name="offer_lng" id="offer_lng">
            {{--Start select Program, City, region--}}
            <div class="form-group form-row mx-auto" style="width: 60%">
                <div class="col">
                    <label for="sel1">Select Program:</label>
                    <select class="program_id form-control" id="program_id" name="program_id">
                        <option class="default_program_option">Select program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program['id'] }}">{{ $program['name'] }}</option>
                        @endforeach
                    </select>
                    <small id="valErr_program_id" class="text-danger"></small>
                </div>
                <div class="col">
                    <label for="sel1">Select City:</label>
                    <select class="city_id form-control" id="city_id" name="city_id">
                        <option class="default_city_option">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="sel1">Select Region:</label>
                    <select class="region_id form-control" id="region_id" name="region_id" disabled>
                        <option class="default_region_option">Select Region</option>
                    </select>
                </div>
                <div class="col">
                    <label for="sel1">Select owner:</label>
                    <select class="region_id form-control" id="user_id" name="user_id">
                        @foreach(\App\User::select('id', 'name')->get() as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{--Start adding prices--}}
            <div class="form-group form-row mx-auto price_row" style="width: 60%">
                <div class="col price_col">
                    <input type="text" class="form-control offer_price" name="offer_price[]" id="offer_price_0" placeholder="Offer Price">     {{--Add is-invalid class--}}
                    <small id="valErr_offer_price_0" class="text-danger valErr_offer_price"></small>
                </div>
                <div class="col price_hint_col" style="display: none">
                    <input type="text" class="form-control offer_price" name="offer_price_hint[]" id="offer_price_hint_0" placeholder="Offer Price hint">     {{--Add is-invalid class--}}
                    <small id="valErr_offer_price_hint_0" class="text-danger valErr_offer_price_hint"></small>
                </div>
            </div>

            {{--Start All Offers--}}
            <div id="all_offers" class="row">
                {{--Start Offer--}}
                <div id="offer_1" class="col-sm-5 p-3 mx-3 mb-3 new_offer" style="border: 2px dashed gray; border-radius: 10px">
                    <h4>English : </h4>
                    <div class="form-row form-group">
                        <input type="text" class="form-control offer_name" name="offer_name_1" id="offer_name_1" placeholder="Offer Name">     {{--Add is-invalid class--}}
                        <small id="valErr_offer_name_1" class="text-danger"></small>
                    </div>
                    <div class="form-row form-group">
                        <textarea rows="5" class="form-control offer_description" name="offer_description_1" id="offer_description_1" placeholder="Offer Description"></textarea>
                        <small id="valErr_offer_description_1" class="text-danger"></small>
                    </div>
                    <div class="form-row form-group">
                        <input type="text" class="form-control offer_address" name="offer_address_1" id="offer_address_1" placeholder="Offer Address">
                        <small id="valErr_offer_address_1" class="text-danger"></small>
                    </div>
                </div>
                {{--End Offer--}}
            </div>
            {{--End All Offers--}}

            <div class="form-row form-group">
                <input type="file" name="offer_photo[]" id="offer_photo" class="form-control offer_photo" multiple>
                <small id="valErr_offer_photo" class="text-danger valErr_offer_photo"></small>
            </div>
            <div id="images_album" class="images_album">
                {{--<img src="" id="previewImg" style="width: 150px; height: 150px; display: none">--}}
            </div>
            <div class="form-row form-group mx-auto offer_map" id="map" style="height: 500px ;">
                {{--Empty for the map--}}
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">Insert</button>
        </form>

    </div>
@stop

@push('scripts')

    <script defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOxYa5cCGkr-04c57Uq5veUPSjy5fvzo4&callback=initmap">
    </script>
    <script src="{{ asset('assets/maps/maps_handling_offers.js') }}"></script>
    <script src="{{asset('js/jquery.loadingModal.js')}}"></script>

    <script>
        $(document).ready(function(){
            var langs = ["1"];
            var offer_counter = 1;

            /////////////////////////////////////////////// Begin Send Data ////////////////////////////////////////////
            $(document).on('submit', '#form_data', function(e){
                e.preventDefault();
                var formData = new FormData($('#form_data')[0]);
                //clear valiErrors from last check
                $('#form_data input,textarea,select').removeClass('is-invalid');
                $("small[id*='valErr']").text('');

                $.ajax({
                    url: "{{ route('offers.insert') }}",
                    method: "post",
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                   /* beforeSend:function(){
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
                            /*$('#form_data').loadingModal('destroy');*/
                            $('#success_alert').text(data.msg).fadeIn(2000);
                            $('#form_data .form-control,#program_id').val('');
                            $('#images_album').empty();
                            $('html, body').animate({scrollTop: '0px'}, 300);
                        }else if(data.status == false){              // validation fails
                            /*$('#form_data').loadingModal('destroy');*/
                            $.each(data.msg, function(index, value){
                                $('#'+index).addClass('is-invalid');
                                $('#valErr_'+index).text(value[0]);

                                if(index.includes('offer_photo') && index.includes('.')){
                                    $('.images_album').empty();
                                    var arr = index.split('.');
                                    $('#'+arr[0]).addClass('is-invalid');
                                    $('#valErr_'+arr[0]).text(value[0]);
                                }

                                if(index.includes('offer_price') && index.includes('.')){
                                    var arr = index.split('.');
                                    console.log('#'+arr[0]+'_'+arr[1]);
                                    $('#'+arr[0]+'_'+arr[1]).addClass('is-invalid');
                                    $('#valErr_'+arr[0]+'_'+arr[1]).text(value[0]);
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

            ///////////////////////////////////////////////Begin add form based on lang //////////////////////////////////////////////
            $(document).on('change', '.form-check-input', function(){

                var newLang = $(this).val();
                var newLangText = $(this).parent().text();

                if(this.checked){

                    var newOffer = "<div id=\"offer_"+newLang+"\" class=\"col-sm-5 p-3 mx-3 mb-3 new_offer\" style=\"border: 2px dashed gray; border-radius: 10px\">\n" +
                        "                    <h4>"+newLangText+" : </h4>\n" +
                        "                    <div class=\"form-row form-group\">\n" +
                        "                        <div class=\"col\">\n" +
                        "                            <input type=\"text\" class=\"form-control offer_name\" name=\"offer_name_"+newLang+"\" id=\"offer_name_"+newLang+"\" placeholder=\"Offer Name\">     {{--Add is-invalid class--}}\n" +
                        "                            <small id=\"valErr_offer_name_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                        </div>\n" +
                        "                    </div>\n" +
                        "                    <div class=\"form-row form-group\">\n" +
                        "                        <textarea rows=\"5\" class=\"form-control offer_description\" name=\"offer_description_"+newLang+"\" id=\"offer_description_"+newLang+"\" placeholder=\"Offer Description\"></textarea>\n" +
                        "                        <small id=\"valErr_offer_description_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                    </div>\n" +
                        "                    <div class=\"form-row form-group\">\n" +
                        "                        <input type=\"text\" class=\"form-control offer_address\" name=\"offer_address_"+newLang+"\" id=\"offer_address_"+newLang+"\" placeholder=\"Offer Address\">\n" +
                        "                        <small id=\"valErr_offer_address_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                    </div>\n" +
                        "                </div>";

                    $('#all_offers').append(newOffer);
                    langs.push($(this).val());
                    $('#offer_langs').val(langs);
                    offer_counter++;
                    $('#offer_counter').val(offer_counter);
                    console.log(langs);
                }else{
                    $('#all_offers #offer_'+newLang).remove();
                    langs.splice(langs.indexOf($(this).val()), 1);
                    $('#offer_langs').val(langs);
                    offer_counter--;
                    $('#offer_counter').val(offer_counter);
                    console.log(langs);
                }
            });

            ///////////////////////////////////////////////End add form based on lang //////////////////////////////////////////////

            /////////////////////////////////////////////// Begin get Regions ////////////////////////////////////////////
            $(document).on('change', '#city_id', function(){
                var cityId = $(this).val();
                $.ajax({
                    url: "{{ route('getRegions') }}",
                    method: "post",
                    data: {
                        "_token": "{{csrf_token()}}",
                        cityId: cityId,
                    },
                    success: function(data){
                        $('#region_id .newRegion').remove();         // remove old regions
                        $('.default_city_option').hide()             // hide (select city first option)
                        $('.default_region_option').show();          // show (select regions first option)

                        $.each(data.data, function(index, value){
                            $('#region_id').append("<option class='newRegion' value="+value.id+">"+value.name+"</option>");
                        });
                        $('#region_id').attr('disabled', false);
                    },
                    error: function(reject){

                    }
                });
            });

            $(document).on('change', '#region_id', function(){
                $('.default_region_option').hide();                     // hide (select region first option)
            });
            $(document).on('change', '#program_id', function(){         // hide (select program first option)
                $('.default_program_option').hide();
            });

            /////////////////////////////////////////////// End get Regions ////////////////////////////////////////////

            //////////////////////////////////////////////// Begin show selected Photo /////////////////////////////////
            function readURL(input) {
                var images_album = $('#images_album');
                $(images_album).empty();

                if (input.files && input.files[0]) {
                    var reader;
                    for(var i=0; i < input.files.length; i++){
                        if(input.files[i]['type'].includes('image/')){
                            reader= new FileReader();
                            reader.onload = function(e) {
                                $(images_album).append("<img src="+e.target.result+" style=\'width: 150px; height: 150px; margin: 0 10px 10px 0\'>");
                            }
                            reader.readAsDataURL(input.files[i]); // convert to base64 string
                        }
                    }

                }
            }
            $("#offer_photo").change(function() {
                readURL(this);

            });

            ///////////////////////////////////////// End show selected Photo /////////////////////////////////////////////////////////



        });

    </script>
@endpush


