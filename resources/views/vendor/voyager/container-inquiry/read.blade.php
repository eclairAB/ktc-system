@extends('voyager::master')
@section('content')
<style>

</style>
<body>
    <div id="container-inquiry-read">
        <div row>
            {{ $containers }}
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
    var vm = new Vue({
    el: '#container-inquiry-read',
    data:{
    },
    methods: {

    }
  })
</script>
@endsection
