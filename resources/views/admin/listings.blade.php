@extends('layouts.app')

@section('content')

<section id="admin-dash">
	<div class="grid">
		<div class="content">		

		<table id="myTable">
				  <thead>
				    <tr>
				      <th scope="col">Listing Name</th>
				      <th scope="col">Listing Owner</th>
				      <th scope="col">Verified</th>
					  <th scope="col">Admin Locked</th>
				      <th scope="col">Actions</th>
				    </tr>
				  </thead>
				  <tbody>
					  @foreach($listings as $listing)
				    <tr>
				      <td data-label="Listing Name">{{ $listing->name }}</td>
				      <td data-label="Listing Owner">{{ $listing->user_name }}</td>
				      <td data-label="Verified">@if($listing->verified) Yes @else No @endif</td>
					  <td data-label="Admin Locked">@if($listing->admin_lock) Yes @else No @endif</td>
				      <td data-label="Actions">
						  <a href="{{ route('view-listing', [$listing->id]) }}" target="_blank">View</a> | 
						  <a href="{{ route('edit-listing-p1', [$listing->id]) }}" target="_blank">Edit</a> |
						  
						  @if($listing->published)<a href="{{ route('deactivate-listing', [$listing->id]) }}">Deactivate</a> 
						  @elseif(!$listing->published && $listing->admin_lock) <a href="{{ route('activate-listing', [$listing->id]) }}">Unlock</a> @endif | 
						  
						  @if(!$listing->suspended) <a href="{{ route('suspend-listing', [$listing->id]) }}">Suspend</a> 
						  @else <a href="{{ route('unsuspend-listing', [$listing->id]) }}">Unsuspend</a> @endif |
						  
						  @if($listing->verified)  <a href="{{ route('unverify-listing', [$listing->id]) }}">Unverify</a> 
						  @else <a href="{{ route('verify-listing', [$listing->id]) }}">Verify</a> @endif
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