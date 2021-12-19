@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <style type="text/css">
      .page-title {
          height: 60px !important;
          line-height: unset !important;
          padding-top: 10px !important;
      }
    </style>
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')

    @if(Auth::user()->role->name != 'checker')
      @include('vendor.voyager.receiving-releasing-btns')
    @endif
  
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
                <div id="containerReleasing">
                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" placeholder="AUTO GENERATED" disabled v-model="form.id_no" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> EIR No.</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" disabled :value="moment(form.inspected_date).format('MMMM DD, YYYY')" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> Inspection Date</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" disabled :value="moment(form.inspected_date).format('hh:mm A')" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> Inspection Time</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="id_no" id="id_no" disabled :value="form.id ? form.inspected_by.name : loginUser" class="form-control" style="height: 37px;">
                          <label for="id_no" class="form-control-placeholder"> Inpection By</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="margin: 0; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Container Details</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="container_no" id="container_no" maxlength="13" placeholder="####-######-#" :disabled="form.id" v-model="form.container_no" @input="searchContainer()" :class="containerError.message ? 'isError form-control' : 'form-control'" style="height: 37px; text-transform:uppercase">
                          <label for="container_no" class="form-control-placeholder"> Container No. <span style="color: red"> *</span></label>
                          <div class="customErrorText" v-if="containerError.message"><small>@{{ containerError.message }}</small></div>
                          <div class="customHintText" v-else></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="client" disabled id="client" :value="containerInfo.client ? containerInfo.client.code_name : ''" style="height: 37px;" class="form-control">
                          <label for="client" class="form-control-placeholder"> Client</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="size" disabled id="size" :value="containerInfo.size_type ? containerInfo.size_type.name : ''" style="height: 37px;" class="form-control">
                          <label for="size" class="form-control-placeholder"> Size</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="class" disabled id="class" :value="containerInfo.class ? containerInfo.container_class.class_name : ''" style="height: 37px;" class="form-control">
                          <label for="class" class="form-control-placeholder"> Class</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="manufactured_date" disabled id="manufactured_date" :value="moment(containerInfo.manufactured_date).format('MMMM YYYY')" style="height: 37px;" class="form-control">
                          <label for="manufactured_date" class="form-control-placeholder"> Manufactured Date</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="empty_loaded" disabled id="empty_loaded" :value="containerInfo.empty_loaded" style="height: 37px;" class="form-control">
                          <label for="empty_loaded" class="form-control-placeholder"> Empty/Loaded</label>
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
                          <input type="text" :disabled="!isOk" name="consignee" id="consignee" v-model="form.consignee" style="height: 37px;" :class="errors.consignee ? 'isError form-control' : 'form-control'">
                          <label for="consignee" class="form-control-placeholder"> Consignee <span style="color: red"> *</span></label>
                          <div class="customErrorText"><small>@{{ errors.consignee ? errors.consignee[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" :disabled="!isOk" name="hauler" id="hauler" v-model="form.hauler" style="height: 37px;" :class="errors.hauler ? 'isError form-control' : 'form-control'">
                          <label for="hauler" class="form-control-placeholder"> Hauler <span style="color: red"> *</span></label>
                          <div class="customErrorText"><small>@{{ errors.hauler ? errors.hauler[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" :disabled="!isOk" name="plate_no" id="plate_no" v-model="form.plate_no" style="height: 37px;" :class="errors.plate_no ? 'isError form-control' : 'form-control'">
                          <label for="plate_no" class="form-control-placeholder"> Plate No. <span style="color: red"> *</span></label>
                          <div class="customErrorText"><small>@{{ errors.plate_no ? errors.plate_no[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" :disabled="!isOk" name="booking_no" id="booking_no" v-model="form.booking_no" style="height: 37px;" :class="errors.booking_no ? 'isError form-control' : 'form-control'">
                          <label for="booking_no" class="form-control-placeholder"> Booking No. <span style="color: red"> *</span></label>
                          <div class="customErrorText"><small>@{{ errors.booking_no ? errors.booking_no[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" :disabled="!isOk" name="seal_no" id="seal_no" v-model="form.seal_no" style="height: 37px;" :class="errors.seal_no ? 'isError form-control' : 'form-control'">
                          <label for="seal_no" class="form-control-placeholder"> Seal No. <span style="color: red"> *</span></label>
                          <div class="customErrorText"><small>@{{ errors.seal_no ? errors.seal_no[0] : '' }}</small></div>
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
                          <textarea v-model="form.remarks" :disabled="!isOk" rows="3" :class="errors.remarks ? 'isError form-control' : 'form-control'" style="width: 100%; height: auto !important;" placeholder="Write Something..."></textarea>  
                          <label for="consignee" class="form-control-placeholder"> Remarks </label>
                          <div class="customErrorText"><small>@{{ errors.remarks ? errors.remarks[0] : '' }}</small></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->


                  <!--  -->
                  <div style="display: none;">@{{container_photo.length}}</div>
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-xs-12" style="border-bottom: 1px solid #e4eaec; padding-bottom: 10px; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Pictures</div>
                        </div>
                        <div class="col-xs-12">
                          <input style="padding: 8px;" :disabled="!isOk" accept="image/*" type="file" class="form-control" id="images" name="images" @change="preview_images" multiple/>
                        </div>
                        <div class="col-xs-12">
                          <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" v-for="(item, index) in form.container_photo" :key="index">
                              <div class="image-container" :style="photolink(item)">
                                <a class="remove-image" @click="removeImage(item)" style="display: inline;">&#215;</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <div style="display: flex; justify-content: flex-end; padding-top: 0;" v-if="isOk === true">
                    <button style="width: 100px;" class="btn btn-primary save" :disabled="loading === true" @click="form.id ? upadteReleasing() : saveReleasing() ">@{{loading === false ? (form.id ? 'Update' : 'Save') : 'Loading...'}}</button>
                  </div>

                </div> <!-- End of Vue Container -->

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    <script type="text/javascript">
      $(function () {
          $('[id*=container_no]').keyup(function () {
              var number = $(this).val();
              var max = 13;
              if (number.length > max) {
                  $(this).val($(this).val().substr(0, max));
              }
              if (number.length == 4) {
                  $(this).val($(this).val() + '-');
              }
              else if (number.length == 11) {
                  $(this).val($(this).val() + '-');
              }
          });
      });
    </script>

    <!-- VUE -->
    <script type="text/javascript">
      var app = new Vue({
        el: '#containerReleasing',
        data: {
          form: {
            container_photo: []
          },
          container_photo: [],
          loginUser: `{!! Auth::user()->name !!}`,
          images: [],
          choosenSize: {},
          containerError: {},
          containerInfo: {},
          isOk: false,
          errors: {},
          loading: false
        },
        methods:{
          dateFormat(date) {
            return moment(date).format('MM/DD/yyyy');
          },
          searchContainer () {
            if (this.form.container_no.length === 13) {
              clearTimeout(this.timer)
              this.timer = setTimeout(() => {
                const payload = {
                  type: 'releasing',
                  container_no: this.form.container_no
                }
                axios.get(`/admin/get/receiving/details?container_no=${payload.container_no}&type=releasing`, payload)
                .then(data => {
                  this.containerError = {} 
                  this.isOk = true
                  this.containerInfo = data.data
                }).catch(error => {
                  this.isOk = false
                  this.form = {
                    inspected_date: moment().format(),
                    inspected_by: {!! Auth::user()->role->id !!},
                    container_photo: []
                  }
                  this.form.container_no = payload.container_no
                  this.containerInfo = {}
                  this.containerError = error.response.data
                })
              }, 1000)
            }
          },
          getBase64(file) {
            return new Promise((resolve, reject) => {
              const reader = new FileReader();
              reader.readAsDataURL(file);
              reader.onload = () => resolve(reader.result);
              reader.onerror = error => reject(error);
            });
          },
          photolink (payload) {
            return `background: url(${payload.storage_path})`
          },
          removeImage (imageData) {
            let photoIndex = _.findIndex(this.form.container_photo, { storage_path: imageData.storage_path })
            Vue.delete(this.form.container_photo, parseInt(photoIndex))
            this.removeSubImage(imageData.storage_path)
          },
          removeSubImage (imageData) {
            let photoIndex = _.findIndex(this.container_photo, { storage_path: imageData.storage_path })
            Vue.delete(this.container_photo, parseInt(photoIndex))
          },
          preview_images () {
            var total_file=document.getElementById("images").files.length;
            this.images = event.target.files
            for ( var i = 0; i < total_file; i++ ) {
              this.getBase64(event.target.files[i]).then(data => {
                let payload = {
                  storage_path: data
                }
                this.container_photo.push(payload)
              });
            }
            this.form.container_photo = this.container_photo
          },
          async saveReleasing (data) {
            this.loading = true
            let currentUrl = window.location.href
            let checkedit = currentUrl.split('/create')[currentUrl.split('/create').length -2]
            this.$set(this.form, 'container_no', this.form.container_no.toUpperCase())
            await axios.post('/admin/create/releasing', this.form).then(async data => {
              this.loading = false
              this.errors = {}
              let customId = data.data[0].container_id
              await axios.get(`/admin/get/print/releasing/${customId}`).then(data => {
                let pasmo = data.data
                let w = window.open();
                w.document.write(pasmo);
                w.print();
                w.close();
                setTimeout(() => { 
                    w.print();
                    w.close();
                }, 100);
              })
              window.location = checkedit
            }).catch(error => {
              this.loading = false
              this.errors = error.response.data.errors
            })
          },
          async upadteReleasing () {
            this.loading = true
            this.form.inspected_by = this.form.inspected_by.id
            await axios.post('/admin/update/releasing', this.form).then(async data => {
              this.loading = false
              this.errors = {}
              let customUrl = `${window.location.origin}/admin/container-inquiry/${this.form.container_no}`
              window.location = customUrl
            }).catch(error => {
              this.loading = false
              this.errors = error.response.data.errors
            })
          },
          async updateStaff () {
            this.customload = true
            let currentUrl = window.location.origin
            let browseUrl = `${currentUrl}/admin/staff`
            await axios.post('/admin/update/Staff', this.form).then(data => {
              this.customload = false
              this.errors = {}
              window.location = browseUrl
            }).catch(error => {
              this.customload = false
              this.errors = error.response.data.errors
            })
          },
          async getdata () {
            let currentUrl = window.location.href
            let checkedit = currentUrl.split('/')[currentUrl.split('/').length - 1]
            if (checkedit === 'edit') {
              let dataId = currentUrl.split('/')[currentUrl.split('/').length - 2]
              let payload = {
                id: parseInt(dataId)
              }
              await axios.get(`/admin/get/releasing/byId/${payload.id}`).then(data => {
                this.form = data.data
                axios.get(`/admin/get/details/forUpdate?container_no=${data.data.container_no}`, data.data).then(data => {
                  this.containerInfo = data.data
                })
                this.form.inspected_by = data.data.inspector
                for (let index of Object.keys(data.data.photos)) {
                  let wawex = {
                    storage_path: data.data.photos[index].encoded[0]
                  }
                  this.container_photo.push(wawex)
                }
                this.form.container_photo = this.container_photo
                this.isOk = true
                document.getElementById("updateBtn").style.display = 'inherit';
              }).catch(error => {
                console.log('error: ', error)
              })
            } else {
              this.form = {
                inspected_date: moment().format(),
                inspected_by: {!! Auth::user()->role->id !!},
                container_photo: []
              }
            }
          }
        },
        mounted () {
          this.getdata()
        }
      })
    </script>
    <!--  -->
@stop
