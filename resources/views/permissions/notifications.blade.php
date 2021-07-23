@extends('layouts.app')

@section('content')
    <h1>Notifications</h1>

        @if(count($newNotifications) > 0)
            <h2>&emsp;New Notifications</h2>
            <div class="card">
                <ul class="list-group list-group-flush">
                @foreach($newNotifications as $newNotification)
                    <li class="list-group-item">
                        <h3><a href="/group/{{$newNotification->group_id}}"> You have been {{$newNotification->status}} <?php echo $newNotification->status == 'Accepted' ? 'to' : 'from'?> <b>{{$newNotification->group_name}}</b></a></h3>
                        <small><b>Updated on:</b> {{$newNotification->updated_at}}</small>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif

        @if(count($oldNotifications) > 0)
            <h2>&emsp;Old Notifications</h2>
            <div class="card">
                <ul class="list-group list-group-flush">
                @foreach($oldNotifications as $oldNotification)
                    <li class="list-group-item">
                        <h3><a href="/group/{{$oldNotification->group_id}}"> You have been {{$oldNotification->status}}<?php echo $oldNotification->status == 'Accepted' ? 'to' : 'from'?> <b>{{$oldNotification->group_name}}</b></a></h3>
                        <small><b>Updated on:</b> {{$oldNotification->updated_at}}</small>
                    </li>
                @endforeach
                </ul>
            </div>            
        @endif
@endsection