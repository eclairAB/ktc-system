<div id="containerOut">

  <div class="panel panel-default" style="margin-top: 15px;">
    
    <div class="panel-body" style="background-color: #fff; padding: 5px; border: 0;">
      <div class="row">
        <div class="col-xs-12" style="margin-bottom: 0;">
          <span style="font-weight: bold; font-size: 15px;">Daily Container Out Report</span>
        </div>
      </div>
    </div>
  
  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

<script type="text/javascript">
  var app = new Vue({
    el: '#containerOut',
    data: {
      form: {},
      errors: [],
      loading: false,
      fullname: ''
    },
    methods: {
      
    }
  })

</script>

<style type="text/css">
  .form-control {
    color: black !important;
  }
</style>
