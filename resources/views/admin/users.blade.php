@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User Manager</div>

                <div class="panel-body">
                   <table>
                       <thead>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                       </thead>
                       @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>@if($user->suspended)
                                <a href="{{ route('admin-unsuspend-user', ['id' => $user->id]) }}">Unsuspend</a>
                            @else
                                <a href="{{ route('admin-suspend-user', ['id' => $user->id]) }}">Suspend</a>
                            @endif</td>
                       </tr>
                       @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
