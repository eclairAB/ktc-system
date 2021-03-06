@extends('voyager::master')
@section('content')
<body>
    <div id="container-inquiry-read">
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12" style="padding: 8px !important; margin-bottom: 5px; margin-top: 10px;">
                    <div class="card_">
                        <div class="row" style="padding: 0px 10px;">
                          <div class="col-lg-4 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="container_no" id="container_no" value="{{ $receiving[0]->container_no }}" class="form-control">
                            <label for="container_no" class="form-control-placeholder"> Container No.</label>
                          </div>
                          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="sizeType" id="sizeType" value="{{ $receiving[0]->sizeType->code }}" class="form-control">
                            <label for="sizeType" class="form-control-placeholder"> Size</label>
                          </div>
                          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="type" id="type" value="{{ $receiving[0]->type->code }}" class="form-control">
                            <label for="type" class="form-control-placeholder"> Type</label>
                          </div>
                          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="type" id="type" value="{{ $receiving[0]->containerClass->class_code }}" class="form-control">
                            <label for="type" class="form-control-placeholder"> Class</label>
                          </div>
                          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 form-group mt-3">
                            <input type="text" disabled name="manufactured_date" id="manufactured_date" value="{{ Carbon\Carbon::parse($receiving[0]->manufactured_date)->format('F, Y') }}" class="form-control">
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
                                      Inspected By
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
                                    <tr style="border-top: solid #5c5c5c29 1px;">
                                        <td style="padding: 0 10px" v-on:click="viewContainerInfo( {{ $item }} )" class="viewItemOnClick">
                                            @{{ moment(`{!! $item->created_at !!}`).format('MMMM DD, YYYY') }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px" v-on:click="viewContainerInfo( {{ $item }} )" class="viewItemOnClick">
                                            {{ $item->inspector->name }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px;" v-on:click="viewContainerInfo( {{ $item }} )" class="viewItemOnClick">
                                            {{ $item->client->code }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning pull-right edit" style="margin-right: 5px;" v-on:click="printDatareceiving( {{ $item->id }} )">
                                                <i class="voyager-file-text"></i>&nbsp Print
                                            </button>
                                            <button class="btn btn-sm btn-warning pull-right edit" v-on:click="editContainerReceiving( {{ $item }} )">
                                                <i class="voyager-edit"></i>&nbsp Edit
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
                                      Inspected By
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
                                        <td style="padding: 0 10px" v-on:click="viewContainerInfo( {{ $item }} )" class="viewItemOnClick">
                                            @{{ moment(`{!! $item->created_at !!}`).format('MMMM DD, YYYY') }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px" v-on:click="viewContainerInfo( {{ $item }} )" class="viewItemOnClick">
                                            {{ $item->inspector->name }}&nbsp
                                        </td>
                                        <td style="padding: 0 10px" v-on:click="viewContainerInfo( {{ $item }} )" class="viewItemOnClick">
                                            {{ $item->container->receiving->client->code }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning pull-right edit" style="margin-right:5px;" v-on:click="printDatareleasing( {{ $item->id }} )">
                                                <i class="voyager-file-text"></i>&nbsp Print
                                            </button>
                                            <button class="btn btn-sm btn-warning pull-right edit" v-on:click="editContainerReleasing( {{ $item }} )">
                                                <i class="voyager-edit"></i>&nbsp Edit
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
                        <button type="button" v-on:click="close" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <hr style="margin: 0">
                      <div class="modal-body" style="margin-top: 15px">
                        <form>
                          <div class="col-lg-12 form-group mt-3" style="padding-right: 13px; padding-left: 13px;">
                            {{-- @{{containerInfo}} --}}
                            <div class="container-info">
                                <div>
                                    {{-- Inspector:&nbsp@{{containerInfo.inspectors}} --}}
                                    <span>Inspected By:</span>
                                    <span>
                                        @{{containerInfo.inspector && containerInfo.inspector.name}}&nbsp
                                        ( @{{containerInfo.inspector && containerInfo.inspector.email}} )
                                    </span>
                                </div>
                                <div v-if="containerInfo.inspected_date">
                                    <span>
                                        Inspected Date:
                                    </span>
                                    <span>
                                        @{{ moment(containerInfo.inspected_date).format('MMMM DD, YYYY') }} 
                                    </span>
                                </div>
                                <div v-if="containerInfo.yard_location">
                                    <span>
                                        Yard Location:
                                    </span>
                                    <span>
                                        @{{containerInfo.yard_location && containerInfo.yard_location.name}}
                                    </span>
                                </div>
                                <!-- <div v-if="containerInfo.manufactured_date">
                                    <span>
                                        Manufactured date:
                                    </span>
                                    <span>
                                        @{{ moment(containerInfo.manufactured_date).format('MMMM DD, YYYY') }}
                                    </span>
                                </div> -->
                                <!-- <div v-if="containerInfo.container_class">
                                    <span>
                                        Class:
                                    </span>
                                    <span>
                                        @{{containerInfo.container_class && containerInfo.container_class.class_name}}
                                    </span>
                                </div> -->
                                <div v-if="containerInfo.consignee">
                                    <span>
                                        Consignee:
                                    </span>
                                    <span>
                                        @{{containerInfo.consignee}}
                                    </span>
                                </div>
                                <div v-if="containerInfo.hauler">
                                    <span>
                                        Hauler:
                                    </span>
                                    <span>
                                        @{{containerInfo.hauler}}
                                    </span>
                                </div>
                                <div v-if="containerInfo.plate_no">
                                    <span>
                                        Plate no:
                                    </span>
                                    <span>
                                        @{{containerInfo.plate_no}}
                                    </span>
                                </div>
                                <div v-if="containerInfo.remarks">
                                    <span>
                                        Remarks:
                                    </span>
                                    <span>
                                        @{{containerInfo.remarks}}
                                    </span>
                                </div>
                                <div v-if="containerInfo.client">
                                    <span>
                                        Client:
                                    </span>
                                    <span>
                                        @{{containerInfo.client && containerInfo.client.code}}
                                    </span>
                                </div>
                                <div v-if="containerInfo.damages">
                                    <span>
                                        Damages:
                                    </span>
                                </div>
                                <div>
                                    <ul>
                                        <li v-for="(item, key) in containerInfo.damages" :key="key">
                                            @{{item.description}}
                                        </li>
                                    </ul>
                                </div>
                                {{-- <div v-if="containerInfo">
                                    <span>
                                        Photos:
                                    </span>
                                    <span>
                                        @{{containerInfo.photos}}
                                    </span>
                                </div> --}}
                            </div>
                          </div>
                        </form>
                        {{-- <a href="{{ route('admin.container-inquiry.download', ['receiving', 1]) }}" class="btn buttons-zip">Download as Zip<i class="fas fa-file-download"></i></a> --}}
                        <a
                            v-if="containerInfo"
                            :href="getDownloadPath"
                            class="btn buttons-zip"
                        >
                            Download container photos
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
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
        editContainerReceiving (payload) {
            let customUrl = `${window.location.origin}/admin/container-receivings/${payload.id}/edit`
            window.location = customUrl
        },
        editContainerReleasing (payload) {
            let customUrl = `${window.location.origin}/admin/container-releasings/${payload.id}/edit`
            window.location = customUrl
        },
        async printDatareceiving (payload) {
          await axios.get(`/admin/get/print/receiving/${payload}`).then(data => {
            let pasmo = data.data
            let w = window.open('', '_blank');
            w.document.write(pasmo);
            setTimeout(() => { 
                w.print();
                w.close();
            }, 100);
          })
        },
        async printDatareleasing (payload) {
          await axios.get(`/admin/get/print/releasing/${payload}`).then(data => {
            let pasmo = data.data
            let w = window.open('', '_blank');
            w.document.write(pasmo);
            setTimeout(() => { 
                w.print();
                w.close();
            }, 100);
          })
        },
        close () {
            this.containerInfo = {}
        }
    }
  })
</script>
@endsection
