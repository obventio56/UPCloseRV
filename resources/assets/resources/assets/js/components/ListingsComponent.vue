<template>	
<div v-if="displayedListings.length != 0" class="prop-list">    
  <div class="prop" v-for="(listing, index) in displayedListings">
    <div v-bind:class="{'selected':selectedIndex == listing.listingsIndex}">
					<div class="grid">
						<div class="prop-img" v-bind:style="{ backgroundImage: 'url(' + listing.image_url + ')' }" ></div>
						<div class="big-deats">
							<p class="h10">{{ listing.city }}, {{ listing.state }}</p>
							<p class="h2">{{ listing.name }}</p>
							<p class="h11">
                <span v-if="listing.property_type_id === 1">
									Privately Owned
                </span>
                <span v-else-if="listing.property_type_id === 2">
									Public Park
								</span>
                <span v-else>
									Commercially Owned
								</span>
								<span>Fits {{ listing.max_vehicle_length }}' RV or smaller</span>
							</p>
              <div class="amenities">
                {{listing.amenities}}
              </div>
						</div>
						<div class="small-deats">
              <div v-if="listing.month_rental">
								<p class="h8">${{ listing.month_pricing }} per month</p>
							</div>
              <div v-if="listing.day_rental">
								<p class="h8">${{ listing.day_pricing }} per night</p>
							</div>
                <stars-component v-bind:rating="listing.stars" v-bind:reviews="listing.reviews"></stars-component> 
							<a v-bind:href="listing.url" class="button listing">View</a>
						</div>
           
					</div>
          </div>
				</div>
        <paginate
                :page-count="pageCount"
                :click-handler="fetch"
                :prev-class="'fas fa-chevron-left'"
                :next-class="'fas fa-chevron-right'"
                :prev-text="''"
                :next-text="''"
                :container-class="'pagination'"
                ref="paginate"
                >
        </paginate>
      </div>
  <div class="noListings" v-else>
    <h1 class="h3">
      No listings found. Looks like we haven't mapped this territory yet!
    </h1>
    <p>Try seaching somewhere else</p>
  </div>
 </template>
 
 <script>
    export default {      
        props: ['listings', 'selectedIndex'],
        data: function () {
          return {
            displayedListings: [],
            pageCount: 1,
            pageLength: 10
          };
        },
        created() {
          this.fetch();
        },
        watch: {
            selectedIndex: function (val, oldVal) {
            console.log("selectedIndex changed", val)
            //this.$refs.paginate.selected = Math.floor(val/this.pageLength)
            this.setActiveListing(val)
          }
        },
        methods: {
          fetch(page = 1) {
              console.log(page)
              var startIndex = this.pageLength*(page - 1);
              console.log(this.listings)
              //we're going to pull a "page" of the listings array into the displayedListings array
              this.displayedListings = this.listings.slice(startIndex, Math.min(this.listings.length, startIndex + this.pageLength)); 
              this.pageCount = Math.ceil(this.listings.length/this.pageLength);
          },
          setActiveListing(selectedIndex = 0) {
            console.log("changing page", selectedIndex)
            console.log("new page", Math.floor(selectedIndex/this.pageLength))
            this.fetch(Math.floor(selectedIndex/this.pageLength) + 1)
            this.$refs.paginate.selected = Math.floor(selectedIndex/this.pageLength)
          }
        }
    }
</script>