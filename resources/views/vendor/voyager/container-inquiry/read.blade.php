@extends('voyager::master')
@section('content')
<style>
    .containers_ {
        /*inline-size: fit-content;*/
      display: grid;
      justify-items: end;
      box-shadow: 0px 2px 10px 0px #00000017;
      border-radius: 5px;
      padding: 1%;
      background-color: white;
    }

    .card_ {
      box-shadow: 0px 2px 10px 0px #00000017;
      border-radius: 5px;
      padding: 1%;
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
</style>
<body>
    <div id="container-inquiry-read">
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12" style="padding: 8px !important; margin-bottom: 5px; margin-top: 10px;">
                    <div class="card_">
                        <div class="row" style="padding: 0px 10px;">
                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="container_no" id="container_no" value="{{ $receiving[0]->container_no }}" class="form-control">
                            <label for="container_no" class="form-control-placeholder"> Container No.</label>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="sizeType" id="sizeType" value="{{ $receiving[0]->sizeType->code }}" class="form-control">
                            <label for="sizeType" class="form-control-placeholder"> Size</label>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="type" id="type" value="{{ $receiving[0]->type->code }}" class="form-control">
                            <label for="type" class="form-control-placeholder"> Type</label>
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="manufactured_date" id="manufactured_date" value="{{ Carbon\Carbon::parse($receiving[0]->manufactured_date)->format('F d, Y') }}" class="form-control">
                            <label for="manufactured_date" class="form-control-placeholder"> Manufactured Date</label>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="paginator_ containers_">
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                  <th style="padding: 10px 10px;">
                                      Date received
                                  </th>
                                  <th style="padding: 10px 10px;">
                                      Inspector
                                  </th>
                                  <th style="padding: 10px 10px;">
                                      Client
                                  </th>
                                  <th style="text-align-last: end; padding: 10px 10px;">
                                      Action
                                  </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($receiving as $item)
                                    <tr style="border-top: solid #5c5c5c29 1px">
                                        <td style="padding: 0 10px">
                                            @{{ moment(`{!! $item->created_at !!}`).format('MMMM DD, YYYY') }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px">
                                            {{ $item->inspector->name }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px;">
                                            {{ $item->client->code_name }}
                                        </td>
                                        <td style="padding: 0 10px">
                                            <button style="padding: 3px 10px;" class="btn btn-sm btn-warning pull-right edit" v-on:click="viewContainerInfo( {{ $item }} )">
                                                <i class="voyager-eye"></i>&nbsp View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr style="border-top: solid #5c5c5c29 1px; font-weight: bold; color: #979797;">
                                        <td style="padding: 0 10px;">Container has no receiving record</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $receiving->links() }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="paginator_ containers_">
                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                  <th style="padding: 10px 10px;">
                                      Date released
                                  </th>
                                  <th style="padding: 10px 10px;">
                                      Inspector
                                  </th>
                                  <th style="padding: 10px 10px;">
                                      Client
                                  </th>
                                  <th style="text-align-last: end; padding: 10px 10px;">
                                      Action
                                  </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($releasing as $item)
                                    <tr style="border-top: solid #5c5c5c29 1px">
                                        <td style="padding: 0 10px">
                                            @{{ moment(`{!! $item->created_at !!}`).format('MMMM DD, YYYY') }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px">
                                            {{ $item->inspector->name }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px">
                                            {{ $item->container->receiving->client->code_name }}
                                        </td>
                                        <td style="padding: 0 10px">
                                            <button style="padding: 3px 10px;" class="btn btn-sm btn-warning pull-right edit" v-on:click="viewContainerInfo( {{ $item }} )">
                                                <i class="voyager-eye"></i>&nbsp View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr style="border-top: solid #5c5c5c29 1px; font-weight: bold; color: #979797;">
                                        <td style="padding: 0 10px;">Container has no releasing record</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $receiving->links() }}
                    </div>
                </div>
                

                <!-- modal -->
                <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                    <div class="modal-content">
                      <div class="modal-header" style="display: flex; align-items: center;">
                        <h5 class="modal-title" id="dialogLabel">Container Info</h5>
                        <button type="button" @click="close" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <hr style="margin: 0">
                      <div class="modal-body" style="margin-top: 15px">
                        <form>
                          <div class="col-lg-12 form-group mt-3" style="padding-right: 13px; padding-left: 13px;">
                            @{{containerInfo}}
                          </div>
                        </form>
                        {{-- <a href="{{ route('admin.container-inquiry.download', ['receiving', 1]) }}" class="btn buttons-zip">Download as Zip<i class="fas fa-file-download"></i></a> --}}
                        <a
                            v-if="containerInfo"
                            :href="getDownloadPath"
                            class="btn buttons-zip"
                        >
                            Download as Zip
                            <i class="fas fa-file-download"></i>
                        </a>
                      </div>
                  </div>
                </div>
                <!-- end of modal -->
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
<script>
    var vm = new Vue({
    el: '#container-inquiry-read',
    data:{
        containerInfo: {}
    },
    computed: {
        getDownloadPath() {
            if (this.containerInfo.id) {
                return `download/${this.containerInfo.photos[0].container_type}/${this.containerInfo.id}`
            }
            else return null
        }
    },
    methods: {
        viewContainerInfo(payload) {
            this.containerInfo = payload
            $('#dialog').modal({backdrop: 'static', keyboard: true});
        },
        close () {
            this.containerInfo = {}
        }
    }
  })
</script>
@endsection
