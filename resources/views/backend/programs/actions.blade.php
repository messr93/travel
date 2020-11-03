<a href="{{ route('programs.edit', ['id' => $id]) }}" class="btn btn-outline-info" id="{{$id}}">Edit</a>
<button class="btn btn-outline-danger delete-btn" id="{{$id}}" name="{{$name}}">Delete</button>
<button class="btn btn-outline-{{ $status == 1? 'warning': 'success' }}  change-status-btn" id="{{$id}}">{{ $status == 1? 'Deactivate': 'Activate' }}</button>
