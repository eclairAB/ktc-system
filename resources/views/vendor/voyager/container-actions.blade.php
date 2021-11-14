@extends('voyager::master')
@section('content')
<body>
	<div id="container-actions">
		<div>
			<h1>
				action: @{{ action }}
			</h1>
		</div>
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
	var vm = new Vue({
    el: '#container-actions',
    data:{
    	action: '{{ $tab_action }}'
    },
    created() {
    	console.log('hello')
    }
  })
</script>
@endsection
