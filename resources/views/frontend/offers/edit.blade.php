@extends('layouts.profile')
@push('css')
    <link rel="stylesheet" href="{{asset('css/jquery.loadingModal.css')}}">
@endpush
@section('content')
    <div class="container-fluid" style="margin-top: 50px">
        <h3 id="status_changed"></h3>
        <div id="success_alert" class="alert alert-success" style="display: none" ></div>
        <div class="form-check-inline mb-3">
            @foreach($allLangs as $lang)
                @if($lang->id == 1)
                    <label class="form-check- mr-2 ml-2"><input type="checkbox" class="form-check-input" value="1" disabled checked>English</label>
                @else
                    @if(in_array($lang->id, $offerLangs))
                        <label class="form-check-label mr-2 ml-2"><input type="checkbox" class="form-check-input" value="{{$lang->id}}" checked>{{$lang->name}}</label>
                    @else
                        <label class="form-check-label mr-2 ml-2"><input type="checkbox" class="form-check-input" value="{{$lang->id}}">{{$lang->name}}</label>
                    @endif
                @endif
            @endforeach
        </div>
        <form id="form_data" enctype="multipart/form-data" class="form_data">
            @csrf
            <input type="hidden" id="offer_old_langs" name="offer_old_langs" value="{{ implode(',', $offerLangs) }}">
            <input type="hidden" id="offer_new_langs" name="offer_new_langs" value="{{ implode(',', $offerLangs) }}">
            <input type="hidden" id="offer_counter" name="offer_counter" value="{{ count($offerLangs) }}">
            <input type="hidden" id="deleted_photos" name="deleted_photos" value="empty">
            <input type="hidden" class="offer_lat" name="offer_lat" id="offer_lat" value="{{ $offer->lat }}">
            <input type="hidden" class="offer_lng" name="offer_lng" id="offer_lng" value="{{ $offer->lng }}">
            {{--Start program & stats--}}
            <div class="form-group form-row">
                <div class="col">
                    <label for="program_id" class="d-block text-center">Change Program:</label>
                    <select class="program_id form-control" id="program_id" name="program_id">
                        @foreach($programs as $program)
                            @if($program['id'] == $offer->program_id)
                                <option value="{{ $program['id'] }}" selected>{{ $program['name'] }}</option>
                            @else
                                <option value="{{ $program['id'] }}">{{ $program['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small id="valErr_program_id" class="text-danger"></small>
                </div>
                <div class="col">
                    <label for="status" class="d-block text-center">Status:</label>
                    <select class="status form-control" id="status" name="status">
                        <option value="1" {{ ($offer->status == 'Active')? 'selected': '' }}>Active</option>
                        <option value="0"{{ ($offer->status == 'unActive')? 'selected': '' }}>unActive</option>
                    </select>
                    <small id="valErr_status" class="text-danger"></small>
                </div>
            </div>
            {{--End program & stats--}}
            {{--Start City && region --}}
            <div class="form-group form-row">
                <div class="col">
                    <label for="city_id" class="d-block text-center">Change City:</label>
                    <select class="city_id form-control" id="city_id" name="city_id">
                        @foreach($cities as $city)
                            @if($city['id'] == $offer->city_id)
                                <option value="{{ $city['id'] }}" selected>{{ $city['name'] }}</option>
                            @else
                                <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small id="valErr_city_id" class="text-danger"></small>
                </div>
                <div class="col">
                    <label for="region_id" class="d-block text-center">Change Region:</label>
                    <select class="region_id form-control" id="region_id" name="region_id">
                        <option class="default_region_option" style="display: none">Select Region</option>
                        @foreach($regions as $region)
                            @if($region['id'] == $offer->region_id)
                                <option value="{{ $region['id'] }}" class="newRegion" selected>{{ $region['name'] }}</option>
                            @else
                                <option value="{{ $region['id'] }}" class="newRegion">{{ $region['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small id="valErr_region_id" class="text-danger"></small>
                </div>
            </div>
            {{--End City && region --}}
            <div class="form-group form-row">
                @foreach($offer->prices as $price)
                <div class="col-sm-6">
                    <input type="text" value="{{$price->price}}" class="form-control offer_price" name="offer_price[{{$price->id}}]" id="offer_price_{{$price->id}}" placeholder="Offer Price">     {{--Add is-invalid class--}}
                    <small id="valErr_offer_price_{{$price->id}}" class="text-danger valErr_offer_price"></small>
                </div>
                <div class="col-sm-6">
                    <input type="text" value="{{$price->hint}}" class="form-control offer_price_hint" name="offer_price_hint[{{$price->id}}]" id="offer_price_hint_{{$price->id}}" placeholder="Offer Price hint">     {{--Add is-invalid class--}}
                    <small id="valErr_offer_price_hint_{{$price->id}}" class="text-danger valErr_offer_price_hint"></small>
                </div>
            </div>
            @endforeach
            {{--end prices--}}
            {{--Start All Offers--}}
            <div id="all_offers" class="row">
                @foreach($offer->offerContents as $offerContent)
                    <div id="offer_{{$offerContent->lang_id}}" class="col-sm-5 p-3 mx-3 mb-3 new_offer" style="border: 2px dashed gray; border-radius: 10px">
                        <h4>{{ \App\Models\Lang::where('id', $offerContent->lang_id)->first()->name }} : </h4>
                        <div class="form-row form-group">
                            <input type="text" value="{{ $offerContent->name }}" class="form-control offer_name" name="offer_name_{{$offerContent->lang_id}}" id="offer_name_{{$offerContent->lang_id}}" placeholder="Offer Name">     {{--Add is-invalid class--}}
                            <small id="valErr_offer_name_{{$offerContent->lang_id}}" class="text-danger"></small>
                        </div>
                        <div class="form-row form-group">
                            <textarea rows="5" value="{{ $offerContent->description }}" class="form-control offer_description" name="offer_description_{{$offerContent->lang_id}}" id="offer_description_{{$offerContent->lang_id}}" placeholder="Offer Description">{{ $offerContent->description }}</textarea>
                            <small id="valErr_offer_description_{{$offerContent->lang_id}}" class="text-danger"></small>
                        </div>
                        <div class="form-row form-group">
                            <input type="text" value="{{ $offerContent->address }}" class="form-control offer_address" name="offer_address_{{$offerContent->lang_id}}" id="offer_address_{{$offerContent->lang_id}}" placeholder="Offer Address">
                            <small id="valErr_offer_address_{{$offerContent->lang_id}}" class="text-danger"></small>
                        </div>
                    </div>
                @endforeach
            </div>
            {{--End All Offers--}}
            {{--Start All photos--}}
            <div class="form-row form-group">
                <input type="file" name="offer_photo[]" id="offer_photo" class="form-control offer_photo" multiple>
                <small id="valErr_offer_photo" class="text-danger valErr_offer_photo"></small>
            </div>
            <div id="images_album" class="images_album">
                @foreach($offer->photos as $photo)
                    <div class="old_photo d-inline-block" id="{{$photo->id}}">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <img src="{{ url('uploads/backend/offers/images/250x250') }}/{{$photo->photo}}" style='width: 150px; height: 150px; margin: 0 10px 10px 0'>
                    </div>
                @endforeach
            </div>
            {{--End All photos--}}
            <div class="form-row form-group mx-auto offer_map" id="map" style="height: 500px; " >
                {{--Empty for the map--}}
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3">Update</button>
        </form>

    </div>
@stop

@push('scripts')

    <script defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOxYa5cCGkr-04c57Uq5veUPSjy5fvzo4&callback=initmap">
    </script>
    <script src="{{ asset('assets/maps/maps_edit_offers.js') }}"></script>
    <script src="{{asset('js/jquery.loadingModal.js')}}"></script>
    <script src="{{asset('assets/jquery.timeago.js')}}"></script>


    <script>

        $(document).ready(function(){

            var langs = $('#offer_new_langs').val().split(',');        /// assign offerLangs to it
            var offer_counter = {{ count($offerLangs) }};
            var deleted_photos =[];

            /////////////////////////////////////////////// Begin Send Data ////////////////////////////////////////////
            $(document).on('submit', '#form_data', function(e){
                e.preventDefault();
                var formData = new FormData($('#form_data')[0]);
                //clear valiErrors from last check
                $('#form_data input,textarea,select').removeClass('is-invalid');
                $("small[id*='valErr']").text('');

                $.ajax({
                    url: "{{ route('userOffers.update', ['offer' => $offer->id]) }}",
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

                                if(index.includes('offer_photo') && index.includes('.')){
                                    $('.images_album .new_photo').remove();
                                    $('#offer_photo').addClass('is-invalid');
                                    $('#valErr_offer_photo').text(value[0]);
                                }

                                if(index.includes('offer_price') && index.includes('.')){
                                    var arr = index.split('.');
                                    console.log('#'+arr[0]+'_'+arr[1]);
                                    $('#'+arr[0]+'_'+arr[1]).addClass('is-invalid');
                                    $('#valErr_'+arr[0]+'_'+arr[1]).text(value[0]);
                                }

                            });

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
                        "                        <h4>"+newLangText+" : </h4>\n" +
                        "                        <div class=\"form-row form-group\">\n" +
                        "                            <input type=\"text\" class=\"form-control offer_name\" name=\"offer_name_"+newLang+"\" id=\"offer_name_"+newLang+"\" placeholder=\"Offer Name\">     {{--Add is-invalid class--}}\n" +
                        "                            <small id=\"valErr_offer_name_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                        </div>\n" +
                        "                        <div class=\"form-row form-group\">\n" +
                        "                            <textarea rows=\"5\" class=\"form-control offer_description\" name=\"offer_description_"+newLang+"\" id=\"offer_description_"+newLang+"\" placeholder=\"Offer Description\"></textarea>\n" +
                        "                            <small id=\"valErr_offer_description_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                        </div>\n" +
                        "                        <div class=\"form-row form-group\">\n" +
                        "                            <input type=\"text\" class=\"form-control offer_address\" name=\"offer_address_"+newLang+"\" id=\"offer_address_"+newLang+"\" placeholder=\"Offer Address\">\n" +
                        "                            <small id=\"valErr_offer_address_"+newLang+"\" class=\"text-danger\"></small>\n" +
                        "                        </div>\n" +
                        "                    </div>";

                    $('#all_offers').append(newOffer);
                    langs.push($(this).val());
                    $('#offer_new_langs').val(langs);
                    offer_counter++;
                    $('#offer_counter').val(offer_counter);
                    console.log(langs);
                }else{
                    $('#all_offers #offer_'+newLang).remove();
                    langs.splice(langs.indexOf($(this).val()), 1);
                    $('#offer_new_langs').val(langs);
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
                    method: "get",
                    data: {
                        cityId: cityId,
                    },
                    success: function(data){
                        $('#region_id .newRegion').remove();         // remove old regions
                        $('.default_region_option').show();          // show (select regions first option)

                        $.each(data.data, function(index, value){
                            $('#region_id').append("<option class='newRegion' value="+value.id+">"+value.name+"</option>");
                        });
                    },
                    error: function(reject){

                    }
                });
            });

            $(document).on('change', '#region_id', function(){
                $('.default_region_option').hide();                     // hide (select region first option)
            });

            /////////////////////////////////////////////// End get Regions ////////////////////////////////////////////

            //////////////////////////////////////////////// Begin show selected Photo /////////////////////////////////
            function readURL(input) {
                var images_album = $('#images_album');
                //$(images_album).empty();

                if (input.files && input.files[0]) {
                    var reader;
                    for(var i=0; i < input.files.length; i++){
                        if(input.files[i]['type'].includes('image/')){
                            reader= new FileReader();
                            reader.onload = function(e) {
                                $(images_album).append("<div class='new_photo d-inline-block'><img src="+e.target.result+" style=\'width: 150px; height: 150px; margin: 0 10px 10px 0\'></div>");
                            }
                            reader.readAsDataURL(input.files[i]); // convert to base64 string
                        }
                    }

                }
            }
            $("#offer_photo").change(function() {
                readURL(this);

            });

            $(document).on('click', '.close', function(){               // delete photo
                parentDiv = $(this).parent();
                deleted_photos.push(parentDiv.attr('id'));
                $('#deleted_photos').val(deleted_photos);
                console.log(deleted_photos);
                parentDiv.remove();
            });

            ///////////////////////////////////////// End show selected Photo /////////////////////////////////////////////////////////




        });

    </script>
@endpush


