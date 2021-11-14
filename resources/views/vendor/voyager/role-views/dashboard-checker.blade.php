@extends('voyager::master')
@section('content')
<body>
  <div id="dashboard" class="clearfix container-fluid row">
    <div class="col-lg-5 col-md-12">
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" @click="reroute('container-actions/in')">
          <h4>Daily In Container</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" @click="reroute('container-actions/out')">
          <h4>Daily Out Container</h4>
        </button>
      </div>
    </div>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
  var vm = new Vue({
    el: '#dashboard',
    data:{},
    methods: {
      reroute(x) {
        location.href = x
      },
    }
  })
</script>
@stop