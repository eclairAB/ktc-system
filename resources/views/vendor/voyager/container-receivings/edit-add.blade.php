@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="containerReceiving">
                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" placeholder="AUTO GENERATED" readonly v-model="form.id_no" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> EIR No.</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" readonly :value="moment().format('MMMM DD, YYYY')" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> Inspection Date</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" readonly :value="moment().format('hh:mm')" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> Inspection Time</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" readonly value="" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> Inpection By</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="container_no" id="container_no" v-model="form.container_no" class="form-control" style="height: 37px;">
                          <label for="container_no" class="form-control-placeholder"> Container No.</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            class="form-control" 
                            :options="sizeTypeList"
                            v-model="choosenSize"
                            label="name"
                            :filter="fuseSize"
                            @option:selected="clearSize()"
                            :reset-on-options-change='true'
                          >
                            <template #search="{attributes, events}">
                              <input
                                class="vs__search"
                                v-bind="attributes"
                                v-on="events"
                                v-model="sizeSearch"
                                @input="searchSize()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.code}} - @{{option.name}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.code}} - @{{option.name}}
                            </template>
                          </v-select>
                          <label for="lastname" class="form-control-placeholder"> Size Type</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            class="form-control" 
                            :options="heightList"
                            v-model="form.height"
                            label="height_name"
                            :filter="fuseHeight"
                            @option:selected="clearHeight()"
                            :reset-on-options-change='true'
                            :reduce="height_name => height_name.id"
                          >
                            <template #search="{attributes, events}">
                              <input
                                class="vs__search"
                                v-bind="attributes"
                                v-on="events"
                                v-model="heightSearch"
                                @input="searchHeight()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.height_code}} - @{{option.height_name}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.height_code}} - @{{option.height_name}}
                            </template>
                          </v-select>
                          <label for="password" class="form-control-placeholder"> Height</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            class="form-control" 
                            :options="clientList"
                            v-model="form.client_id"
                            label="code_name"
                            :filter="fuseClient"
                            @option:selected="clearClient()"
                            :reset-on-options-change='true'
                            :reduce="code_name => code_name.id"
                          >
                            <template #search="{attributes, events}">
                              <input
                                class="vs__search"
                                v-bind="attributes"
                                v-on="events"
                                v-model="clientSearch"
                                @input="searchClient()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.code_name}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.code_name}}
                            </template>
                          </v-select>
                          <label for="client" class="form-control-placeholder"> Client</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            class="form-control" 
                            :options="yardList"
                            v-model="form.yard_loacation"
                            label="name"
                            @option:selected="clearYard()"
                            :reset-on-options-change='true'
                            :reduce="name => name.id"
                          >
                            <template #search="{attributes, events}">
                              <input
                                class="vs__search"
                                v-bind="attributes"
                                v-on="events"
                                v-model="yardSearch"
                                @input="searchYard()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.name}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.name}}
                            </template>
                          </v-select>
                          <label for="yard_loacation" class="form-control-placeholder"> Yard Location</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="type" id="type" readonly v-model="form.type" class="form-control" style="height: 37px;">
                          <label for="type" class="form-control-placeholder"> Type</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="acceptance_no" id="acceptance_no" v-model="form.acceptance_no" class="form-control" style="height: 37px;">
                          <label for="acceptance_no" class="form-control-placeholder"> Acceptance No.</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            class="form-control" 
                            :options="classList"
                            v-model="form.class"
                            label="class_name"
                            :filter="fuseClass"
                            @option:selected="clearClass()"
                            :reset-on-options-change='true'
                            :reduce="class_name => class_name.id"
                          >
                            <template #search="{attributes, events}">
                              <input
                                class="vs__search"
                                v-bind="attributes"
                                v-on="events"
                                v-model="classSearch"
                                @input="searchClass()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.class_code}} - @{{option.class_name}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.class_code}} - @{{option.class_name}}
                            </template>
                          </v-select>
                          <label for="contact_number" class="form-control-placeholder"> Class</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            class="form-control" 
                            :options="emptyloaded"
                            v-model="form.empty_loaded"
                          ></v-select>
                          <label for="empty_loaded" class="form-control-placeholder"> Empty/Loaded</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <vuejs-datepicker
                            v-model="form.manufactured_date"
                            placeholder="mm/dd/yyyyy"
                            :input-class="'form-control'"
                            :typeable="true"
                            name="manufactured_date"
                            :format="dateFormat"
                            :required="true">
                          </vuejs-datepicker>
                          <label for="manufactured_date" class="form-control-placeholder"> Manufactured Date</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="margin: 0; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Shipment Details</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="consignee" id="consignee" v-model="form.consignee" class="form-control" style="height: 37px;">
                          <label for="consignee" class="form-control-placeholder"> Consignee</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="hauler" id="hauler" v-model="form.hauler" class="form-control" style="height: 37px;">
                          <label for="hauler" class="form-control-placeholder"> Hauler</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="plate_no" id="plate_no" v-model="form.plate_no" class="form-control" style="height: 37px;">
                          <label for="plate_no" class="form-control-placeholder"> Plate No.</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <textarea v-model="form.remarks" rows="3" style="width: 100%; height: auto !important;" class="form-control" placeholder="Write Something..."></textarea>  
                          <label for="consignee" class="form-control-placeholder"> Remarks</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-xs-12" style="border-bottom: 1px solid #e4eaec; padding-bottom: 10px; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Pictures</div>
                        </div>
                        <div class="col-xs-12">
                          <input style="padding: 8px;" type="file" class="form-control" id="images" name="images" @change="preview_images" multiple/>
                        </div>
                        <div class="col-xs-12">
                          <div class="row" id="image_preview"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="panel panel-bordered">
                  <div class="panel-body" style="padding: 15px;">
                    <div class="row" style="padding: 0px 10px;">
                      <div class="col-xs-12" style="border-bottom: 1px solid #e4eaec; padding-bottom: 10px; margin-bottom: 10px;">
                        <div style="font-weight: 700; font-size: 15px; color: black;">Signature</div>
                      </div>
                      <div class="col-xs-12">
                        <div style="font-weight: 700;">Draw Signature Here</div>
                        <div class="wrapper-custom">
                          <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
                        </div>
                        <div>
                          <button id="clear" class="btn btn-danger">Clear</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 0;">
                    <button style="width: 100px;" id="save" class="btn btn-primary save">Save</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@3.0.0-beta.4/dist/signature_pad.umd.min.js"></script>

    <script>
        var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
          backgroundColor: 'rgba(255, 255, 255, 0)',
          penColor: 'rgb(0, 0, 0)'
        });
        var saveButton = document.getElementById('save');
        var cancelButton = document.getElementById('clear');

        saveButton.addEventListener('click', function (event) {
          document.getElementById('containerReceiving').__vue__.saveReceiving(signaturePad.toDataURL('image/png'))
        });

        cancelButton.addEventListener('click', function (event) {
          signaturePad.clear();
        });

    </script>
    
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://unpkg.com/vue-select@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/fuse.js@6.4.6"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-date-dropdown@1.0.5/dist/vue-date-dropdown.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuejs-datepicker@1.6.2/dist/vuejs-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>

    <!-- VUE -->
    <script type="text/javascript">
      Vue.component('v-select', VueSelect.VueSelect)

      var app = new Vue({
        el: '#containerReceiving',
        components: {
          vuejsDatepicker,
        },
        data: {
          form: {
            inspected_date: moment().format(),
            inspected_by: {!! Auth::user()->role->id !!}
          },
          clientList: [],
          sizeTypeList: [],
          classList: [],
          heightList: [],
          yardList: [],
          images: [],
          choosenSize: {},
          classSearch: '',
          sizeSearch: '',
          heightSearch: '',
          clientSearch: '',
          yardSearch: '',
          emptyloaded: [
            'Empty',
            'Loaded'
          ]
        },
        methods:{
          dateFormat(date) {
            return moment(date).format('MM/DD/yyyy');
          },
          fuseSize(options, search) {
            const fuse = new Fuse(options, {
              keys: ['code', 'name'],
              shouldSort: true,
            })
            return search.length
              ? fuse.search(search).map(({ item }) => item)
              : fuse.list
          },
          clearSize () {
            this.form.size_type = this.choosenSize.id
            this.form.type = this.choosenSize.type
            this.sizeSearch = ''
          },
          searchSize () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.sizeSearch
              }
              axios.get(`/admin/container/size_type?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.sizeTypeList = data.data
              })
            }, 1000)
          },
          async getSize () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/container/size_type?keyword=${search.keyword}`, search).then( data => {
              this.sizeTypeList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          fuseClass(options, search) {
            const fuse = new Fuse(options, {
              keys: ['class_code', 'class_name'],
              shouldSort: true,
            })
            return search.length
              ? fuse.search(search).map(({ item }) => item)
              : fuse.list
          },
          clearClass () {
            this.classSearch = ''
          },
          searchClass () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.classSearch
              }
              axios.get(`/admin/container/classes?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.classList = data.data
              })
            }, 1000)
          },
          async getClass () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/container/classes?keyword=${search.keyword}`, search).then( data => {
              this.classList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          fuseHeight(options, search) {
            const fuse = new Fuse(options, {
              keys: ['height_code', 'height_name'],
              shouldSort: true,
            })
            return search.length
              ? fuse.search(search).map(({ item }) => item)
              : fuse.list
          },
          clearHeight () {
            this.heightSearch = ''
          },
          searchHeight () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.heightSearch
              }
              axios.get(`/admin/container/heights?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.heightList = data.data
              })
            }, 1000)
          },
          async getHeight () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/container/heights?keyword=${search.keyword}`, search).then( data => {
              this.heightList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          fuseClient(options, search) {
            const fuse = new Fuse(options, {
              keys: ['code_name'],
              shouldSort: true,
            })
            return search.length
              ? fuse.search(search).map(({ item }) => item)
              : fuse.list
          },
          clearClient () {
            this.sizeSearch = ''
          },
          searchClient () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.clientSearch
              }
              axios.get(`/admin/clients?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.clientList = data.data
              })
            }, 1000)
          },
          async getClient () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/clients?keyword=${search.keyword}`, search).then( data => {
              this.clientList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          clearYard () {
            this.yardSearch = ''
          },
          searchYard () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.yardSearch
              }
              axios.get(`/admin/yards?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.yardList = data.data
              })
            }, 1000)
          },
          async getYard () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/yards?keyword=${search.keyword}`, search).then( data => {
              this.yardList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          getBase64(file) {
            return new Promise((resolve, reject) => {
              const reader = new FileReader();
              reader.readAsDataURL(file);
              reader.onload = () => resolve(reader.result);
              reader.onerror = error => reject(error);
            });
          },
          preview_images () {
           var total_file=document.getElementById("images").files.length;
           this.images = event.target.files
           for ( var i = 0; i < total_file; i++ ) {
            this.getBase64(event.target.files[i]).then(data => {
              this.form.upload_photo = data
            });
            $('#image_preview').append("<div class='col-md-3'><img class='img-responsive' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
           }
          },
          async saveReceiving (data) {
            this.form.signature = data
            await axios.post('/admin/create_receiving', this.form).then(data => {
              console.log('Data: ',data)
              this.errors = {}
            }).catch(error => {
              console.log('Error: ',error)
              this.errors = error.response.data.errors
            })
          }
        },
        mounted () {
          this.getSize()
          this.getClient()
          this.getYard()
          this.getClass()
          this.getHeight()
        }
      })
    </script>
    <!--  -->
@stop
