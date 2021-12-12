@extends('voyager::master')
@section('content')
<style>
    .containers_ {
      display: grid;
      justify-items: end;
      box-shadow: 0px 2px 10px 0px #00000017;
      border-radius: 5px;
      padding: 1%;
      margin: .5%;
      background-color: white;
    }

    .paginator_ {
      height: fit-content;
    }

    section {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: center;
    }

    th {
      border-color: #eaeaea;
      background: #f8fafc;
      color: #337ab7;
    }

    td {
      line-height: 65px;
    }
</style>
<body>
    <div id="container-inquiry-read">
        <div row>
            <div class="col-md-12 paginator_ containers_">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                          <th style="padding: 0 10px;">
                              A
                          </th>
                          <th style="padding: 0 10px;">
                              Consignee
                          </th>
                          <th style="padding: 0 10px;">
                              C
                          </th>
                          <th style="text-align-last: end; padding: 0 10px;">
                              D
                          </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $containers }}
                        @forelse ($containers as $item)
                            <tr style="border-top: solid #5c5c5c29 1px">
                                <td style="padding: 0 10px">
                                    {{ $item->receiving_container_no }}&nbsp
                                </td>
                                <td style="padding: 0 10px">
                                    {{ $item->receiving_consignee }}&nbsp
                                </td>
                                <td style="padding: 0 10px">
                               
                                </td>
                                <td style="padding: 0 10px">
                                </td>
                            </tr>
                        @empty
                            <tr style="border-top: solid #5c5c5c29 1px; font-weight: bold; color: #979797;">
                                <td style="padding: 0 10px;">No record at this moment</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
