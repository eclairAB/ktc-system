<div id="customBtns">
  <div row v-if="isEdit === false"  style="height: 55px !important;">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin: 0; padding: 0;">
      <button class="btn btn-primary btn-block" @click="reroute('container-receivings')" style="border: 1px solid white;" :style="action === 'container-receivings' ? 'border-bottom: 4px solid black;' : '' ">Container Receiving</button> 
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin: 0; padding: 0;">
      <button class="btn btn-primary btn-block" @click="reroute('container-releasings')" style="border: 1px solid white;" :style="action === 'container-releasings' ? 'border-bottom: 4px solid black;' : '' ">Container Releasing</button>
    </div>
  </div>
  <div style="color: transparent;">test</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>

<script type="text/javascript">

  var app = new Vue({
    el: '#customBtns',
    data: {
      action: ''
    },
    computed: {
      isEdit () {
        let currentUrl = window.location.href
        let checkedit = currentUrl.split('/')[currentUrl.split('/').length - 1]
        if (checkedit === 'edit') {
          return true
        } else {
          return false
        }
      }
    },
    methods:{
      currentRoute () {
        let currentUrl = window.location.href
        let currentLoc = currentUrl.split('/')[currentUrl.split('/').length - 1]
        if (currentLoc === 'edit') {
          this.action = currentUrl.split('/')[currentUrl.split('/').length - 3]
        } else if (currentLoc === 'create') {
          this.action = currentUrl.split('/')[currentUrl.split('/').length - 2]
        }
      },
      reroute(x) {
        location.href = `${location.origin}/admin/${x}/create`
      }
    },
    mounted () {
      this.currentRoute()
    }
  })

</script>