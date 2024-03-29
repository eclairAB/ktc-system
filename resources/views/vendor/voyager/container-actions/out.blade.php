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
      tbody td {
        vertical-align: middle !important;
      }
      .app-container {
        height: 100%;
      }
      .app-container .row.content-container {
        height: 100%;
      }
      .app-container .row.content-container .container-fluid {
        height: 100%;
      }
      .app-container .row.content-container .container-fluid .side-body.padding-top {
        height: 100%;
      }
      .app-container .row.content-container .container-fluid .side-body.padding-top #containerOut {
        height: 100%;
      }
      .app-container .row.content-container .container-fluid .side-body.padding-top #containerOut .panel.panel-default {
        height: fit-content;
        display: flex;
        flex-direction: column;
      }
      .app-container .row.content-container .container-fluid .side-body.padding-top #containerOut .panel.panel-default .panel-body:nth-child(2) {
        height:  fit-content;
        display: flex;
        flex-direction: column;
      }
      @media (max-height: 915px) {

        .app-container .row.content-container .container-fluid .side-body.padding-top #containerOut .panel.panel-default .panel-body:nth-child(2) {
          max-height: 415px;
        }
      }
    </style>
@stop

@section('content')
<body>
  <div id="containerOut">

    <div class="panel panel-default" style="margin-top: 15px;">
      <div class="panel-body" style="background-color: #fff; border: 0; padding-bottom: 0;">
        <div class="row">
          <div class="col-xs-12" style="margin-bottom: 0;">
            <div class="row" style="margin: 0; width: 100%;">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin: 0;">
                <span style="font-weight: bold; font-size: 18px;">Daily Container Out Report</span>
              </div>
              <div id="wawex" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin: 0;">
                <div>
                  <button class="btn btn-primary" :disabled="generateLoad" @click="getContainerOut">@{{ generateLoad === false ? 'Generate' : 'Loading...' }}</button>
                  <button class="btn btn-success" :disabled="exportLoad" @click="exportContainerOut">@{{ exportLoad === false ? 'Export to Excel' : 'Loading...' }}</button>
                  <button class="btn btn-danger" :disabled="printLoad" @click="printContainerOut">@{{ exportLoad === false ? 'Print' : 'Loading...' }}</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-12" style="margin-bottom: 0;">
            <hr style="margin: 5px 0;">
          </div>
          <div class="col-xs-12" style="margin: 0;">
            <div class="row" style="padding: 0 15px;">
              
              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <vuejs-datepicker
                  v-model="form.from"
                  placeholder="mm/dd/yyyyy"
                  input-class="form-control"
                  :typeable="true"
                  name="from"
                  :format="dateFormat"
                  :required="true"
                  @input="getClient">
                </vuejs-datepicker>
                <label for="from" class="form-control-placeholder"> Date Out</span></label>
              </div>

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <vuejs-datepicker
                  v-model="form.to"
                  placeholder="mm/dd/yyyyy"
                  input-class="form-control"
                  :typeable="true"
                  name="to"
                  :format="dateFormat"
                  :required="true"
                  @input="getClient">
                </vuejs-datepicker>
                <label for="to" class="form-control-placeholder"> Date To</span></label>
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
                <label for="client" class="form-control-placeholder"> Client</span></label>
              </div>

              <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  class="form-control"
                  :options="typeList"
                  style="height: 37px !important;"
                  v-model="form.type"
                  label="code"
                  :reduce="code => code.id"
                ></v-select>
                <label for="type" class="form-control-placeholder"> Type</label>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  class="form-control"
                  :options="emptyLoadedList"
                  style="height: 37px !important;"
                  v-model="form.status"
                  label="name"
                  :reduce="name => name.name"
                ></v-select>
                <label for="status" class="form-control-placeholder"> Status</label>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  :options="sizeTypeList"
                  style="height: 37px !important;"
                  v-model="form.sizeType"
                  class="form-control"
                  label="code"
                  :reduce="code => code.id"
                ></v-select>
                <label for="code" class="form-control-placeholder"> Size</label>
              </div>
              
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  style="height: 37px !important;"
                  class="form-control"
                  :options="classList"
                  v-model="form.class"
                  label="class_code"
                  :reduce="class_code => class_code.id"
                ></v-select>
                <label for="class" class="form-control-placeholder"> Class</span></label>
              </div>

            </div>
          </div>
        </div>
      </div>  

      <div class="panel-body" style="padding-top: 0;">
        <div style="color: black; font-weight: bold; text-align: center; margin-bottom: 10px;">
            <!-- <img src = "{{ asset('/images/kudos.png') }}" width="150px" /><br> -->
            <span>Container Daily Out Report</span>
        </div>
        <div class="table-container" style="overflow: auto; height: 100%;">
          <table class="table table-bordered" style="margin-bottom: 0; color: black;">
            <thead>
              <tr>
                <th @click="customSort('container_no')" style="text-align: left; white-space: nowrap; cursor: pointer;" scope="col">Container No.</th>
                <th @click="customSort('eir_no')" scope="col" style="white-space: nowrap; cursor: pointer;">EIR</th>
                <th @click="customSort('type')" scope="col" style="white-space: nowrap; cursor: pointer;">Type</th>
                <th @click="customSort('size_type')" scope="col" style="white-space: nowrap; cursor: pointer;">Size</th>
                <th @click="customSort('client')" scope="col" style="white-space: nowrap; cursor: pointer;">Client</th>
                <th @click="customSort('inspected_date')" scope="col" style="white-space: nowrap; cursor: pointer;">Date Time</th>
                <th @click="customSort('container_class')" scope="col" style="white-space: nowrap; cursor: pointer;">Class</th>
                <th @click="customSort('remarks')" scope="col" style="white-space: nowrap; cursor: pointer;">Remarks</th>
                <th @click="customSort('consignee')" scope="col" style="white-space: nowrap; cursor: pointer;">Consignee</th>
                <th @click="customSort('plate_no')" scope="col" style="white-space: nowrap; cursor: pointer;">Plate No.</th>
                <th @click="customSort('hauler')" scope="col" style="white-space: nowrap; cursor: pointer;">Trucker</th>
                <th @click="customSort('booking_no')" scope="col" style="white-space: nowrap; cursor: pointer;">Booking No.</th>
                <th @click="customSort('seal_no')" scope="col" style="white-space: nowrap; cursor: pointer;">Seal No.</th>
                <th @click="customSort('inspected_date')" scope="col" style="white-space: nowrap; cursor: pointer;">Date Out</th>
                <th @click="customSort('inspected_date')" scope="col" style="white-space: nowrap; cursor: pointer;">Time</th>
              </tr>
            </thead>
            <tbody v-if="containerOutList.length > 0">
              <tr v-for="(item, index) in containerOutList" :key="index">
                <td style="white-space: nowrap" class="viewItemOnClick"  v-on:click="reroute(item.releasing.id, item.id)">@{{ item.container_no }}</td>
                <td style="white-space: nowrap">@{{ item.eir_no_out ? item.eir_no_out.eir_no : '' }}</td>
                <td style="white-space: nowrap">@{{ item.type ? item.type.code : '' }}</td>
                <td style="white-space: nowrap">@{{ item.size_type ? item.size_type.size : '' }}</td>
                <td style="white-space: nowrap">@{{ item.client ? item.client.code : ''  }}</td>
                <td style="white-space: nowrap">@{{ moment(item.releasing.inspected_date).format('DD/MM/YYYY HH:mm') }}</td>
                <td style="white-space: nowrap">@{{ item.container_class ? item.container_class.class_name : '' }}</td>
                <td style="white-space: nowrap">@{{ item.releasing.remarks }}</td>
                <td style="white-space: nowrap">@{{ item.releasing.consignee }}</td>
                <td style="white-space: nowrap">@{{ item.releasing.plate_no }}</td>
                <td style="white-space: nowrap">@{{ item.releasing.hauler }}</td>
                <td style="white-space: nowrap">@{{ item.releasing.booking_no }}</td>
                <td style="white-space: nowrap">@{{ item.releasing.seal_no }}</td>
                <td style="white-space: nowrap" class="viewItemOnClick"  v-on:click="reroute(item.releasing.id, item.id)">@{{ moment(item.releasing.inspected_date).format('DD/MM/YYYY') }}</td>
                <td style="white-space: nowrap">@{{ moment(item.releasing.inspected_date).format('HH:mm') }}</td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="14" style="text-align: center;" v-if="tableLoad === true">
                  <div class="lds-facebook"><div></div><div></div><div></div></div><br>
                  <div>Fetching...</div>
                </td>
                <td colspan="14" style="text-align: center;" v-else>No Data Available</td>
              </tr>
            </tbody>
          </table>
          <div style="display: flex; margin-top: 10px;">
            <div v-if="containerOutList.length > 0" style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
              <div style="margin-left: 50px;margin-top: -8px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">@{{ van_total }}</div>
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
    el: '#containerOut',
    components: {
      vuejsDatepicker,
    },
    data: {
      form: {
        param: 'container_no',
        order: 'ASC'
      },
      errors: [],
      clientList: [],
      sizeTypeList: [],
      typeList: [],
      bookingNoList: [],
      classList: [],
      containerNoList: [],
      loading: false,
      containerOutList: [],
      emptyLoadedList: [],
      tableLoad: false,
      generateLoad: false,
      exportLoad: false,
      isOk: true,
      printLoad: false,
      van_total: '',
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
        this.getContainerOut()
      },
      reroute(releasing_id, container_id) {
        localStorage.setItem('container_id', container_id)
				let customUrl = `${window.location.origin}/admin/container-releasings/${releasing_id}/edit`
				window.location = customUrl
		  },
    	dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      async printContainerOut () {
        let payload = {
          param: this.form.param,
          order: this.form.order,
          type: this.form.type === undefined || null ? 'NA' : this.form.type,
          sizeType: this.form.sizeType === undefined || null ? 'NA' : this.form.sizeType,
          client: this.form.client === undefined || null ? 'NA' : this.form.client,
          class: this.form.class === undefined || null ? 'NA' : this.form.class,
          status: this.form.status === undefined || null ? 'NA' : this.form.status,
          from: this.form.from === undefined || this.form.from === null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
          to: this.form.to === undefined || this.form.to === null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
        }
        await axios.get(`/admin/get/print/daily_out/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.status}/${payload.from}/${payload.to}/${payload.param}/${payload.order}`).then(data => {
          let pasmo = data.data
          let w = window.open(`/admin/get/print/daily_out/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.status}/${payload.from}/${payload.to}/${payload.param}/${payload.order}`, '_blank');

          var css = '@page { size: landscape; }',
              head = document.head || document.getElementsByTagName('head')[0],
              style = document.createElement('style');

          style.type = 'text/css';
          style.media = 'print';

          if (style.styleSheet){
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(document.createTextNode(css));
          }
          
          w.document.write(pasmo);
          setTimeout(() => { 
              w.print();
              w.close();
          }, 100);
        })
      },
      async getClient () {
        if (this.form.from && this.form.to) {
          let search = {
            keyword: '',
            from: this.form.from === undefined || this.form.from === null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || this.form.to === null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.get(`/admin/get/client/dateOut?keyword=${search.keyword}&from=${search.from}&to=${search.to}`, search).then( data => {
            this.clientList = data.data
          }).catch(error => {
            console.log('error: ', error)
          })
        }
        else
        {
          let search = {
            keyword: '',
          }
          await axios.get(`/admin/get/clients?keyword=${search.keyword}`, search).then( data => {
            this.clientList = data.data
          }).catch(error => {
            console.log('error: ', error)
          })
        }
      },
      async getContainerOut () {
        // if (this.form.from && this.form.to) {
        	this.generateLoad = true
          let payload = {
            param: this.form.param,
            order: this.form.order,
            type: this.form.type === undefined || null ? 'NA' : this.form.type,
            sizeType: this.form.sizeType === undefined || null ? 'NA' : this.form.sizeType,
            client: this.form.client === undefined || null ? 'NA' : this.form.client,
            class: this.form.class === undefined || null ? 'NA' : this.form.class,
            status: this.form.status === undefined || null ? 'NA' : this.form.status,
            from: this.form.from === undefined || this.form.from === null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || this.form.to === null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.post(`/admin/get/daily_out`, payload).then(data => {
          	this.generateLoad = false
            this.containerOutList = data.data.data
            this.van_total = data.data.count
            if (data.data.data.length === 0) {
              Swal.fire({
                title: '',
                text: 'No record found!',
                icon: 'error',
              })
            }
          }).catch(error => {
          	this.generateLoad = false
            console.log(error)
          })
        // } else {
        //   Swal.fire({
        //     title: '',
        //     text: 'Please fill out the required fields!',
        //     icon: 'error',
        //   })
        // }
      },
      async exportContainerOut () {
        // if (this.form.from && this.form.to) {
        	this.exportLoad = true
          let payload = {
            param: this.form.param,
            order: this.form.order,
            type: this.form.type === undefined || null ? 'NA' : this.form.type,
            sizeType: this.form.sizeType === undefined || null ? 'NA' : this.form.sizeType,
            client: this.form.client === undefined || null ? 'NA' : this.form.client,
            class: this.form.class === undefined || null ? 'NA' : this.form.class,
            status: this.form.status === undefined || null ? 'NA' : this.form.status,
            from: this.form.from === undefined || this.form.from === null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || this.form.to === null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.get(`/excel/daily_container_out/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.status}/${payload.from}/${payload.to}/${payload.param}/${payload.order}`).then(data => {
          	this.exportLoad = false
            window.open(`${location.origin}/excel/daily_container_out/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.status}/${payload.from}/${payload.to}/${payload.param}/${payload.order}`, "_blank");
          }).catch(error => {
          	this.exportLoad = false
            console.log(error)
          })
        // } else {
        //   Swal.fire({
        //     title: '',
        //     text: 'Please fill out the required fields!',
        //     icon: 'error',
        //   })
        // }
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
      selectBooking () {
      	this.isOk = false
      	this.getContainerNo()
      },
      searchBookingNo () {
      	clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          const payload = {
            keyword: this.form.booking_no
          }
          axios.get(`/admin/get/booking_no/all?keyword=${payload.keyword}`, payload)
          .then(data => {
            this.bookingNoList = data.data
          })
        }, 1000)
      },
      async getBookingNo () {
        let search = {
          keyword: ''
        }
        await axios.get(`/admin/get/booking_no/all?keyword=${search.keyword}`, search).then( data => {
          this.bookingNoList = data.data
        }).catch(error => {
          console.log('error: ', error)
        })
      },
      searchContainerNo () {
      	clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          const payload = {
            booking_no: this.form.container_no
          }
          axios.get(`/admin/get/container_no/byBookingNo?booking_no=${this.form.booking_no}`, payload)
          .then(data => {
            this.containerNoList = data.data
          })
        }, 1000)
      },
      async getContainerNo () {
        let search = {
          booking_no: ''
        }
        await axios.get(`/admin/get/container_no/byBookingNo?booking_no=${this.form.booking_no}`, search).then( data => {
          this.containerNoList = data.data
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
      },
    },
    mounted () {
      this.getSize()
      this.getType()
      this.getBookingNo()
      this.getClass()
      this.getEmptyLoaded()
      this.getClient()
    }
  })

</script>
@stop
