@extends('voyager::master')
@section('css')
	<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
@stop
@section('content')
<body>
	<div id="container-actions">
		<div row>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin: 0; padding: 0;">
				<button class="btn btn-primary btn-block" @click="reroute('in')" style="border: 1px solid white;" :style="action === 'in' ? 'border-bottom: 4px solid black;' : '' ">Daily Container In</button>	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin: 0; padding: 0;">
				<button class="btn btn-primary btn-block" @click="reroute('out')" style="border: 1px solid white;" :style="action === 'out' ? 'border-bottom: 4px solid black;' : '' ">Daily Container Out</button>
			</div>
		</div>
		
	</div>
	<div class="row">
		@if ($tab_action === 'in')
		<div class="col-xs-12">
			@include('vendor.voyager.container-actions.in')
		</div>
		@elseif ($tab_action === 'out')
		<div class="col-xs-12">
			@include('vendor.voyager.container-actions.out')
		</div>
		@endif
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

<script>
	var vm = new Vue({
    el: '#container-actions',
    data:{
    	action: '{{ $tab_action }}'
    },
    methods: {
    	reroute(x) {
        location.href = x
      }
    },
    created() {
    	console.log('hello')
    }
  })
</script>
@endsection
