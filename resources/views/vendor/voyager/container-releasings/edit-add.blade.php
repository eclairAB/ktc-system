@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />     
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
    <style>
        .kbw-signature { width: 100%; height: 200px;}
        #sig canvas{ width: 100% !important; height: auto;}
    </style>  
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

                <div id="containerReleasing">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 15px 15px 0 15px;">
                            <div class="row" style="padding: 0px 10px;">
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                                <input type="text" name="booking_no" id="booking_no" v-model="form.booking_no" class="form-control" style="height: 37px;">
                                <label for="booking_no" class="form-control-placeholder"> Booking No.</label>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                                <input type="text" name="plate_no" id="plate_no" v-model="form.plate_no" class="form-control" style="height: 37px;">
                                <label for="plate_no" class="form-control-placeholder"> Plate No.</label>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                                <input type="text" name="seac_no" id="seac_no" v-model="form.seac_no" class="form-control" style="height: 37px;">
                                <label for="seac_no" class="form-control-placeholder"> Seal No</label>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                                <input type="text" name="conglone" id="conglone" v-model="form.conglone" class="form-control" style="height: 37px;">
                                <label for="conglone" class="form-control-placeholder"> Consignee</label>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                                <input type="text" name="hauler" id="hauler" v-model="form.hauler" class="form-control" style="height: 37px;">
                                <label for="hauler" class="form-control-placeholder"> Hauler</label>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                              <input type="text" name="container_no" id="container_no" v-model="form.container_no" class="form-control" style="height: 37px;">
                              <label for="container_no" class="form-control-placeholder"> Container No.</label>
                            </div>
                            </div>
                        </div>

                        <div class="panel-footer" style="display: flex; justify-content: flex-end; padding-top: 0;">
                            <button type="submit" class="btn btn-primary save">Save</button>
                        </div>
                    </div>

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

                    <div class="panel panel-bordered">
                      <div class="panel-body" style="padding: 15px;">
                        <div class="row" style="padding: 0px 10px;">
                          <div class="col-xs-12" style="border-bottom: 1px solid #e4eaec; padding-bottom: 10px; margin-bottom: 10px;">
                            <div style="font-weight: 700; font-size: 15px; color: black;">Signature</div>
                          </div>
                          <div class="col-md-6 offset-md-3 mt-5">
                            <div class="card">
                              <div class="card-body">
                                  @if ($message = Session::get('success'))
                                      <div class="alert alert-success  alert-dismissible">
                                          <button type="button" class="close" data-dismiss="alert">×</button>  
                                          <strong>{{ $message }}</strong>
                                      </div>
                                  @endif
                                  <form>
                                      @csrf
                                      <div class="col-md-12">
                                          <label class="" for="">Draw Signature:</label>
                                          <br/>
                                          <div id="sig_releasing"></div>
                                          <br><br>
                                          <button id="clear" class="btn btn-danger">Clear Signature</button>
                                          <button class="btn btn-success">Save</button>
                                          <textarea id="signature_releasing" name="signature" style="display: none"></textarea>
                                      </div>
                                  </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
    <script type="text/javascript">
        var sig_releasing = $('#sig_releasing').signature({syncField: '#signature_releasing', syncFormat: 'PNG'});
        $('#clear').click(function(e) {
            e.preventDefault();
            sig_releasing.signature('clear');
            $("#signature_releasing").val('');
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

    <!-- VUE -->
    <script type="text/javascript">
      Vue.component('v-select', VueSelect.VueSelect)
      var app = new Vue({
        el: '#containerReleasing',
        data: {
          form: {},
          images: []
        },
        methods:{
          preview_images () {
           var total_file=document.getElementById("images").files.length;
           this.images = event.target.files
           for ( var i = 0; i < total_file; i++ ) {
            console.log(event.target.files[i])
            $('#image_preview').append("<div class='col-md-3'><img class='img-responsive' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
           }
          }
        }
      })
    </script>
    <!--  -->
@stop
