<div class="card">
    <div class="card-header">Users</div>

    <div class="card-body">
        @if ($users->isEmpty())
            <p>No users</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($users as $user)
                <li>
                    <a href="{{ route('messages.chat', [ 'id'=>$user_id->id, 'ids' => auth()->user()->id  . '-' . $user->id ]) }}" class="list-group-item list-group-item-action">{{ $user->name }} <p style="color:#2196f3"><i>{{$user->company_name}}</i></p></a>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
