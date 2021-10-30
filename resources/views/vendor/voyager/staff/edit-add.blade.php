@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

                <div class="panel panel-bordered">
                    <div id="staffForm">
                        <div class="panel-body">
                            <div class="row" style="padding: 0px 10px;">
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="id_no" id="id_no" v-model="form.id_no" class="form-control" :class="errors.id_no ? 'isError' : ''">
                                <label for="id_no" class="form-control-placeholder"> ID No.</label>
                                <div class="customErrorText"><small>@{{ errors.id_no ? errors.id_no[0] : '' }}</small></div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="firstname" id="firstname" v-model="form.firstname" class="form-control" :class="errors.firstname ? 'isError' : ''">
                                <label for="firstname" class="form-control-placeholder"> First Name</label>
                                <div class="customErrorText"><small>@{{ errors.firstname ? errors.firstname[0] : '' }}</small></div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="lastname" id="lastname" v-model="form.lastname" class="form-control" :class="errors.lastname ? 'isError' : ''">
                                <label for="lastname" class="form-control-placeholder"> Last Name</label>
                                <div class="customErrorText"><small>@{{ errors.lastname ? errors.lastname[0] : '' }}</small></div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="contact_no" id="contact_no" v-model="form.contact_no" class="form-control" :class="errors.contact_no ? 'isError' : ''">
                                <label for="contact_no" class="form-control-placeholder"> Contact Number</label>
                                <div class="customErrorText"><small>@{{ errors.contact_no ? errors.contact_no[0] : '' }}</small></div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="user_id" id="user_id" v-model="form.user_id" class="form-control" :class="errors.user_id ? 'isError' : ''">
                                <label for="user_id" class="form-control-placeholder"> User Name</label>
                                <div class="customErrorText"><small>@{{ errors.user_id ? errors.user_id[0] : '' }}</small></div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="password" id="password" v-model="form.password" class="form-control" :class="errors.password ? 'isError' : ''">
                                <label for="password" class="form-control-placeholder"> Password</label>
                                <div class="customErrorText"><small>@{{ errors.password ? errors.password[0] : '' }}</small></div>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                                <input type="text" name="password_confirmation" id="password_confirmation" v-model="form.password_confirmation" class="form-control" :class="errors.password_confirmation ? 'isError' : ''">
                                <label for="password_confirmation" class="form-control-placeholder"> Confirm Password</label>
                                <div class="customErrorText"><small>@{{ errors.password_confirmation ? errors.password_confirmation[0] : '' }}</small></div>
                              </div>
                            </div>
                        </div>

                        <div class="panel-footer" style="display: flex; justify-content: flex-end;">
                            <button type="submit" :disabled="customload === true" class="btn btn-primary save buttonload" @click="form.id ? updateStaff() : saveStaff()" style="display: flex; align-items:center;">
                                <i :class="customload === false ? 'fa fa-save' : 'fa fa-refresh fa-spin'"></i>
                                <div style="margin-left: 5px;">@{{ customload === false ? (form.id ? 'Update' : 'Save') : 'Loading' }}</div>
                            </button>
                        </div>
                    </div>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

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

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>

    <!-- VUE -->
    <script type="text/javascript">
        var app = new Vue({
        el: '#staffForm',
        data: {
          form: {},
          errors: {},
          customload: false
        },
        methods:{
          async saveStaff () {
            this.customload = true
            let currentUrl = window.location.href
            let checkedit = currentUrl.split('/create')[currentUrl.split('/create').length -2]
            await axios.post('/admin/create/Staff', this.form).then(data => {
              this.customload = false
              this.errors = {}
              window.location = checkedit
            }).catch(error => {
              this.customload = false
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
              await axios.get(`/admin/get/Staff/byId/${payload.id}`).then(data => {
                this.form = data.data
              }).catch(error => {
                console.log('error: ', error)
              })
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
