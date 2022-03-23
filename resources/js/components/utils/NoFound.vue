<template>
  <div class="container">
      <div class="d-flex justify-content-center align-items-center content-found">
          <div class="text-center">
                <h1 class="display-1 ">{{ message }}</h1>
                <h6 class="display-4 text-danger"> {{ '('+code+')' }} </h6>
          </div>

      </div>
  </div>
</template>

<script>
export default {
    name: 'NoFound',
    data(){
        return {
            message: 'No Found',
            code: '404'
        }
    }
}
</script>
<style>
    .content-found{
        height: 80vh;
    }

</style>
