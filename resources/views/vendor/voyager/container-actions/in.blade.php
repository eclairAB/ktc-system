<div id="containerIn">

  <div class="panel panel-default" style="margin-top: 15px;">
    
    <div class="panel-body" style="background-color: #fff; border: 0;">
      <div class="row">
        <div class="col-xs-12" style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-weight: bold; font-size: 18px;">Daily Container In Report</span>
          <button class="btn btn-success" @click="exportContainerIn">Export to Excel</button>
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
              <label for="lastname" class="form-control-placeholder"> Size Type <span style="color: red;"> *</span></label>
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
              <label for="client" class="form-control-placeholder"> Client <span style="color: red;"> *</span></label>
            </div>
            
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
              <v-select
                style="height: 37px !important;"
                class="form-control"
                :options="yardList"
                v-model="choosenYard"
                label="name"
                @option:selected="clearYard()"
                :reset-on-options-change='true'
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
              <label for="loc" class="form-control-placeholder"> Yard Location</label>
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
                    style="color: black;"
                    @input="searchContainerNo()"
                  />
                </template>
              </v-select>
              <label for="lastname" class="form-control-placeholder"> Container No. <span style="color: red;"> *</span></label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<vuejs-datepicker
                v-model="form.from"
                placeholder="mm/dd/yyyyy"
                input-class="form-control"
                :typeable="true"
                name="from"
                :format="dateFormat"
                :required="true">
              </vuejs-datepicker>
              <label for="from" class="form-control-placeholder"> Date From</label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
            	<vuejs-datepicker
                v-model="form.to"
                placeholder="mm/dd/yyyyy"
                input-class="form-control"
                :typeable="true"
                name="to"
                :format="dateFormat"
                :required="true">
              </vuejs-datepicker>
              <label for="to" class="form-control-placeholder"> Date To</label>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0px; display: flex; justify-content: flex-end;">
            	<button class="btn btn-primary" @click="getContainerIn">Generate</button>
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
          <span>Container Daily In Report</span>
      </div>
      <table class="table table-bordered" style="margin-bottom: 0; color: black;">
        <thead>
          <tr>
            <th style="text-align: left;" scope="col">Eir No.</th>
            <th scope="col">Container No.</th>
            <th scope="col">Size/Type</th>
            <th scope="col">Date In</th>
            <th scope="col">Shipping Line</th>
            <th scope="col">Truckers</th>
            <th scope="col">Plate No.</th>
            <th scope="col">Inspected By</th>
            <th scope="col">Class</th>
            <th scope="col">Manufactured Date</th>
            <th scope="col">Status</th>
            <th scope="col">Remarks</th>
          </tr>
        </thead>
        <tbody v-if="containerInList.length > 0">
          <tr v-for="(item, index) in containerInList" :key="index">
            <td>@{{ item.id }}</td>
            <td>@{{ item.container_no }}</td>
            <td>@{{ item.size_type.size }} - @{{ item.size_type.type }}</td>
            <td>@{{ item.inspected_date }}</td>
            <td>@{{ item.client.code_name }}</td>
            <td>@{{ item.hauler }}</td>
            <td>@{{ item.plate_no }}</td>
            <td>@{{ item.inspector.name }}</td>
            <td>@{{ item.container_class.class_name }}</td>
            <td>@{{ item.manufactured_date }}</td>
            <td>Received</td>
            <td>@{{ item.remarks }}</td>
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
    el: '#containerIn',
    components: {
      vuejsDatepicker,
    },
    data: {
      form: {
        loc: 'NA',
      },
      errors: [],
      clientList: [],
      sizeTypeList: [],
      bookingNoList: [],
      containerNoList: [],
      yardList: [],
      choosenSize: {},
      choosenClient: {},
      choosenYard: {},
      sizeSearch: '',
      clientSearch: '',
      yardSearch: '',
      loading: false,
      containerInList: [],
      tableLoad: false
    },
    watch: {
      'choosenSize': {
        handler () {
          if (this.choosenSize === null) {
            if (this.form.sizeType){
              delete this.form.sizeType
            }
          }
        },
        deep: true
      },
      'choosenClient': {
        handler () {
          if (this.choosenClient === null) {
            if (this.form.client){
              delete this.form.client
            }
          }
        },
        deep: true
      },
      'choosenYard': {
        handler () {
          if (this.choosenYard === null) {
            if (this.form.loc){
              this.form.loc = 'NA'
            }
          }
        },
        deep: true
      }
    },
    methods: {
    	dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      async getContainerIn () {
        if (this.form.sizeType && this.form.client && this.form.container_no) {
          let payload = {
            sizeType: this.form.sizeType,
            client: this.form.client,
            container_no: this.form.container_no,
            loc: this.form.loc,
            from: this.form.from === undefined || null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.post(`/admin/get/daily_in`, payload).then(data => {
            this.containerInList = data.data
            if (data.data.length === 0) {
              Swal.fire({
                title: '',
                text: 'No record found!',
                icon: 'error',
              })
            }
          }).catch(error => {
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
      async exportContainerIn () {
        if (this.form.sizeType && this.form.client && this.form.container_no) {
          let payload = {
            sizeType: this.form.sizeType,
            client: this.form.client,
            container_no: this.form.container_no,
            loc: this.form.loc,
            from: this.form.from === undefined || null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.get(`/excel/daily_container_in/${payload.sizeType}/${payload.client}/${payload.container_no}/${payload.loc}/${payload.from}/${payload.to}`).then(data => {
            if (data.data.length === 0) {
              Swal.fire({
                title: '',
                text: 'No record found!',
                icon: 'error',
              })
            }
          }).catch(error => {
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
        this.form.sizeType = this.choosenSize.id
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
        this.form.client = this.choosenClient.id
        this.clientSearch = ''
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
        this.form.loc = this.choosenYard.id
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
      this.getContainerNo()
      this.getYard()
    }
  })

</script>

<style type="text/css">
  .form-control {
    color: black !important;
  }
</style>
