@extends('voyager::master')
@section('content')
<body>
  <div id="dashboard" class="clearfix container-fluid row">
    <div class="col-lg-5 col-md-12">
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" @click="reroute('container-receivings/create')">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Container Receiving</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" @click="reroute('container-releasings/create')">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Container Releasing</h4>
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