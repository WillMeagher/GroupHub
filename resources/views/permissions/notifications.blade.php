@extends('layouts.app')

@section('content')
    <h1>Notifications</h1>

    @if(count($newNotifications) > 0)
        <h2>&emsp;New Notifications</h2>
        <div class="card mt-3">
            <ul class="list-group list-group-flush">
            @foreach($newNotifications as $notification)
                <li class="list-group-item">
                    @if (Auth::user()->id == $notification->user_id)
                        <h3><a href="/group/{{str_replace(" ", "_", $notification->group_name)}}/view"> You have been {{$notification->status}} <?php echo $notification->status == 'Accepted' ? 'to' : 'from'?> <b>{{$notification->group_name}}</b></a></h3>
                    @elseif ($notification->status == "Accepted")
                        <h3><a href="/account/{{$notification->user_name}}/view"> <b>{{$notification->user_name}}</b> has joined <b>{{$notification->group_name}}</b></a></h3>
                    @else
                        <h3><a href="/permissions/{{$notification->id}}"> <b>{{$notification->user_name}}</b> wants to join <b>{{$notification->group_name}}</b></a></h3>
                        <small><b>Message:</b> {{$notification->message}}</small>
                    @endif
                    <small><b>Updated on:</b> {{$notification->updated_at}}</small>
                </li>
            @endforeach
            </ul>
        </div>
    @endif

    @if(count($oldNotifications) > 0)
        <h2>&emsp;Old Notifications</h2>
        <div class="card mt-3">
            <ul class="list-group list-group-flush">
            @foreach($oldNotifications as $notification)
                <li class="list-group-item">
                    @if (Auth::user()->id == $notification->user_id)
                        <h3><a href="/group/{{str_replace(" ", "_", $notification->group_name)}}/view"> You have been {{$notification->status}} <?php echo $notification->status == 'Accepted' ? 'to' : 'from'?> <b>{{$notification->group_name}}</b></a></h3>
                    @elseif ($notification->status == "Accepted")
                        <h3><a href="/account/{{$notification->user_name}}/view"> <b>{{$notification->user_name}}</b> has joined <b>{{$notification->group_name}}</b></a></h3>
                    @else
                        <h3><a href="/permissions/{{$notification->id}}"> <b>{{$notification->user_name}}</b> wants to join <b>{{$notification->group_name}}</b></a></h3>
                        <small><b>Message:</b> {{$notification->message}}</small>
                    @endif
                    <small><b>Updated on:</b> {{$notification->updated_at}}</small>
                </li>
            @endforeach
            </ul>
        </div>            
    @endif

    @if (count($oldNotifications) == 0 && count($newNotifications) == 0)
        <div class="card mt-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h3 class="m-2">No Results</h3>
                </li>
            </ul>
        </div>
    @endif
@endsection