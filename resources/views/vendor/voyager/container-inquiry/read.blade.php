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
        <div style="display: flex;">
            <div class="col-md-6 paginator_ containers_">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                          <th style="padding: 0 10px;">
                              Date received
                          </th>
                          <th style="padding: 0 10px;">
                              Inspector
                          </th>
                          <th style="padding: 0 10px;">
                              Client
                          </th>
                          <th style="text-align-last: end; padding: 0 10px;">
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
                                    <button class="btn btn-sm btn-warning pull-right edit" v-on:click="viewContainerInfo( {{ $item }} )">
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
            <div class="col-md-6 paginator_ containers_">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                          <th style="padding: 0 10px;">
                              Date released
                          </th>
                          <th style="padding: 0 10px;">
                              Inspector
                          </th>
                          <th style="padding: 0 10px;">
                              Client
                          </th>
                          <th style="text-align-last: end; padding: 0 10px;">
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
                                    <button class="btn btn-sm btn-warning pull-right edit" v-on:click="viewContainerInfo( {{ $item }} )">
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

            <!-- modal -->
            <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
              <div class="modal-success-dialog modal-dialog" role="document" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
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
            </div>
            <!-- end of modal -->

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
                return `container-inquiry/download/${this.containerInfo.photos[0].container_type}/${this.containerInfo.id}`
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
