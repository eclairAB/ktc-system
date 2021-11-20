<div id="containerOut">

  <div class="panel panel-default" style="margin-top: 15px;">
    <div class="panel-body">
      <div class="row">
        <div class="col-xs-12" style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-weight: bold; font-size: 18px;">Daily Container Out Report</span>
          <button class="btn btn-success">Export to Excel</button>
        </div>
        <div class="col-xs-12" style="margin-bottom: 0;">
          <hr style="margin: 5px 0;">
        </div>
        <div class="col-xs-12" style="margin: 0;">
          <div class="row" style="padding: 0 15px;">
          	
          	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
              <v-select
                :options="sizeTypeList"
                style="height: 37px !important;"
                v-model="choosenSize"
                class="form-control"
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
            </div>
            
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
              <v-select
                style="height: 37px !important;"
                :options="clientList"
                v-model="choosenClient"
                label="code_name"
                class="form-control"
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
            </div>
            
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<v-select
                style="height: 37px !important;"
                :class="errors.type ? 'isError form-control' : 'form-control'"
                :options="bookingNoList"
                v-model="form.booking_no"
              >
              	<template #search="{attributes, events}">
                  <input
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events"
                    v-model="form.booking_no"
                    style="color: black;"
                    @input="searchBookingNo()"
                  />
                </template>
              </v-select>
              <label for="client" class="form-control-placeholder"> Booking No.</label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
              <v-select
                style="height: 37px !important;"
                :class="errors.type ? 'isError form-control' : 'form-control'"
                :options="containerNoList"
                v-model="form.container_no"
              >
              	<template #search="{attributes, events}">
                  <input
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events"
                    v-model="form.container_no"
                    style="color: black;"
                    @input="searchContainerNo()"
                  />
                </template>
              </v-select>
              <label for="lastname" class="form-control-placeholder"> Container No.</label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<vuejs-datepicker
                v-model="form.manufactured_date"
                placeholder="mm/dd/yyyyy"
                :input-class="errors.manufactured_date ? 'isError form-control' : 'form-control'"
                :typeable="true"
                name="manufactured_date"
                :format="dateFormat"
                :required="true">
              </vuejs-datepicker>
              <label for="manufactured_date" class="form-control-placeholder"> Date From</label>
              <div class="customErrorText"><small>@{{ errors.manufactured_date ? errors.manufactured_date[0] : '' }}</small></div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<vuejs-datepicker
                v-model="form.manufactured_date"
                placeholder="mm/dd/yyyyy"
                :input-class="errors.manufactured_date ? 'isError form-control' : 'form-control'"
                :typeable="true"
                name="manufactured_date"
                :format="dateFormat"
                :required="true">
              </vuejs-datepicker>
              <label for="manufactured_date" class="form-control-placeholder"> Date To</label>
              <div class="customErrorText"><small>@{{ errors.manufactured_date ? errors.manufactured_date[0] : '' }}</small></div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0px; display: flex; justify-content: flex-end;">
            	<button class="btn btn-primary">Generate</button>
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
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
            <td>sample</td>
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
      bookingNoList: [],
      containerNoList: [],
      choosenSize: {},
      choosenClient: {},
      sizeSearch: '',
      clientSearch: '',
      loading: false,
      containerOutList: [],
      tableLoad: false
    },
    methods: {
    	dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      inputComponent () {

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
        this.form.client_id = this.choosenClient.id
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
        	console.log('clientList: ', data.data)
          this.clientList = data.data
        }).catch(error => {
          console.log('error: ', error)
        })
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
            keyword: this.form.container_no
          }
          axios.get(`/admin/get/container_no/all?keyword=${payload.keyword}`, payload)
          .then(data => {
            this.containerNoList = data.data
          })
        }, 1000)
      },
      async getContainerNo () {
        let search = {
          keyword: ''
        }
        await axios.get(`/admin/get/container_no/all?keyword=${search.keyword}`, search).then( data => {
          this.containerNoList = data.data
        }).catch(error => {
          console.log('error: ', error)
        })
      }
    },
    mounted () {
      this.getSize()
      this.getClient()
      this.getBookingNo()
      this.getContainerNo()
    }
  })

</script>

<style type="text/css">
  .form-control {
    color: black !important;
  }
</style>