<template>
  <div class="rating">
    <template v-for="n in 5">
      <span v-if="n < rating" class="star"></span>
      <span v-else></span>
    </template>
     {{reviews}}
  </div>
</template>

<script>
  export default {      
        props: ['rating', 'reviews'],
        data: function () {
          return {};
        }
      }
</script>
