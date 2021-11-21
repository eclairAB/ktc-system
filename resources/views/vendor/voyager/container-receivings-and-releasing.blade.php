@extends('voyager::master')
@section('content')
<body>
	<div id="container-receivings">
		<div row>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin: 0; padding: 0;">
				<button class="btn btn-primary btn-block" @click="reroute('receiving')" style="border: 1px solid white;" :style="action === 'receiving' ? 'border-bottom: 4px solid black;' : '' ">Container Receiving</button>	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin: 0; padding: 0;">
				<button class="btn btn-primary btn-block" @click="reroute('releasing')" style="border: 1px solid white;" :style="action === 'releasing' ? 'border-bottom: 4px solid black;' : '' ">Container Releasing</button>
			</div>
		</div>
	</div>
	<div class="row">
		@if ($tab_action === 'receiving')
		<div class="col-xs-12">
		</div>
		@elseif ($tab_action === 'releasing')
		<div class="col-xs-12">
		</div>
		@endif
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
	var vm = new Vue({
    el: '#container-receivings',
    data:{
    	action: '{{ $tab_action }}'
    },
    methods: {
    	reroute(x) {
        location.href = x
      }
    }
  })
</script>
@endsection
