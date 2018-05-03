<div class="sidebar">
    {{ $slot }}
    <p class="h7">Manage Your Account</p>
    <section id="generic-tabs" class="nav">
        <ul id="tabs">
            <li><a title="Home" href="#first-tab">Your Account</a></li><!--
            --><li><a title="Photos" href="#second-tab">For Hosts</a></li>
        </ul>
        <div id="first-tab" class="tab-content">        
            <a href="{{ route('home') }}" class="h8" id="listingz">Your profile</a>
            <a href="{{ route('upcoming-trips') }}" class="h8" id="trips">Your upcoming trips</a>
            <a href="{{ route('past-trips') }}" class="h8" id="previous">Previous Trips</a>
            <a href="{{ route('favorited-listings') }}" class="h8" id="saved">Saved Listings</a>
            <a href="{{ route('messages') }}" class="h8" id="reviews">Messages</a>
            <a href="" class="h8" id="support">Support</a>
        </div>

        <div id="second-tab" class="tab-content">        
            
            <a href="" class="h8" id="trips">Your Host Profile</a>
            <a href="{{ route('view-own-listings') }}" class="h8" id="previous">Your Listings</a>
            <a href="" class="h8" id="saved">Past Rentals</a>
            <a href="{{ route('payment-dashboard') }}" class="h8" id="listingz">Payment Dashboard</a>
            <a href="{{ route('messages') }}" class="h8" id="reviews">Messages</a>
            <a href="" class="h8" id="support">Support</a>
        </div>
    </section>	
     <a href="{{ route('logout') }}" class="button listing"
          onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
          Log Out
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
</div>