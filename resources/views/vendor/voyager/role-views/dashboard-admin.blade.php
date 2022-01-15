@extends('voyager::master')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>
  <div id="dashboard" class="clearfix container-fluid row">
    <div class="col-md-12">
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" v-on:click="reroute('container-inquiry/browse')">
          <h4>Container Inquiry</h4>
        </button>
      </div>
      <div>        
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" v-on:click="reroute('container-receivings/create')">
          <h4>Container Receiving</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" v-on:click="reroute('container-releasings/create')">
          <h4>Container Releasing</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" v-on:click="reroute('container-aging/all')">
          <h4>Container Aging and Inventory</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" v-on:click="reroute('container-actions/in')">
          <h4>Daily In Container</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" v-on:click="reroute('container-actions/out')">
          <h4>Daily Out Container</h4>
        </button>
      </div>
    </div>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.2.1/dist/echarts.min.js" integrity="sha256-EJb5T/UXVVwHY/BJ33bAFqyyzsAqdl4ZCElh3UYvaLk=" crossorigin="anonymous"></script>
<script>
  var vm = new Vue({
    el: '#dashboard',
    methods: {
      reroute(x) {
        location.href = x
      },
    }
  })
</script>
@stop