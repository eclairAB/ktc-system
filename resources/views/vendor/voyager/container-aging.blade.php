@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <style type="text/css">
		  .form-control {
		    color: black !important;
		  }
		</style>
@stop

@section('content')
<body>
	<div id="containerIn">

	  <div class="panel panel-default" style="margin-top: 15px;">
	    
	    <div class="panel-body" style="background-color: #fff; border: 0;">
	      <div class="row">
	        <div class="col-xs-12" style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: center;">
	          <span style="font-weight: bold; font-size: 18px;">Container Aging and Inventory</span>
	          <button class="btn btn-success" :disabled="exportLoad" @click="exportContainerIn">@{{ exportLoad === false ? 'Export to Excel' : 'Loading...' }}</button>
	        </div>
	        <div class="col-xs-12" style="margin-bottom: 0;">
	          <hr style="margin: 5px 0;">
	        </div>
	        <div class="col-xs-12" style="margin: 0;">
	          <div class="row" style="padding: 0 15px;">
	            
	            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
	              <v-select
	                style="height: 37px !important;"
	                :options="clientList"
	                v-model="form.client_id"
	                label="code_name"
	                class="form-control"
	                :reduce="code_name => code_name.id"
	              ></v-select>
	              <label for="client" class="form-control-placeholder"> Client <span style="color: red;"> *</span></label>
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
	                v-model="form.size_type"
	                class="form-control"
	                label="code"
	                :reduce="code => code.id"
	              ></v-select>
	              <label for="size_type" class="form-control-placeholder"> Size<span style="color: red;"> *</span></label>
	            </div>

	            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <v-select
                  :class="errors.type_id ? 'isError form-control' : 'form-control'"
                  :options="typeList"
                  style="height: 37px !important;"
                  v-model="form.type_id"
                  label="code"
                  :reduce="code => code.id"
                ></v-select>
                <label for="type" class="form-control-placeholder"> Type</label>
                <div class="customErrorText"><small>@{{ errors.size_type ? errors.size_type[0] : '' }}</small></div>
              </div>

	            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px; margin-bottom: 10px;">
                <input type="text" name="remarks" id="remarks" v-model="form.remarks" style="height: 37px;" class="form-control">
                <label for="remarks" class="form-control-placeholder"> Remarks<span style="color: red"> *</span></label>
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
	              <label for="from" class="form-control-placeholder"> Date as of</label>
	            </div>

	            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-right: 5px; padding-left: 5px; margin-bottom: 0px; display: flex; justify-content: flex-end;">
	            	<button class="btn btn-primary" :disabled="generateLoad" @click="getContainerIn">@{{ generateLoad === false ? 'Generate' : 'Loading...' }}</button>
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
	          <span>Container Aging and Inventory</span>
	      </div>
	      <table class="table table-bordered" style="margin-bottom: 0; color: black;">
	        <thead>
	          <tr>
	            <th style="text-align: left;" scope="col">#</th>
	            <th scope="col">Client</th>
	            <th scope="col">Container No.</th>
	            <th scope="col">Type</th>
	            <th scope="col">Mfg Date</th>
	            <th scope="col">Class</th>
	            <th scope="col">Date In</th>
	            <th scope="col">Total No. of Days</th>
	            <th scope="col">Condition</th>
	            <th scope="col">Empty/Loaded</th>
	            <th scope="col">Yard</th>
	            <th scope="col">Status</th>
	            <th scope="col">Consignee</th>
	            <th scope="col">Remarks</th>
	          </tr>
	        </thead>
	        <tbody v-if="containerInList.length > 0">
	          <tr v-for="(item, index) in containerInList" :key="index">
	            <td>@{{ item.id }}</td>
	            <td>@{{ item.container_no }}</td>
	            <td>@{{ item.size_type.code }} - @{{ item.size_type.name }}</td>
	            <td>@{{ moment(item.inspected_date).format('MMMM DD, YYYY') }}</td>
	            <td>@{{ item.client.code_name }}</td>
	            <td>@{{ item.hauler }}</td>
	            <td>@{{ item.plate_no }}</td>
	            <td>@{{ item.inspector.name }}</td>
	            <td>@{{ item.container_class.class_name }}</td>
	            <td>@{{ moment(item.manufactured_date).format('MMMM DD, YYYY') }}</td>
	            <td>Received</td>
	            <td>@{{ item.remarks }}</td>
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
</body>

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
      form: {},
      errors: [],
      classList: [],
      clientList: [],
      sizeTypeList: [],
      typeList: [],
      containerInList: [],
      loading: false,
      tableLoad: false,
      generateLoad: false,
      exportLoad: false
    },
    methods: {
    	dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      async getContainerIn () {
        if (this.form.sizeType && this.form.client && this.form.container_no) {
          this.generateLoad = true
          let payload = {
            sizeType: this.form.sizeType,
            client: this.form.client,
            container_no: this.form.container_no,
            loc: this.form.loc,
            from: this.form.from === undefined || null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.post(`/admin/get/daily_in`, payload).then(data => {
            this.generateLoad = false
            this.containerInList = data.data
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
      async exportContainerIn () {
        if (this.form.sizeType && this.form.client && this.form.container_no) {
          this.exportLoad = true
          let payload = {
            sizeType: this.form.sizeType,
            client: this.form.client,
            container_no: this.form.container_no,
            loc: this.form.loc,
            from: this.form.from === undefined || null ? 'NA' : moment(this.form.from).format('YYYY-MM-DD'),
            to: this.form.to === undefined || null ? 'NA' : moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.get(`/excel/daily_container_in/${payload.sizeType}/${payload.client}/${payload.container_no}/${payload.loc}/${payload.from}/${payload.to}`).then(data => {
            this.exportLoad = false
            window.open(`${location.origin}/excel/daily_container_in/${payload.sizeType}/${payload.client}/${payload.container_no}/${payload.loc}/${payload.from}/${payload.to}`, "_blank");
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
      async getClass () {
        let search = {
          keyword: ''
        }
        await axios.get(`/admin/get/container/classes?keyword=${search.keyword}`, search).then( data => {
          this.classList = data.data
        }).catch(error => {
          console.log('error: ', error)
        })
      }
    },
    mounted () {
      this.getSize()
      this.getType()
      this.getClient()
      this.getClass()
    }
  })

</script>
@stop
