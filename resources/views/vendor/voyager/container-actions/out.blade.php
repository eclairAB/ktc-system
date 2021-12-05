<div id="containerOut">

  <div class="panel panel-default" style="margin-top: 15px;">
    <div class="panel-body">
      <div class="row">
        <div class="col-xs-12" style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-weight: bold; font-size: 18px;">Daily Container Out Report</span>
          <button class="btn btn-success" :disabled="exportLoad" @click="exportContainerOut">@{{ exportLoad === false ? 'Export to Excel' : 'Loading...' }}</button>
        </div>
        <div class="col-xs-12" style="margin-bottom: 0;">
          <hr style="margin: 5px 0;">
        </div>
        <div class="col-xs-12" style="margin: 0;">
          <div class="row" style="padding: 0 15px;">
          	
          	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
              <v-select
                :options="sizeTypeList"
                style="height: 37px !important;"
                v-model="form.sizeType"
                class="form-control"
                label="code"
                :reduce="code => code.id"
              ></v-select>
              <label for="lastname" class="form-control-placeholder"> Size<span style="color: red;"> *</span></label>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
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
                style="height: 37px !important;"
                :options="clientList"
                v-model="form.client"
                label="code_name"
                class="form-control"
                :reduce="code_name => code_name.id"
              ></v-select>
              <label for="client" class="form-control-placeholder"> Client <span style="color: red;"> *</span></label>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<v-select
                style="height: 37px !important;"
                :class="errors.type ? 'isError form-control' : 'form-control'"
                :options="bookingNoList"
                v-model="form.booking_no"
                @option:selected="selectBooking()"
              >
              	<template #search="{attributes, events}">
                  <input
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events"
                    style="color: black;"
                    @input="searchBookingNo()"
                  />
                </template>
              </v-select>
              <label for="client" class="form-control-placeholder"> Booking No. <span style="color: red;"> *</span></label>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
              <v-select
                style="height: 37px !important;"
                class="form-control"
                :disabled="isOk"
                :options="containerNoList"
                v-model="form.container_no"
              >
              	<template #search="{attributes, events}">
                  <input
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events"
                    style="color: black;"
                    @input="searchContainerNo()"
                  />
                </template>
              </v-select>
              <label for="lastname" class="form-control-placeholder"> Container No. <span style="color: red;"> *</span></label>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<vuejs-datepicker
                v-model="form.from"
                placeholder="mm/dd/yyyyy"
                :input-class="errors.from ? 'isError form-control' : 'form-control'"
                :typeable="true"
                name="from"
                :format="dateFormat"
                :required="true">
              </vuejs-datepicker>
              <label for="from" class="form-control-placeholder"> Date From</label>
              <div class="customErrorText"><small>@{{ errors.from ? errors.from[0] : '' }}</small></div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<vuejs-datepicker
                v-model="form.to"
                placeholder="mm/dd/yyyyy"
                :input-class="errors.to ? 'isError form-control' : 'form-control'"
                :typeable="true"
                name="to"
                :format="dateFormat"
                :required="true">
              </vuejs-datepicker>
              <label for="to" class="form-control-placeholder"> Date To</label>
              <div class="customErrorText"><small>@{{ errors.to ? errors.to[0] : '' }}</small></div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0px; display: flex; justify-content: flex-end;">
            	<button class="btn btn-primary" :disabled="generateLoad" @click="getContainerOut">@{{ generateLoad === false ? 'Generate' : 'Loading...' }}</button>
            </div>

          </div>
        </div>
      </div>
    </div>  
  </div>

  <div class="panel panel-default" style="margin-top: 15px;">
    <div class="panel-body">
  		<div style="color: black; font-weight: bold; text-align: center; margin-bottom: 10px;">
          <img src = "{{ asset('/images/kudos.png') }}" width="150px" /><br>
          <span>Container Daily Out Report</span>
      </div>
      <table class="table table-bordered" style="margin-bottom: 0; color: black;">
        <thead>
          <tr>
            <th style="text-align: left;" scope="col">Eir No.</th>
            <th scope="col">Container No.</th>
            <th scope="col">Size/Type</th>
            <th scope="col">Date Out</th>
            <th scope="col">Booking No.</th>
            <th scope="col">Seal No.</th>
            <th scope="col">Shipping Line</th>
            <th scope="col">Truckers</th>
            <th scope="col">Plate No.</th>
            <th scope="col">Checker</th>
            <th scope="col">Class</th>
            <th scope="col">Manufactured Date</th>
            <th scope="col">Status</th>
            <th scope="col">Remarks</th>
          </tr>
        </thead>
        <tbody v-if="containerOutList.length > 0">
          <tr v-for="(item, index) in containerOutList" :key="index">
            <td>@{{ item.id }}</td>
            <td>@{{ item.container_no }}</td>
            <td>@{{ item.container.size_type ? `${item.container.size_type.code} - ${item.container.size_type.name}` : '' }}</td>
            <td>@{{ moment(item.inspected_date).format('MMMM DD, YYYY') }}</td>
            <td>@{{ item.booking_no }}</td>
            <td>@{{ item.seal_no }}</td>
            <td>@{{ item.container.client ? item.container.client.code_name : '' }}</td>
            <td>@{{ item.hauler }}</td>
            <td>@{{ item.plate_no }}</td>
            <td>@{{ item.inspector.name }}</td>
            <td>@{{ item.container.container_class ? item.container.container_class.class_name : '' }}</td>
            <td>@{{ moment(item.manufactured_date).format('MMMM DD, YYYY') }}</td>
            <td>Released</td>
            <td>@{{ item.remarks }}</td>
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
    </div>  
  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
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
      form: {},
      errors: [],
      clientList: [],
      sizeTypeList: [],
      typeList: [],
      bookingNoList: [],
      containerNoList: [],
      loading: false,
      containerOutList: [],
      tableLoad: false,
      generateLoad: false,
      exportLoad: false,
      isOk: true
    },
    methods: {
    	dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      async getContainerOut () {
        if (this.form.sizeType && this.form.client && this.form.container_no && this.form.booking_no) {
        	this.generateLoad = true
          let payload = {
            type: this.form.type,
            sizeType: this.form.sizeType,
            client: this.form.client,
            container_no: this.form.container_no,
            booking_no: this.form.booking_no === null || this.form.booking_no === undefined ? 'NA' : this.form.booking_no,
            from: this.form.from === undefined || null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.post(`/admin/get/daily_out`, payload).then(data => {
          	this.generateLoad = false
            this.containerOutList = data.data
            if (data.data.length === 0) {
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
        } else {
          Swal.fire({
            title: '',
            text: 'Please fill out the required fields!',
            icon: 'error',
          })
        }
      },
      async exportContainerOut () {
        if (this.form.sizeType && this.form.client && this.form.container_no && this.form.booking_no) {
        	this.exportLoad = true
          let payload = {
            type: this.form.type,
            sizeType: this.form.sizeType,
            client: this.form.client,
            container_no: this.form.container_no,
            booking_no: this.form.booking_no,
            from: this.form.from === undefined || null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.get(`/excel/daily_container_out/${payload.type}/${payload.sizeType}/${payload.client}/${payload.container_no}/${payload.booking_no}/${payload.from}/${payload.to}`).then(data => {
          	this.exportLoad = false
            window.open(`${location.origin}/excel/daily_container_out/${payload.type}/${payload.sizeType}/${payload.client}/${payload.container_no}/${payload.booking_no}/${payload.from}/${payload.to}`, "_blank");
          }).catch(error => {
          	this.exportLoad = false
            console.log(error)
          })
        } else {
          Swal.fire({
            title: '',
            text: 'Please fill out the required fields!',
            icon: 'error',
          })
        }
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
      }
    },
    mounted () {
      this.getSize()
      this.getType()
      this.getClient()
      this.getBookingNo()
    }
  })

</script>

<style type="text/css">
  .form-control {
    color: black !important;
  }
</style>
