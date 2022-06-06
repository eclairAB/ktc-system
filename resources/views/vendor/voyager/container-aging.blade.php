@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@3.16.0/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <style type="text/css">
      .form-control {
        color: black !important;
      }
      .form-error {
        border: 1px solid #ff0000;
      }
    </style>
@stop

@section('content')
<body>
  <div id="containerIn">

    <div class="panel panel-default" style="margin-top: 15px;">
      
      <div class="panel-body" style="background-color: #fff; border: 0;">
        <div class="row">
          <div class="col-xs-12" style="margin-bottom: 0;">
            <div class="row" style="margin: 0; width: 100%;">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin: 0;">
                <span style="font-weight: bold; font-size: 18px;">Container Aging and Inventory</span>
              </div>
              <div id="wawex" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin: 0;">
                <div>
                  <button class="btn btn-primary" :disabled="generateLoad" @click="getContainerAging">@{{ generateLoad === false ? 'Generate' : 'Loading...' }}</button>
                  <button class="btn btn-success" :disabled="exportLoad" @click="exportContainerIn">@{{ exportLoad === false ? 'Export to Excel' : 'Loading...' }}</button>
                  <button class="btn btn-danger" @click="printContainerAging">@{{ exportLoad === false ? 'Print' : 'Loading...' }}</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-12" style="margin-bottom: 10px;">
            <hr style="margin: 5px 0;">
          </div>
          <div class="col-xs-12" style="margin: 0;">
            <div class="row" style="padding: 0 15px;">
              
              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  style="height: 37px !important;"
                  :options="['ALL','IN','OUT']"
                  v-model="form.option"
                  :clearable=false
                ></v-select>
                <label for="option" class="form-control-placeholder"> Container Record</label>
              </div>

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <vuejs-datepicker
                  v-model="form.date_in_from"
                  placeholder="mm/dd/yyyyy"
                  :input-class="generateErrorList.date_in_from ? 'form-control form-error' : 'form-control'"
                  :disabled="inDate"
                  :typeable="true"
                  name="from"
                  :format="dateFormat"
                  :required="true"
                  @input="getClient">
                </vuejs-datepicker>
                <label for="from" class="form-control-placeholder"> Container In Date From</label>
                <div class="customErrorText"><small>@{{ generateErrorList.date_in_from ? generateErrorList.date_in_from[0] : '' }}</small></div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <vuejs-datepicker
                  v-model="form.date_in_to"
                  placeholder="mm/dd/yyyyy"
                  :input-class="generateErrorList.date_in_to ? 'form-control form-error' : 'form-control'"
                  :typeable="true"
                  :disabled="inDate"
                  name="to"
                  :format="dateFormat"
                  :required="true"
                  @input="getClient">
                </vuejs-datepicker>
                <label for="to" class="form-control-placeholder"> Container In Date To </label>
                <div class="customErrorText"><small>@{{ generateErrorList.date_in_to ? generateErrorList.date_in_to[0] : '' }}</small></div>
              </div>

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <vuejs-datepicker
                  v-model="form.date_out_from"
                  placeholder="mm/dd/yyyyy"
                  :input-class="generateErrorList.date_out_from ? 'form-control form-error' : 'form-control'"
                  :disabled="outDate"
                  :typeable="true"
                  name="from"
                  :format="dateFormat"
                  :required="true"
                  @input="getClient">
                </vuejs-datepicker>
                <label for="from" class="form-control-placeholder"> Container Out Date In </label>
                <div class="customErrorText"><small>@{{ generateErrorList.date_out_from ? generateErrorList.date_out_from[0] : '' }}</small></div>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <vuejs-datepicker
                  v-model="form.date_out_to"
                  placeholder="mm/dd/yyyyy"
                  :input-class="generateErrorList.date_out_to ? 'form-control form-error' : 'form-control'"
                  :disabled="outDate"
                  :typeable="true"
                  name="to"
                  :format="dateFormat"
                  :required="true"
                  @input="getClient">
                </vuejs-datepicker>
                <label for="to" class="form-control-placeholder"> Container Out Date To </label>
                <div class="customErrorText"><small>@{{ generateErrorList.date_out_to ? generateErrorList.date_out_to[0] : '' }}</small></div>
              </div>

              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0px;">
              </div>

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  :options="emptyLoadedList"
                  style="height: 37px !important;"
                  v-model="form.status"
                  class="form-control"
                  label="name"
                  :reduce="name => name.name"
                ></v-select>
                <label for="status" class="form-control-placeholder"> Status</label>
              </div>

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  style="height: 37px !important;"
                  :options="clientList"
                  v-model="form.client"
                  label="code"
                  class="form-control"
                  :reduce="code => code.id"
                ></v-select>
                <label for="client" class="form-control-placeholder"> Client </label>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  style="height: 37px !important;"
                  class="form-control"
                  :options="classList"
                  v-model="form.class"
                  label="class_code"
                  :reduce="class_code => class_code.id"
                ></v-select>
                <label for="contact_number" class="form-control-placeholder"> Class</label>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  :options="sizeTypeList"
                  style="height: 37px !important;"
                  v-model="form.sizeType"
                  class="form-control"
                  label="code"
                  :reduce="code => code.id"
                ></v-select>
                <label for="sizeType" class="form-control-placeholder"> Size</label>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  :class="errors.type ? 'isError form-control' : 'form-control'"
                  :options="typeList"
                  style="height: 37px !important;"
                  v-model="form.type"
                  label="code"
                  :reduce="code => code.id"
                ></v-select>
                <label for="type" class="form-control-placeholder"> Type </label>
                <div class="customErrorText"><small>@{{ errors.size_type ? errors.size_type[0] : '' }}</small></div>
              </div>

            </div>
          </div>
        </div>
      </div>
    
    </div>

    <!-- <div class="panel panel-default" style="margin-top: 15px;"> -->
    <div class="panel panel-default">
      <div class="panel-body">
        <!-- <div style="color: black; font-weight: bold; text-align: center; margin-bottom: 10px;">
            <img src = "{{ asset('/images/kudos.png') }}" width="150px" /><br>
            <span>Container Aging and Inventory</span>
        </div> -->
        <div style="overflow: auto; max-height: 500px;">
          <table class="table table-bordered" style="margin-bottom: 0; color: black;">
            <thead>
              <tr>
                <th @click="customSort('container_no')" scope="col" style="white-space: nowrap; cursor: pointer;">Container No.</th>
                <th @click="customSort('size_type')" scope="col" style="white-space: nowrap; cursor: pointer;">Size</th>
                <th @click="customSort('type')" scope="col" style="white-space: nowrap; cursor: pointer;">Type</th>
                <th @click="customSort('status')" scope="col" style="white-space: nowrap; cursor: pointer;">Status</th>
                <th @click="customSort('client')" scope="col" style="white-space: nowrap; cursor: pointer;">Client</th>
                <th @click="customSort('container_class')" scope="col" style="white-space: nowrap; cursor: pointer;">Class</th>
                <th @click="customSort('receiving_inspected_date')" scope="col" style="white-space: nowrap; cursor: pointer;">Date In</th>
                <th @click="customSort('receiving_consignee')" scope="col" style="white-space: nowrap; cursor: pointer;">Consignee</th>
                <th scope="col" style="white-space: nowrap">Damages</th>
                <th @click="customSort('remarks')" scope="col" style="white-space: nowrap; cursor: pointer;">Remarks</th>
                <th @click="customSort('releasing_inspected_date')" scope="col" style="white-space: nowrap; cursor: pointer;">Date Out</th>
                <th @click="customSort('releasing_consignee')" scope="col" style="white-space: nowrap; cursor: pointer;">Consignee</th>
                <th @click="customSort('booking_no')" scope="col" style="white-space: nowrap; cursor: pointer;">Booking</th>
                <th @click="customSort('seal_no')" scope="col" style="white-space: nowrap; cursor: pointer;">Seal</th>
                <th @click="customSort('total_no_days')" scope="col" style="white-space: nowrap; cursor: pointer;">Days</th>
              </tr>
            </thead>
            <tbody v-if="containerAgingList.length > 0">
              <tr v-for="(item, index) in containerAgingList" :key="index">
                <td style="white-space: nowrap" class="viewItemOnClick"
                v-on:click="reroute(item.releasing_id,item.receiving_id)">@{{ item.container_no }}</td>
                <td style="white-space: nowrap">@{{ item.size_type ? item.size_type.code : '' }}</td>
                <td style="white-space: nowrap">@{{ item.type ? item.type.code : '' }}</td>
                <td style="white-space: nowrap">@{{ item.status }}</td>
                <td style="white-space: nowrap">@{{ item.client ? item.client.code : '' }}</td>
                <td style="white-space: nowrap">@{{ item.container_class ? item.container_class.class_code : '' }}</td>
                <td style="white-space: nowrap"
                  class="viewItemOnClick"
                  v-on:click="rerouteReceiving(item.receiving_id)">@{{ item.receiving ? moment(item.receiving.inspected_date).format('YYYY-MM-DD') : '' }}
                </td>
                <td style="white-space: nowrap">@{{ item.receiving ? item.receiving.consignee : '' }}</td>
                <td style="white-space: nowrap">
                  <div v-for="(item,i) in item.receiving.damages" :key="i">
                    @{{ i + 1 }}.) @{{ item.description }}
                  </div>
                </td>
                <td style="white-space: nowrap">@{{ item.receiving ? item.receiving.remarks : '' }}</td>
                <td style="white-space: nowrap"
                  :class="item.releasing_id ? 'viewItemOnClick' : ''"
                  v-on:click="rerouteReleasing(item.releasing_id)">@{{ item.releasing ? moment(item.releasing.inspected_date).format('YYYY-MM-DD') : '' }}
                </td>
                <td style="white-space: nowrap">@{{ item.releasing ? item.releasing.consignee : '' }}</td>
                <td style="white-space: nowrap">@{{ item.releasing ? item.releasing.booking_no : '' }}</td>
                <td style="white-space: nowrap">@{{ item.releasing ? item.releasing.seal_no : '' }}</td>
                <td style="white-space: nowrap">@{{ item.total_no_days }}</td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="12" style="text-align: center;" v-if="tableLoad === true">
                  <div class="lds-facebook"><div></div><div></div><div></div></div><br>
                  <div>Fetching...</div>
                </td>
                <td colspan="12" style="text-align: center;" v-else>No Data Available</td>
              </tr>
            </tbody>
          </table>
          <div style="display: flex; margin-top: 10px;">
            <div v-if="containerAgingList.length > 0" style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
              Van Count: 
              <div style="margin-left: 10px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">@{{ van_total }}</div>
            </div>
            <div v-if="containerAgingList.length > 0" style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
              IN: 
              <div style="margin-left: 10px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">@{{ van_in }}</div>
            </div>
            <div v-if="containerAgingList.length > 0" style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
              OUT: 
              <div style="margin-left: 10px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">@{{ van_out }}</div>
            </div>
          </div>
        </div>
      </div>  
    </div>

  </div>
</body>

<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
  function checkWidth(init) {

    if ($(window).width() > 480) {
      document.getElementById("wawex").classList.add('text-right');
      $('input').addClass('text-right');
    } else {
      if (!init) {
        document.getElementById("wawex").classList.remove('text-right');
      }
    }
  }

  $(document).ready(function() {
    checkWidth(true);

    $(window).resize(function() {
      checkWidth(false);
    });
  });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/vue-select@3.16.0"></script>
<script src="https://cdn.jsdelivr.net/npm/fuse.js@6.4.6"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-date-dropdown@1.0.5/dist/vue-date-dropdown.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuejs-datepicker@1.6.2/dist/vuejs-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

<script type="text/javascript">
  Vue.component('v-select', VueSelect.VueSelect)
  var app = new Vue({
    el: '#containerIn',
    components: {
      vuejsDatepicker,
    },
    data: {
      form: {
        option: 'ALL',
        param: 'container_no',
        order: 'ASC'
      },
      generateErrorList: {},
      errors: [],
      classList: [],
      clientList: [],
      sizeTypeList: [],
      typeList: [],
      containerAgingList: [],
      emptyLoadedList: [],
      loading: false,
      tableLoad: false,
      generateLoad: false,
      exportLoad: false,
      van_total: '',
      van_in: '',
      van_out: ''
    },
    computed: {
      inDate () {
        if(this.form.option === 'IN' ){
          return false
        }  else if (this.form.option === 'ALL'){
          return true
        } else {
          return true
        }
      },
      outDate () {
        if(this.form.option === 'OUT'){
          return false
        } else if (this.form.option === 'ALL'){
          return true
        } else {
          return true
        }
      },
    },
    methods: {
      customSort (data) {
        if (this.form.order === 'DESC') {
          this.$set(this.form, 'param', data)
          this.$set(this.form, 'order', 'ASC')
        } else {
          this.$set(this.form, 'param', data)
          this.$set(this.form, 'order', 'DESC')
        }
        this.getContainerAging()
      },
      rerouteReceiving(receiving_id) {
        let customUrl = `${window.location.origin}/admin/container-receivings/${receiving_id}/edit`
        window.location = customUrl
      },
      rerouteReleasing(releasing_id) {
        if(releasing_id) {
          let customUrl = `${window.location.origin}/admin/container-releasings/${releasing_id}/edit`
          window.location = customUrl
        }
      },
      reroute(releasing_id,receiving_id) {
        if(releasing_id)
        {
          let customUrl = `${window.location.origin}/admin/container-releasings/${releasing_id}/edit`
          window.location = customUrl
        }
        else{
          let customUrl = `${window.location.origin}/admin/container-receivings/${receiving_id}/edit`
          window.location = customUrl
        }
      },
      dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      async getContainerAging () {
        this.generateLoad = true
        let payload = {
          param: this.form.param,
          order: this.form.order,
          option: this.form.option === undefined || this.form.option === null ? 'ALL' : this.form.option,
          type: this.form.type === undefined || this.form.type === null ? 'NA' : this.form.type,
          sizeType: this.form.sizeType === undefined || this.form.sizeType === null ? 'NA' : this.form.sizeType,
          client: this.form.client === undefined || this.form.client === null ? 'NA' : this.form.client,
          class: this.form.class === undefined || this.form.class === null ? 'NA' : this.form.class,
          status: this.form.status === undefined || this.form.status === null ? 'NA' : this.form.status,
          date_in_from: this.form.date_in_from === undefined || this.form.date_in_from === null ? 'NA' : moment(this.form.date_in_from).format('YYYY-MM-DD'),
          date_in_to: this.form.date_in_to === undefined || this.form.date_in_to === null ? 'NA' : moment(this.form.date_in_to).format('YYYY-MM-DD'),
          date_out_from: this.form.date_out_from === undefined || this.form.date_out_from === null ? 'NA' : moment(this.form.date_out_from).format('YYYY-MM-DD'),
          date_out_to: this.form.date_out_to === undefined || this.form.date_out_to === null ? 'NA' : moment(this.form.date_out_to).format('YYYY-MM-DD')
        }
        await axios.post(`/admin/get/container/aging`, payload).then(data => {
          this.generateLoad = false
          this.containerAgingList = data.data.data
          this.van_total = data.data.van_count
          this.van_in = data.data.in
          this.van_out = data.data.out
          if (data.data.data.length === 0) {
            Swal.fire({
              title: '',
              text: 'No record found!',
              icon: 'error',
            })
          }
        }).catch(error => {
          this.generateErrorList = error.response.data.errors
          this.generateLoad = false
          console.log(error.response.data.errors)
        })
      },
      async exportContainerIn () {
        this.exportLoad = true
        let payload = {
          param: this.form.param,
          order: this.form.order,
          option: this.form.option === undefined || this.form.option === null ? 'ALL' : this.form.option,
          type: this.form.type === undefined || this.form.type === null ? 'NA' : this.form.type,
          sizeType: this.form.sizeType === undefined || this.form.sizeType === null ? 'NA' : this.form.sizeType,
          client: this.form.client === undefined || this.form.client === null ? 'NA' : this.form.client,
          class: this.form.class === undefined || this.form.class === null ? 'NA' : this.form.class,
          status: this.form.status === undefined || this.form.status === null ? 'NA' : this.form.status,
          date_in_from: this.form.date_in_from === undefined || this.form.date_in_from === null ? 'NA' : moment(this.form.date_in_from).format('YYYY-MM-DD'),
          date_in_to: this.form.date_in_to === undefined || this.form.date_in_to === null ? 'NA' : moment(this.form.date_in_to).format('YYYY-MM-DD'),
          date_out_from: this.form.date_out_from === undefined || this.form.date_out_from === null ? 'NA' : moment(this.form.date_out_from).format('YYYY-MM-DD'),
          date_out_to: this.form.date_out_to === undefined || this.form.date_out_to === null ? 'NA' : moment(this.form.date_out_to).format('YYYY-MM-DD')
        }
        await axios.get(`/excel/container_aging/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.date_in_from}/${payload.date_in_to}/${payload.date_out_from}/${payload.date_out_to}/${payload.option}/${payload.status}/${payload.param}/${payload.order}`).then(data => {
          this.exportLoad = false
          window.open(`${location.origin}/excel/container_aging/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.date_in_from}/${payload.date_in_to}/${payload.date_out_from}/${payload.date_out_to}/${payload.option}/${payload.status}/${payload.param}/${payload.order}`, "_blank");
        }).catch(error => {
          this.exportLoad = false
          console.log(error)
        })
      },
      async printContainerAging () {
        let payload = {
          param: this.form.param,
          order: this.form.order,
          option: this.form.option === undefined || this.form.option === null ? 'ALL' : this.form.option,
          type: this.form.type === undefined || this.form.type === null ? 'NA' : this.form.type,
          sizeType: this.form.sizeType === undefined || this.form.sizeType === null ? 'NA' : this.form.sizeType,
          client: this.form.client === undefined || this.form.client === null ? 'NA' : this.form.client,
          class: this.form.class === undefined || this.form.class === null ? 'NA' : this.form.class,
          status: this.form.status === undefined || this.form.status === null ? 'NA' : this.form.status,
          date_in_from: this.form.date_in_from === undefined || this.form.date_in_from === null ? 'NA' : moment(this.form.date_in_from).format('YYYY-MM-DD'),
          date_in_to: this.form.date_in_to === undefined || this.form.date_in_to === null ? 'NA' : moment(this.form.date_in_to).format('YYYY-MM-DD'),
          date_out_from: this.form.date_out_from === undefined || this.form.date_out_from === null ? 'NA' : moment(this.form.date_out_from).format('YYYY-MM-DD'),
          date_out_to: this.form.date_out_to === undefined || this.form.date_out_to === null ? 'NA' : moment(this.form.date_out_to).format('YYYY-MM-DD')
        }
        await axios.get(`/admin/get/print/aging/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.date_in_from}/${payload.date_in_to}/${payload.date_out_from}/${payload.date_out_to}/${payload.option}/${payload.status}/${payload.param}/${payload.order}`).then(data => {
          let pasmo = data.data
          let w = window.open(`/admin/get/print/aging/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.date_in_from}/${payload.date_in_to}/${payload.date_out_from}/${payload.date_out_to}/${payload.option}/${payload.status}/${payload.param}/${payload.order}`, '_blank');
          w.document.write(pasmo);
          setTimeout(() => { 
              w.print();
              w.close();
          }, 100);
        })
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
      async getEmptyLoaded () {
        await axios.get(`/admin/get/emptyloaded`).then(data => {
          this.emptyLoadedList = data.data
        })
      }
    },
    mounted () {
      this.getSize()
      this.getType()
      this.getClient()
      this.getClass()
      this.getEmptyLoaded()
    }
  })

</script>
@stop
