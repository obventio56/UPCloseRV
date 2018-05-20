@extends('layouts.app')

@section('content')

<section id="admin-dash">
	<div class="grid">
		<div class="content">		
 <p class="h6 title">User Manager</p>
			<p>
				Suspending hosts will unpublish any of their listings and cancel bookings more than 7 days out from today.
			</p>
		<table id="myTable">
				  <thead>
				    <tr>
				      <th scope="col">Name</th>
				      <th scope="col">Email</th>
					  <th scope="col">Host?</th>
				      <th scope="col">Actions</th>
				    </tr>
				  </thead>
				  <tbody>
					  @foreach($users as $user)
				    <tr>
				      <td data-label="Name">{{ $user->name }}</td>
				      <td data-label="Email"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
				      <td data-label="host">@if($user->stripe_acc) Yes @else No @endif</td>
				      <td data-label="Actions">
							@if($user->suspended)
                                <a href="{{ route('admin-unsuspend-user', ['id' => $user->id]) }}">Unsuspend</a>
                            @else
                                <a href="{{ route('admin-suspend-user', ['id' => $user->id]) }}">Suspend</a>
                            @endif
					  </td>
				    </tr>
					  @endforeach
				  </tbody>
				</table>
		</div>
		@component('components.sidebars.admin')
		
		@endcomponent
	</div>
</section>
	@endsection

@section('scripts')
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
@endsection

