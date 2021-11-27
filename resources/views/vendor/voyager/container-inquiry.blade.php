@extends('voyager::master')
@section('content')
<body>
	<div id="container-inquiry">
		<div row>
			{{ $containers }}
		</div>		
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
	var vm = new Vue({
    el: '#container-inquiry',
    data:{
    },
    methods: {
 
    }
  })
</script>
@endsection
