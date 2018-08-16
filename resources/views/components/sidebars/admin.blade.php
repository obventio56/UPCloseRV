<div class="sidebar">
    <p class="h7">Admin Management</p>
    <div class="nav">
        <a href="{{ route('admin-listings') }}" class="h8" id="listingz">Listing Manager</a>
        <a href="{{ route('admin-users') }}" class="h8" id="users">User Manager</a>
        <a href="https://dashboard.stripe.com/login" target="_blank" class="h8" id="payments">Payments</a>
        <a href="{{ route('admin-query') }}" class="h8" id="database">Database</a>
        <!--<a href="" class="h8" id="reviews">Manage Reviews</a>-->
    </div>
	 <a href="{{ route('logout') }}" class="button listing"
	  onclick="event.preventDefault();
			   document.getElementById('logout-form').submit();">
	  Log Out
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
</div>