@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/@trevoreyre/autocomplete-vue/dist/style.css"/>
    <style type="text/css">
      .page-title {
        height: 60px !important;
        line-height: unset !important;
        padding-top: 10px !important;
      }
      .isDate::placeholder {
        color: black !important;
      }
    </style>
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    
    @if(Auth::user()->role->name != 'checker')
      @include('vendor.voyager.receiving-releasing-btns')
    @endif

    <!-- <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1> -->
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="containerReceiving">
                  <!--  -->
                  <div class="panel panel-bordered" style="margin-bottom: 5px;">
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0;">
                          <input type="text" name="id_no" id="id_no" placeholder="AUTO GENERATED" disabled v-model="form.id_no" class="form-control">
                          <label for="id_no" class="form-control-placeholder"> EIR No.</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0;">
                          <input type="text" name="id_no" id="id_no" disabled :value="moment(form.inspected_date).format('MMMM DD, YYYY')" class="form-control">
                          <label for="id_no" class="form-control-placeholder"> Inspection Date</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0;">
                          <input type="text" name="id_no" id="id_no" disabled :value="moment(form.inspected_date).format('hh:mm A')" class="form-control">
                          <label for="id_no" class="form-control-placeholder"> Inspection Time</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0;">
                          <input type="text" name="id_no" id="id_no" disabled :value="form.id ? form.inspected_by.name : loginUser" class="form-control">
                          <label for="id_no" class="form-control-placeholder"> Inpection By</label>
                        </div>

                        <div class="col-xs-12" style="padding: 0; margin: 0;">
                          <hr style="margin: 15px 0;">
                        </div>

                        {{-- <button class="btn btn-success" onclick="test()"> Add element</button> --}}
                        {{-- <button class="btn btn-success" onclick="test2()"> remove element</button> --}}

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input
                            id="container_no"
                            tabindex="1"
                            type="text"
                            name="container_no"
                            ng-maxlength="13"
                            maxlength="13"
                            placeholder="####-######-#"
                            v-model="form.container_no"
                            @input="searchContainer()":class="containerError.message ? 'isError form-control' : 'form-control'"
                            style="height: 37px; text-transform:uppercase"
                          >

                          <label for="container_no" class="form-control-placeholder"> Container No. <span style="color: red"> *</span></label>
                          <div class="customErrorText" v-if="containerError.message"><small>@{{ containerError.message }}</small></div>
                          <div class="customHintText" v-else></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            tabindex="2"
                            :class="errors.size_type ? 'isError form-control' : 'form-control'"
                            :options="sizeTypeList"
                            style="height: 30px !important;"
                            v-model="form.size_type"
                            :disabled="!isOk"
                            label="name"
                            :reduce="name => name.id"
                          ></v-select>
                          <label for="lastname" class="form-control-placeholder"> Size</label>
                          <div class="customErrorText"><small>@{{ errors.size_type ? errors.size_type[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            style="height: 30px !important;"
                            tabindex="3"
                            :class="errors.client_id ? 'isError form-control' : 'form-control'"
                            :options="clientList"
                            v-model="form.client_id"
                            :disabled="!isOk"
                            label="code_name"
                            :reduce="code_name => code_name.id"
                          ></v-select>
                          <label for="client" class="form-control-placeholder"> Client</label>
                          <div class="customErrorText"><small>@{{ errors.client_id ? errors.client_id[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            style="height: 30px !important;"
                            tabindex="4"
                            :class="errors.yard_location ? 'isError form-control' : 'form-control'"
                            :options="yardList"
                            :disabled="!isOk"
                            v-model="form.yard_location"
                            label="name"
                            :reduce="name => name.id"
                          ></v-select>
                          <label for="yard_location" class="form-control-placeholder"> Yard Location</label>
                          <div class="customErrorText"><small>@{{ errors.yard_location ? errors.yard_location[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 15px;">
                          <v-select
                            tabindex="5"
                            :class="errors.type_id ? 'isError form-control' : 'form-control'"
                            :options="typeList"
                            style="height: 30px !important;"
                            v-model="form.type_id"
                            :disabled="!isOk"
                            label="code"
                            :reduce="code => code.id"
                          ></v-select>
                          <label for="type" class="form-control-placeholder"> Type</label>
                          <div class="customErrorText"><small>@{{ errors.type ? errors.type[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 15px;">
                          <v-select
                            style="height: 30px !important;"
                            tabindex="6"
                            :class="errors.class ? 'isError form-control' : 'form-control'"
                            :options="classList"
                            v-model="form.class"
                            :disabled="!isOk"
                            label="class_code"
                            :reduce="class_code => class_code.id"
                          ></v-select>
                          <label for="contact_number" class="form-control-placeholder"> Class</label>
                          <div class="customErrorText"><small>@{{ errors.class ? errors.class[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 15px;">
                          <v-select
                            style="height: 30px !important;"
                            tabindex="7"
                            :class="errors.empty_loaded ? 'isError form-control' : 'form-control'"
                            :disabled="!isOk"
                            :options="emptyloaded"
                            v-model="form.empty_loaded"
                          ></v-select>
                          <label for="empty_loaded" class="form-control-placeholder"> Empty/Loaded</label>
                          <div class="customErrorText"><small>@{{ errors.empty_loaded ? errors.empty_loaded[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 15px;">
                          <vuejs-datepicker
                            tabindex="8"
                            v-model="form.manufactured_date"
                            :placeholder="pasmoDate === undefined ? 'mm/yyyy' : moment(pasmoDate).format('MM/yyyy')"
                            :input-class="errors.manufactured_date ? 'isError form-control isDate' : 'form-control isDate'"
                            :typeable="true"
                            name="manufactured_date"
                            :format="dateFormat"
                            minimum-view="month"
                            :disabled="!isOk"
                            :required="true"
                          >
                          </vuejs-datepicker>
                          <label for="manufactured_date" class="form-control-placeholder"> Manufactured Date</label>
                          <div class="customErrorText"><small>@{{ errors.manufactured_date ? errors.manufactured_date[0] : '' }}</small></div>
                        </div>
                      
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="margin: 0; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Shipment Details</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input
                            id="consignee"
                            tabindex="9"
                            type="text"
                            name="consignee"
                            v-model="form.consignee"
                            :disabled="!isOk"
                            :class="errors.consignee ? 'isError form-control' : 'form-control'"
                          >
                          <label for="consignee" class="form-control-placeholder"> Consignee</label>
                          <div class="customErrorText"><small>@{{ errors.consignee ? errors.consignee[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input
                            id="hauler"
                            tabindex="10"
                            type="text"
                            name="hauler"
                            v-model="form.hauler"
                            :disabled="!isOk"
                            :class="errors.hauler ? 'isError form-control' : 'form-control'"
                          >
                          <label for="hauler" class="form-control-placeholder"> Hauler</label>
                          <div class="customErrorText"><small>@{{ errors.hauler ? errors.hauler[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input
                            id="plate_no"
                            tabindex="11"
                            type="text"
                            name="plate_no"
                            v-model="form.plate_no"
                            :disabled="!isOk"
                            :class="errors.plate_no ? 'isError form-control' : 'form-control'"
                          >
                          <label for="plate_no" class="form-control-placeholder"> Plate No.</label>
                          <div class="customErrorText"><small>@{{ errors.plate_no ? errors.plate_no[0] : '' }}</small></div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 5px;">
                          <input
                            tabindex="12"
                            type="text" 
                            placeholder="Write Something..." 
                            name="remarks" 
                            id="remarks" 
                            v-model="form.remarks" 
                            :disabled="!isOk" 
                            :class="errors.remarks ? 'isError form-control' : 'form-control'"
                          >
                          <label for="consignee" class="form-control-placeholder"> Remarks</label>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0 !important; margin: 0 !important; display: flex; justify-content: space-between; align-items: center;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Damages</div>
                          <button class="btn btn-success" @click="addNew" style="height: 25px; font-size: 10px;"> Add Damage</button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="color: black !important; margin-top: 0 !important; margin-bottom: 10px;" v-if="damageList.length > 0">
                          <table border="1" cellspacing="0" cellpadding="" width="100%">
                            <tbody align="left">
                              <tr  v-for="(item, key) in damageList" :key="key">
                                <td class="border-b" style="padding: 5px; font-size: 10px;">@{{key + 1}}.) @{{item.description}}</td>
                                <td>
                                  <div style="display: flex; justify-content: center; width: 100%">
                                    <button class="btn btn-primary" style="margin: 5px; height: 25px; font-size: 10px;" @click="item.id ? editActual(item, key) : editFromList(item, key)">Edit</button>
                                    <button class="btn btn-danger" style="margin: 5px; height: 25px; font-size: 10px;" @click="item.id ? deleteActual(item) : deleteFromList(key)">Delete</button>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="color: black !important; margin-top: 0 !important; margin-bottom: 10px;" v-else>
                          <div style="border: 1px solid #c8c8c8; padding: 5px; font-size: 11px; text-align: center;">
                            No Damage Declared
                          </div>
                        </div>

                        <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document" style="">
                            <div class="modal-content">
                              <div class="modal-header" style="display: flex; align-items: center;">
                                <h5 class="modal-title" id="dialogLabel">@{{ isEdit === true ? 'Edit Damage' : 'Add Damage' }}</h5>
                                <button type="button" @click="closeDialog" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <hr style="margin: 0">
                              <div class="modal-body" style="padding-bottom: 0;">
                                <div class="col-lg-12 form-group mt-3">
                                  <autocomplete
                                    ref="autocompleteRepair"
                                    base-class="repair autocomplete"
                                    :search="searchRepair"
                                    :get-result-value="getResultRepair"
                                    @update="handleUpdateRepair"
                                    auto-select
                                    @submit="handleAutocompleteSubmitRepair"
                                  ></autocomplete>
                                  <label for="repair" class="form-control-placeholder"> Repair</label>
                                  <div class="customErrorText"><small>@{{ damageError.repair }}</small></div>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <autocomplete
                                    ref="autocompleteComponent"
                                    base-class="component autocomplete"
                                    :search="searchComponent"
                                    :get-result-value="getResultComponent"
                                    @update="handleUpdateComponent"
                                    auto-select
                                    @submit="handleAutocompleteSubmitComponent"
                                  ></autocomplete>
                                  <label for="component" class="form-control-placeholder"> Component</label>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <autocomplete
                                    ref="autocompleteDamage"
                                    base-class="damage autocomplete"
                                    :search="searchDamage"
                                    :get-result-value="getResultDamage"
                                    @update="handleUpdateDamage"
                                    auto-select
                                    @submit="handleAutocompleteSubmitDamage"
                                  ></autocomplete>
                                  <label for="damage" class="form-control-placeholder"> Damage</label>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <input type="text" name="location" id="location" class="form-control" v-model="damages.location" style="margin-top: 10px;">
                                  <label for="location" class="form-control-placeholder"> Location</label>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <input type="number" name="length" id="length" class="form-control" v-model="damages.length" style="margin-top: 10px;">
                                  <label for="length" class="form-control-placeholder"> Length</label>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <input type="number" name="width" id="width" class="form-control" v-model="damages.width" style="margin-top: 10px;">
                                  <label for="width" class="form-control-placeholder"> Width</label>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <input type="number" name="quantity" id="quantity" class="form-control" v-model="damages.quantity" style="margin-top: 10px;">
                                  <label for="quantity" class="form-control-placeholder"> Quantity</label>
                                </div>
                                <div class="col-lg-12 form-group mt-3">
                                  <input 
                                    type="text" 
                                    disabled 
                                    name="description" 
                                    id="description" 
                                    class="form-control" 
                                    style="margin-top: 10px;" 
                                    v-model="damages.description"
                                  >
                                  <label for="description" class="form-control-placeholder"> Description</label>
                                </div>
                              </div>
                              <div class="modal-footer" style="text-align: left !important; padding-top: 0;">
                                <button type="button" class="btn btn-primary" style="margin-top: 15px;" @click="clearDamage"> Clear</button>
                                <button type="button" class="btn btn-danger" style="margin-top: 15px;" @click="cancelDamage"> Cancel</button>
                                <button type="button" class="btn btn-primary" style="background-color: #2ecc71; margin-top: 15px;" :disabled="damageLoad === true" @click="isEdit === true ? updateDamage() : form.id ? addNewDamage() : checkDamage()">@{{ damageLoad === false ? isEdit === true ? 'Update' : 'Save' : 'Loading...' }}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                  <!--  -->

                        <div style="display: none;">@{{container_photo.length}}</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="margin: 0;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Pictures</div>
                        </div>
                        <div class="col-xs-12">
                          <input style="padding: 8px;" type="file" accept="image/*" class="form-control" :disabled="!isOk" id="images" name="images" @change="preview_images" multiple/>
                        </div>
                        <div class="col-xs-12" style="margin-bottom: 0;">
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

                  <div style="display: flex; justify-content: flex-end; padding-top: 0;" v-if="isOk === true">
                    <button style="width: 100px;" class="btn btn-primary save" :disabled="loading === true" @click="form.id ? upadteReceiving() : saveReceiving() ">@{{loading === false ? (form.id ? 'Update' : 'Save') : 'Loading...'}}</button>
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
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@trevoreyre/autocomplete-vue"></script>

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
          // $('.repair').find(':input').on( 'keydown', function( e ) {
          //   var enterkey = $.Event( "keyup", { keyCode: 13 } );
          //   if( e.which == 9 ) {
          //     $('.repair').find(':input').trigger(enterkey);
          //   }
          // });
      });
    </script>

    <!-- VUE -->
    <script type="text/javascript">
      Vue.component('v-select', VueSelect.VueSelect)

      var app = new Vue({
        el: '#containerReceiving',
        components: {
          vuejsDatepicker,
          Autocomplete
        },
        data: {
          form: {},
          container_photo: [],
          loginUser: `{!! Auth::user()->name !!}`,
          clientList: [],
          sizeTypeList: [],
          typeList: [],
          classList: [],
          yardList: [],
          images: [],
          choosenSize: {},
          choosenType: {},
          choosenClient: {},
          choosenYard: {},
          choosenClass: {},
          classSearch: '',
          sizeSearch: '',
          typeSearch: '',
          clientSearch: '',
          yardSearch: '',
          emptyloaded: [
            'Empty',
            'Loaded'
          ],
          errors: {},
          containerError: {},
          isOk: false,
          customload: false,
          damages: {},
          damageError: {},
          input: {},
          damageList: [],
          loading: false,
          pasmoDate: null,
          repairList: [],
          componentresults: [],
          selectedIndexComponent: -1,
          submittedComponent: false,
          repairresults: [],
          selectedIndexRepair: -1,
          submittedRepair: false,
          damageresults: [],
          selectedIndexDamage: -1,
          submittedDamage: false,
          isEdit: false,
          damageLoad: false
        },
        watch: {
          'damages': {
            handler () {
              this.pasmo()
            },
            deep: true
          },
          'form.manufactured_date': {
            handler () {
              if (this.form.manufactured_date !== null) {
                this.pasmoDate = this.form.manufactured_date
                this.$set(this.form, 'manufactured_date', this.pasmoDate)
              }
            },
            deep: true
          }
        },
        methods:{
          searchRepair(input) {
            this.submittedRepair = false
            return new Promise((resolve) => {
              if (input.length < 1) {
                return resolve([])
              }
              axios.get(`/admin/get/container/repair?keyword=${input}`)
                .then((data) => {
                  resolve(data.data)
                })
            })
          },
          getResultRepair(result) {
            return result.code
          },
          handleUpdateRepair(results, selectedIndex) {
            this.repairresults = results
            selectedIndexRepair = selectedIndex
          },
          handleAutocompleteSubmitRepair(result) {
            if (result !== undefined) {
              this.submittedRepair = true
            }
            this.handleSubmitRepair(result)
          },
          handleSubmitRepair(result) {
            this.$set(this.damages, 'repair', result)
            this.$set(this.damages, 'repair_id', result.id)
            this.pasmo()
          },
          searchComponent(input) {
            this.submittedComponent = false
            return new Promise((resolve) => {
              if (input.length < 1) {
                return resolve([])
              }
              axios.get(`/admin/get/container/component?keyword=${input}`)
                .then((data) => {
                  resolve(data.data)
                })
            })
          },
          getResultComponent(result) {
            return result.code
          },
          handleUpdateComponent(results, selectedIndex) {
            this.componentresults = results
            selectedIndexComponent = selectedIndex
          },
          handleAutocompleteSubmitComponent(result) {
            if (result !== undefined) {
              this.submittedComponent = true
            }
            this.handleSubmitComponent(result)
          },
          handleSubmitComponent(result) {
            this.$set(this.damages, 'component', result)
            this.$set(this.damages, 'component_id', result.id)
            this.pasmo()
          },
          searchDamage(input) {
            this.submittedDamage = false
            return new Promise((resolve) => {
              if (input.length < 1) {
                return resolve([])
              }
              axios.get(`/admin/get/container/damage?keyword=${input}`)
                .then((data) => {
                  resolve(data.data)
                })
            })
          },
          getResultDamage(result) {
            return result.code
          },
          handleUpdateDamage(results, selectedIndex) {
            this.damageresults = results
            selectedIndexDamage = selectedIndex
          },
          handleAutocompleteSubmitDamage(result) {
            if (result !== undefined) {
              this.submittedDamage = true
            }
            this.handleSubmitDamage(result)
          },
          handleSubmitDamage(result) {
            this.$set(this.damages, 'damage', result)
            this.$set(this.damages, 'damage_id', result.id)
            this.pasmo()
          },
          editActual (payload, key) {
            this.isEdit = true
            this.damages = payload
            this.$set(this.damages, 'key', key)
            this.$refs.autocompleteRepair.value = payload.repair.code
            this.$refs.autocompleteComponent.value = payload.component.code
            this.$refs.autocompleteDamage.value = payload.damage.code
            $('#dialog').modal({backdrop: 'static', keyboard: false});
          },
          editFromList (payload, key) {
            this.isEdit = true
            this.damages = payload
            this.$set(this.damages, 'key', key)
            this.$refs.autocompleteRepair.value = payload.repair.code
            this.$refs.autocompleteComponent.value = payload.component.code
            this.$refs.autocompleteDamage.value = payload.damage.code
            $('#dialog').modal({backdrop: 'static', keyboard: false});
          },
          deleteFromList (payload) {
            Vue.delete(this.damageList, parseInt(payload))
            Swal.fire({
              title: '',
              text: 'Deleted succesfully!',
              icon: 'success',
            })
          },
          async deleteActual (payload) {
            await axios.delete(`/admin/delete/damage/${payload.id}`).then(data => {
              let index = _.findIndex(this.damageList, { id: payload.id })
              Vue.delete(this.damageList, parseInt(index))
              Swal.fire({
                title: '',
                text: 'Deleted succesfully!',
                icon: 'success',
              })
            })
          },
          clearDamage () {
            this.damages = {}
            this.damageError = {}
            this.input = {}
            this.$refs.autocompleteRepair.value = ''
            this.$refs.autocompleteComponent.value = ''
            this.$refs.autocompleteDamage.value = ''
            this.repairList = []
            this.componentresults = []
            this.selectedIndexComponent = -1
            this.submittedComponent = false
            this.repairresults = []
            this.selectedIndexRepair = -1
            this.submittedRepair = false
            this.damageresults = []
            this.selectedIndexDamage = -1
            this.submittedDamage = false
            this.isEdit = false
          },
          cancelDamage () {
            $('#dialog').modal('hide');
          },
          pasmo () {
            this.damages.description = (this.damages.repair ? this.damages.repair.name : '') + ' ' + (this.damages.location ? `(${this.damages.location})` : '') + ' ' + (this.damages.damage ? this.damages.damage.name : '') + ' ' + (this.damages.component ? this.damages.component.name : '') + ' ' + (this.damages.quantity ? `(${this.damages.quantity})` : '') + ' ' + (this.damages.length ? `${this.damages.length}CM` : '') + '' + (this.damages.width ? `X${this.damages.width}CM` : '')
          },
          async checkDamage () {
            this.damageLoad = true
            await axios.post('/admin/check/damage', this.damages).then(data => {
              this.damageList.push(this.damages)
              this.damageLoad = false
              this.closeDialog()
            }).catch(error => {
              alert('Error')
            })
          },
          addNew () {
            $('#dialog').modal({backdrop: 'static', keyboard: false});
          },
          closeDialog () {
            this.clearDamage()
            $('#dialog').modal('hide');
          },
          dateFormat(date) {
            return moment(date).format('MM/yyyy');
          },
          photolink (payload) {
            return `background: url(${payload.storage_path})`
          },
          async getSize () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/get/container/size_type?keyword=${search.keyword}`, search).then( data => {
              this.sizeTypeList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          async getType () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/get/type?keyword=${search.keyword}`, search).then( data => {
              this.typeList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          async getClass () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/get/container/classes?keyword=${search.keyword}`, search).then( data => {
              this.classList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          async getClient () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/get/clients?keyword=${search.keyword}`, search).then( data => {
              this.clientList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          async getYard () {
            let search = {
              keyword: ''
            }
            await axios.get(`/admin/get/yards?keyword=${search.keyword}`, search).then( data => {
              this.yardList = data.data
            }).catch(error => {
              console.log('error: ', error)
            })
          },
          searchContainer () {
            if (this.form.container_no.length === 13) {
              clearTimeout(this.timer)
              this.timer = setTimeout(() => {
                const payload = {
                  type: 'receiving',
                  container_no: this.form.container_no
                }
                axios.get(`/admin/get/receiving/details?container_no=${payload.container_no}&type=receiving`)
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
            } else {
              this.isOk = false
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
          updateDamage () {
            this.damageLoad = true
            if (this.damages.id) {
              axios.post(`/admin/update/damage`, this.damages).then(data => { 
                this.getDamages()
                this.closeDialog()
                this.damageLoad = false
              })
            } else {
              Vue.set(this.damageList, this.damages.key, this.damages)
              this.closeDialog()
              this.damageLoad = false
            }
          },
          addNewDamage () {
            this.damageLoad = true
            this.$set(this.damages, 'receiving_id', this.form.id)
            axios.post(`/admin/create/damage`, this.damages).then(data => {
              this.getDamages()
              this.closeDialog()
              this.damageLoad = false
            })
          },
          async saveReceiving () {
            this.loading = true
            let currentUrl = window.location.href
            let checkedit = currentUrl.split('/create')[currentUrl.split('/create').length -2]
            this.$set(this.form, 'container_no', this.form.container_no.toUpperCase())
            this.$set(this.form, 'manufactured_date', this.pasmoDate)
            await axios.post('/admin/create/receiving', this.form).then(async data => {
              this.loading = false
              this.errors = {}
              for (let i = 0; i < this.damageList.length; i++) {
                this.$set(this.damageList[i], 'receiving_id', (+data.data[0].container_id))
                axios.post(`/admin/create/damage`, this.damageList[i]).then(data2 => {
                })
              }
              let customId = data.data[0].container_id
              await axios.get(`/admin/get/print/receiving/${customId}`).then(data => {
                let pasmo = data.data
                let w = window.open('', '_blank');
                w.document.write(pasmo);
                setTimeout(() => { 
                  w.print();
                  w.close();
                  let customUrl = `${window.location.origin}/admin/container-inquiry/${this.form.container_no}`
                  window.location = customUrl
                }, 100);
              })
              
            }).catch(error => {
              this.loading = false
              this.errors = error.response.data.errors
            })
          },
          async upadteReceiving () {
            this.loading = true
            this.form.inspected_by = this.form.inspected_by.id
            await axios.post('/admin/update/receiving', this.form).then(async data => {
              this.loading = false
              this.errors = {}
              let customUrl = `${window.location.origin}/admin/container-inquiry/${this.form.container_no}`
              window.location = customUrl
            }).catch(error => {
              this.loading = false
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
              await axios.get(`/admin/get/receiving/byId/${payload.id}`).then(data => {
                this.form = data.data
                this.sizeSearch = data.data.size_type.code
                this.classSearch = data.data.container_class.class_code
                this.yardSearch = data.data.yard_location.name
                this.clientSearch = data.data.client.code_name
                this.form.size_type = data.data.size_type.id
                this.form.type_id = data.data.type_id
                this.form.client_id = data.data.client.id
                this.form.yard_location = data.data.yard_location.id
                this.form.class = data.data.container_class.id
                this.form.inspected_by = data.data.inspector
                for (let index of Object.keys(data.data.photos)) {
                  let wawex = {
                    storage_path: data.data.photos[index].encoded[0]
                  }
                  this.container_photo.push(wawex)
                }
                this.form.container_photo = this.container_photo
                this.isOk = true
                this.getDamages()
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
          },
          async getDamages () {
            await axios.get(`/admin/get/damage/${this.form.id}`).then(data => {
              this.damageList = data.data
            })
          },
          testing(x) {
            console.log(x)
          }
        },
        mounted () {
          this.getdata()
          this.getSize()
          this.getType()
          this.getClient()
          this.getYard()
          this.getClass()
        }
      })
    </script>
    <script>
      {{-- vanilla js because i am a god hah --}}
      
      
      // function used for testing to add new components

      /*function test() {
        const dropdowns = document.querySelector("div#vs1__combobox > div.vs__selected-options")

        if (!dropdowns.children[1]) {
          var spanElement = document.createElement('span')
          spanElement.innerHTML = 'AAAAAAAAAA'
          spanElement.classList = ['vs__selected']
          dropdowns.prepend( spanElement )
        }
      }

      function test2() {
        const a = document.querySelectorAll(`div#vs1__combobox > div.vs__selected-options > span.vs__selected`)
        for (item of a) {
          item.remove()
        }
      }*/

      function setDropdownListeners() {
        const dropdowns = document.querySelectorAll("input.vs__search")
        for(let item of dropdowns) {
          const aria_control = item.attributes['aria-controls'].nodeValue

          // observes attribute change on dropdown
          const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
              if (mutation.type == "attributes") {
                const classList = document.getElementById(aria_control).classList

                const duplicates = document.querySelectorAll(`div[aria-owns='${aria_control}'] > div.vs__selected-options > span.vs__selected`)
                if(duplicates.length > 1) {
                  for (index in duplicates) {
                    const i = Number(index)
                    if (i < duplicates.length - 1) {
                      duplicates[i].remove()
                    }
                  }
                }

                if (classList.value) {
                  const focusedDropdownItem = document.querySelector(`input.vs__search[aria-controls="${aria_control}"]`).attributes['aria-activedescendant']

                  if (focusedDropdownItem) {
                    const focusedDropdownItemIndex = focusedDropdownItem.nodeValue.split('__option-')[1]
                    const displayElement = document.querySelector(`div[aria-owns='${aria_control}'] > div.vs__selected-options`)
                    const selectedDisplayElement = document.querySelector(`div[aria-owns='${aria_control}'] > div.vs__selected-options > span.vs__selected`)
                    const a = document.getElementById(aria_control).children[focusedDropdownItemIndex]

                    if (selectedDisplayElement) { // if has active selection
                      if (a) {
                        displayElement.children[0].innerHTML = a.innerHTML
                      }
                    }
                    else {
                      for(let item of displayElement.children) {
                        if (!displayElement.children[1]) {

                          setTimeout(() => {
                            var spanElement = document.createElement('span')
                            spanElement.innerHTML = document.getElementById(aria_control).children[0].innerHTML
                            spanElement.classList = ['vs__selected']
                            displayElement.prepend( spanElement )
                          }, 200)
                        }
                      }
                    }
                    // show clear button
                    const removeButton = document.querySelector(`div[aria-owns='${aria_control}'] > div.vs__actions > button.vs__clear`)
                    removeButton.style.removeProperty('display')
                    removeButton.addEventListener('click', onRemoveClick)
                  }
                }
              }
            })
          })
          observer.observe(item, {attributes: true})
        }
      }

      function onRemoveClick(x) {
        const selection = x.path[4].children[0].children
        for (item of selection) {
          if (item.classList.contains("vs__selected")) {
            item.remove()
          }
        }

        // hide close button
        const actions = x.path[4].children[1].children
        for (item of actions) {
          if (item.classList.contains("vs__clear")) {
            item.style.display = "none"
          }
        }
      }

      setDropdownListeners()
    </script>
    <!--  -->
@stop
