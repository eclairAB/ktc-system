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
                    <div class="panel-body" style="padding: 15px 15px 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="container_no" id="container_no" maxlength="11" v-model="form.container_no" @input="searchContainer()" :class="containerError.message ? 'isError form-control' : 'form-control'" style="height: 37px;">
                          <label for="container_no" class="form-control-placeholder"> Container No. <span style="color: red"> *</span></label>
                          <div class="customErrorText"><small>@{{ containerError.message }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            :class="errors.size_type ? 'isError form-control' : 'form-control'"
                            :options="sizeTypeList"
                            style="height: 37px !important;"
                            v-model="choosenSize"
                            :disabled="!isOk"
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
                                style="color: black;"
                                @input="searchSize()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.code}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.code}}
                            </template>
                          </v-select>
                          <label for="lastname" class="form-control-placeholder"> Size Type</label>
                          <div class="customErrorText"><small>@{{ errors.size_type ? errors.size_type[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            style="height: 37px !important;"
                            :class="errors.client_id ? 'isError form-control' : 'form-control'"
                            :options="clientList"
                            v-model="form.client_id"
                            :disabled="!isOk"
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
                                style="color: black;"
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
                          <div class="customErrorText"><small>@{{ errors.client_id ? errors.client_id[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            style="height: 37px !important;"
                            :class="errors.yard_location ? 'isError form-control' : 'form-control'"
                            :options="yardList"
                            :disabled="!isOk"
                            v-model="form.yard_location"
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
                                style="color: black;"
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
                          <label for="yard_location" class="form-control-placeholder"> Yard Location</label>
                          <div class="customErrorText"><small>@{{ errors.yard_location ? errors.yard_location[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="type" id="type" disabled v-model="form.type" style="height: 37px;" :class="errors.type ? 'isError form-control' : 'form-control'">
                          <label for="type" class="form-control-placeholder"> Type</label>
                          <div class="customErrorText"><small>@{{ errors.type ? errors.type[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            style="height: 37px !important;"
                            :class="errors.class ? 'isError form-control' : 'form-control'"
                            :options="classList"
                            v-model="form.class"
                            :disabled="!isOk"
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
                                style="color: black;"
                                v-model="classSearch"
                                @input="searchClass()"
                              />
                            </template>
                            <template slot="selected-option" slot-scope="option">
                              <span>@{{option.class_code}}</span>
                            </template>
                            <template slot="option" slot-scope="option">
                                @{{option.class_code}}
                            </template>
                          </v-select>
                          <label for="contact_number" class="form-control-placeholder"> Class</label>
                          <div class="customErrorText"><small>@{{ errors.class ? errors.class[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <v-select
                            style="height: 37px !important;"
                            :class="errors.empty_loaded ? 'isError form-control' : 'form-control'"
                            :disabled="!isOk"
                            :options="emptyloaded"
                            v-model="form.empty_loaded"
                          ></v-select>
                          <label for="empty_loaded" class="form-control-placeholder"> Empty/Loaded</label>
                          <div class="customErrorText"><small>@{{ errors.empty_loaded ? errors.empty_loaded[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <vuejs-datepicker
                            v-model="form.manufactured_date"
                            placeholder="mm/dd/yyyyy"
                            :disabled="!isOk"
                            :input-class="errors.manufactured_date ? 'isError form-control' : 'form-control'"
                            :typeable="true"
                            name="manufactured_date"
                            :format="dateFormat"
                            :required="true">
                          </vuejs-datepicker>
                          <label for="manufactured_date" class="form-control-placeholder"> Manufactured Date</label>
                          <div class="customErrorText"><small>@{{ errors.manufactured_date ? errors.manufactured_date[0] : '' }}</small></div>
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
                          <input type="text" name="consignee" id="consignee" v-model="form.consignee" :disabled="!isOk" style="height: 37px;" :class="errors.consignee ? 'isError form-control' : 'form-control'">
                          <label for="consignee" class="form-control-placeholder"> Consignee</label>
                          <div class="customErrorText"><small>@{{ errors.consignee ? errors.consignee[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="hauler" id="hauler" v-model="form.hauler" :disabled="!isOk" style="height: 37px;" :class="errors.hauler ? 'isError form-control' : 'form-control'">
                          <label for="hauler" class="form-control-placeholder"> Hauler</label>
                          <div class="customErrorText"><small>@{{ errors.hauler ? errors.hauler[0] : '' }}</small></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
                          <input type="text" name="plate_no" id="plate_no" v-model="form.plate_no" :disabled="!isOk" style="height: 37px;" :class="errors.plate_no ? 'isError form-control' : 'form-control'">
                          <label for="plate_no" class="form-control-placeholder"> Plate No.</label>
                          <div class="customErrorText"><small>@{{ errors.plate_no ? errors.plate_no[0] : '' }}</small></div>
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
                          <textarea v-model="form.remarks" rows="3" style="width: 100%; height: auto !important;" :disabled="!isOk" placeholder="Write Something..." :class="errors.remarks ? 'isError form-control' : 'form-control'"></textarea>  
                          <label for="consignee" class="form-control-placeholder"> Remarks</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="padding: 0 !important; margin: 0 !important;">
                          <div style="display: flex; justify-content: flex-end; padding-top: 0;">
                            <button class="btn btn-success" @click="addNew"> Add Damage</button>
                          </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="margin-top: 0 !important; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Damages</div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="color: black !important; margin-top: 0 !important; margin-bottom: 10px;">
                          <table border="1" cellspacing="0" cellpadding="" width="100%">
                            <tbody align="left">
                              <tr  v-for="(item, key) in damageList" :key="key">
                                <td class="border-b" style="padding: 10px">@{{key + 1}}.) @{{item.description}}</td>
                                <td>
                                  <div style="display: flex; justify-content: center; width: 100%">
                                    <button class="btn btn-danger" style="margin: 5px;" @click="item.id ? deleteActual(item) : deleteFromList(key)">Delete</button>  
                                  </div>                                
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
                    <div class="modal-success-dialog modal-dialog" role="document" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                      <div class="modal-content">
                        <div class="modal-header" style="display: flex; align-items: center;">
                          <h5 class="modal-title" id="dialogLabel">Add Damage</h5>
                          <button type="button" @click="closeDialog" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <hr style="margin: 0">
                        <div class="modal-body">
                          <div class="col-lg-12 form-group mt-3" style="padding-bottom: 15px;">
                            <input type="text" name="component" id="component" :class="damageError.component ? 'isError form-control' : 'form-control'" v-model="input.component" style="height: 37px !important; margin-top: 10px;" @input="inputComponent">
                            <label for="component" class="form-control-placeholder"> Component</label>
                            <div class="customErrorText"><small>@{{ damageError.component }}</small></div>
                          </div>
                          <div class="col-lg-12 form-group mt-3" style="padding-bottom: 15px;">
                            <input type="text" name="damage" id="damage" :class="damageError.damage ? 'isError form-control' : 'form-control'" v-model="input.damage" style="height: 37px !important; margin-top: 10px;" @input="inputDamage">
                            <label for="damage" class="form-control-placeholder"> Damage</label>
                            <div class="customErrorText"><small>@{{ damageError.damage }}</small></div>
                          </div>
                          <div class="col-lg-12 form-group mt-3" style="padding-bottom: 15px;">
                            <input type="text" name="repair" id="repair" :class="damageError.repair ? 'isError form-control' : 'form-control'" v-model="input.repair" style="height: 37px !important; margin-top: 10px;" @input="inputRepair">
                            <label for="repair" class="form-control-placeholder"> Repair</label>
                            <div class="customErrorText"><small>@{{ damageError.repair }}</small></div>
                          </div>
                          <div class="col-lg-12 form-group mt-3">
                            <input type="text" name="location" id="location" class="form-control" v-model="damages.location" style="height: 37px !important; margin-top: 10px;">
                            <label for="location" class="form-control-placeholder"> Location</label>
                          </div>
                          <div class="col-lg-12 form-group mt-3">
                            <input type="number" name="length" id="length" class="form-control" v-model="damages.length" style="height: 37px !important; margin-top: 10px;">
                            <label for="length" class="form-control-placeholder"> Length</label>
                          </div>
                          <div class="col-lg-12 form-group mt-3">
                            <input type="number" name="width" id="width" class="form-control" v-model="damages.width" style="height: 37px !important; margin-top: 10px;">
                            <label for="width" class="form-control-placeholder"> Width</label>
                          </div>
                          <div class="col-lg-12 form-group mt-3">
                            <input type="number" name="quantity" id="quantity" class="form-control" v-model="damages.quantity" style="height: 37px !important; margin-top: 10px;">
                            <label for="quantity" class="form-control-placeholder"> Quantity</label>
                          </div>
                          <div class="col-lg-12 form-group mt-3">
                            <input 
                              type="text" 
                              disabled 
                              name="description" 
                              id="description" 
                              class="form-control" 
                              style="height: 37px !important; margin-top: 10px;" 
                              v-model="damages.description"
                            >
                            <label for="description" class="form-control-placeholder"> Description</label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary" style="background-color: #2ecc71; margin-top: 15px;" @click="checkDamage"> Save</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <div style="display: none;">@{{container_photo.length}}</div>
                  <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-xs-12" style="border-bottom: 1px solid #e4eaec; padding-bottom: 10px; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Pictures</div>
                        </div>
                        <div class="col-xs-12">
                          <input style="padding: 8px;" type="file" class="form-control" :disabled="!isOk" id="images" name="images" @change="preview_images" multiple/>
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

                  <div class="panel panel-bordered" v-if="form.id">
                    <div class="panel-body" style="padding: 15px;">
                      <div class="row" style="padding: 0px 10px;">
                        <div class="col-xs-12" style="border-bottom: 1px solid #e4eaec; padding-bottom: 10px; margin-bottom: 10px;">
                          <div style="font-weight: 700; font-size: 15px; color: black;">Signature</div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                          <div style="font-weight: 700;">Signature</div>
                          <div class="image-container" :style="photolink(signature)" style="margin-top: 10px;border: 4px solid #e5e7eb; border-radius: 10px;"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="panel panel-bordered" id="signCard">
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
                  <div id="saveBtn">
                    <button style="width: 100px;" id="save" class="btn btn-primary save">Save</button>
                  </div>
                  <div id="updateBtn">
                    <button style="width: 100px;" id="update" class="btn btn-primary save">Update</button>
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
    
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@3.0.0-beta.4/dist/signature_pad.umd.min.js"></script>

    <script>
        var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
          backgroundColor: 'rgba(255, 255, 255, 0)',
          penColor: 'rgb(0, 0, 0)'
        });
        var saveButton = document.getElementById('save');
        var updateButton = document.getElementById('update');
        var cancelButton = document.getElementById('clear');

        saveButton.addEventListener('click', function (event) {
          if(!event.detail || event.detail == 1){ 
            document.getElementById('containerReceiving').__vue__.saveReceiving(signaturePad.toDataURL('image/png'))
          }
        });

        updateButton.addEventListener('click', function (event) {
          if(!event.detail || event.detail == 1){ 
            document.getElementById('containerReceiving').__vue__.upadteReceiving()
          }
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
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- VUE -->
    <script type="text/javascript">
      Vue.component('v-select', VueSelect.VueSelect)

      var app = new Vue({
        el: '#containerReceiving',
        components: {
          vuejsDatepicker,
        },
        data: {
          signature: {},
          form: {},
          container_photo: [],
          loginUser: `{!! Auth::user()->name !!}`,
          clientList: [],
          sizeTypeList: [],
          classList: [],
          yardList: [],
          images: [],
          choosenSize: {},
          classSearch: '',
          sizeSearch: '',
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
          damageList: []
        },
        watch: {
          'damages': {
            handler () {
              this.pasmo()
            },
            deep: true
          }
        },
        methods:{
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
          pasmo () {
            this.damages.description = ((this.damages.component ? this.damages.component : '') + ' ' + (this.damages.damage ? this.damages.damage : '') + ' ' + (this.damages.repair ? this.damages.repair : '') + ' ' + (this.damages.location ? this.damages.location : '') + ' ' + (this.damages.width ? this.damages.width : '') + ' ' + (this.damages.length ? this.damages.length : '') + ' ' + (this.damages.quantity ? this.damages.quantity : ''))
          },
          inputComponent () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.input.component
              }
              axios.get(`/admin/get/container/component?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.damageError = {}
                if (data.data[0] !== undefined) {
                  this.damages.component = data.data[0].name
                  this.damages.component_id = data.data[0].id
                  this.pasmo()
                } else {
                  delete this.damages.component
                  this.damageError.component = 'This component code is undefined.'
                }
              })
            }, 1000)
          },
          inputDamage () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.input.damage
              }
              axios.get(`/admin/get/container/damage?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.damageError = {}
                if (data.data[0] !== undefined) {
                  this.damages.damage = data.data[0].name
                  this.damages.damage_id = data.data[0].id
                  this.pasmo()
                } else {
                  this.damages = {}
                  this.damageError.damage = 'This damage code is undefined.'
                }
              })
            }, 1000)
          },
          inputRepair () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.input.repair
              }
              axios.get(`/admin/get/container/repair?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.damageError = {}
                if (data.data[0] !== undefined) {
                  this.damages.repair = data.data[0].name
                  this.damages.repair_id = data.data[0].id
                  this.pasmo()
                } else {
                  this.damages = {}
                  this.damageError.repair = 'This repair code is undefined.'
                }
              })
            }, 1000)
          },
          async checkDamage () {
            await axios.post('/admin/check/damage', this.damages).then(data => {
              this.damageList.push(this.damages)
              this.closeDialog()
            }).catch(error => {
              alert('Error')
            })
          },
          addNew () {
            $('#dialog').modal({backdrop: 'static', keyboard: false});
          },
          closeDialog () {
            this.damages = {}
            this.damageError = {}
            this.input = {}
            $('#dialog').modal('hide');
          },
          dateFormat(date) {
            return moment(date).format('MM/DD/yyyy');
          },
          photolink (payload) {
            return `background: url(${payload.storage_path})`
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
              axios.get(`/admin/get/container/size_type?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.sizeTypeList = data.data
              })
            }, 1000)
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
              axios.get(`/admin/get/container/classes?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.classList = data.data
              })
            }, 1000)
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
              axios.get(`/admin/get/clients?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.clientList = data.data
              })
            }, 1000)
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
          clearYard () {
            this.yardSearch = ''
          },
          searchYard () {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                keyword: this.yardSearch
              }
              axios.get(`/admin/get/yards?keyword=${payload.keyword}`, payload)
              .then(data => {
                this.yardList = data.data
              })
            }, 1000)
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
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
              const payload = {
                type: 'receiving',
                container_no: this.form.container_no
              }
              axios.get(`/admin/get/receiving/details?container_no=${payload.container_no}&type=receiving`)
              .then(data => {
                if (!this.form.id) {
                  document.getElementById("signCard").style.display = 'inherit'; 
                  document.getElementById("saveBtn").style.display = 'inherit';
                } else {
                  document.getElementById("updateBtn").style.display = 'inherit'; 
                }
                this.isOk = true
                this.containerError = {}
                this.containerInfo = data.data
              }).catch(error => {
                this.isOk = false
                document.getElementById("signCard").style.display = 'none';
                document.getElementById("saveBtn").style.display = 'none';
                document.getElementById("updateBtn").style.display = 'none'; 
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
          async saveReceiving (data) {
            $('#save').attr('disabled', 'disabled');
            let currentUrl = window.location.href
            let checkedit = currentUrl.split('/create')[currentUrl.split('/create').length -2]
            this.form.signature = data
            await axios.post('/admin/create/receiving', this.form).then(async data => {
              document.getElementById("save").removeAttribute("disabled");
              this.errors = {}
              for (let i = 0; i < this.damageList.length; i++) {
                this.$set(this.damageList[i], 'receiving_id', (+data.data[0].container_id))
                axios.post(`/admin/create/damage`, this.damageList[i]).then(data2 => {
                  // console.log(i)
                })
              }
              let customId = data.data[0].container_id
              await axios.get(`/admin/get/print/receiving/${customId}`).then(data => {
                let pasmo = data.data
                let w = window.open();
                w.document.write(pasmo);
                w.print();
                w.close();
              })
              window.location = checkedit
            }).catch(error => {
              document.getElementById("save").removeAttribute("disabled");
              this.errors = error.response.data.errors
            })
          },
          async upadteReceiving () {
            $('#supdate').attr('disabled', 'disabled');
            this.form.inspected_by = this.form.inspected_by.id
            delete this.form.signature
            await axios.post('/admin/update/receiving', this.form).then(async data => {
              document.getElementById("update").removeAttribute("disabled");
              this.errors = {}
              let customId = data.data.id
              await axios.get(`/admin/get/print/receiving/${customId}`).then(data => {
                let pasmo = data.data
                let w = window.open();
                w.document.write(pasmo);
                w.print();
                w.close();
              })
              window.location = checkedit
            }).catch(error => {
              document.getElementById("update").removeAttribute("disabled");
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
              await axios.get(`/admin/get/receiving/byId/${payload.id}`).then(data => {
                this.form = data.data
                this.sizeSearch = data.data.size_type.code
                this.form.size_type = data.data.size_type.id
                this.form.clien_id = data.data.client.id
                this.form.yard_location = data.data.yard_location.id
                this.form.class = data.data.class.id
                this.signature.storage_path = data.data.signature[0]
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
          }
        },
        mounted () {
          document.getElementById("signCard").style.display = 'none';
          document.getElementById("saveBtn").style.display = 'none';
          document.getElementById("updateBtn").style.display = 'none';
          this.getdata()
          this.getSize()
          this.getClient()
          this.getYard()
          this.getClass()
        }
      })
    </script>
    <!--  -->
@stop
