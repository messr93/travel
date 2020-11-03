<a href="{{ route('offers.edit', ['id' => $offer_id]) }}" class="btn btn-outline-info" id="{{$offer_id}}">Edit</a>
<button class="btn btn-outline-danger delete-btn" id="{{$offer_id}}" name="{{$name}}">Delete</button>
<button class="btn btn-outline-{{ $status == 1? 'warning': 'success' }}  change-status-btn" id="{{$offer_id}}">{{ $status == 1? 'Deactivate': 'Activate' }}</button>
