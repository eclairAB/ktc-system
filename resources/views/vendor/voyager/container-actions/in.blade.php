<div id="containerIn">

  <div class="panel panel-default" style="margin-top: 15px;">
    
    <div class="panel-body" style="background-color: #fff; border: 0;">
      <div class="row">
        <div class="col-xs-12" style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-weight: bold; font-size: 18px;">Daily Container In Report</span>
          <button class="btn btn-success" :disabled="exportLoad" @click="exportContainerIn">@{{ exportLoad === false ? 'Export to Excel' : 'Loading...' }}</button>
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
              <label for="from" class="form-control-placeholder"> Date In <span style="color: red;"> *</span></label>
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
              <label for="to" class="form-control-placeholder"> Date To <span style="color: red;"> *</span></label>
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
              <label for="client" class="form-control-placeholder"> Client <span style="color: red;"> *</span></label>
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
              <label for="type" class="form-control-placeholder"> Status</label>
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
              <label for="class" class="form-control-placeholder"> Class <span style="color: red;"> *</span></label>
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
          <span>Container Daily In Report</span>
      </div>
      <table class="table table-bordered" style="margin-bottom: 0; color: black;">
        <thead>
          <tr>
            <th style="text-align: left;" scope="col">Container No.</th>
            <th scope="col">EIR</th>
            <th scope="col">Size</th>
            <th scope="col">Type</th>
            <th scope="col">Client</th>
            <th scope="col">Consignee</th>
            <th scope="col">Plate No.</th>
            <th scope="col">Trucker</th>
            <th scope="col">Class</th>
            <th scope="col">Remarks</th>
            <th scope="col">Date In</th>
          </tr>
        </thead>
        <tbody v-if="containerInList.length > 0">
          <tr v-for="(item, index) in containerInList" :key="index">
            <td>@{{ item.container_no }}</td>
            <td>@{{ item.id }}</td>
            <td>@{{ item.size_type.code }} - @{{ item.size_type.name }}</td>
            <td>@{{ item.type.code }} - @{{ item.type.name }}</td>
            <td>@{{ item.client.code  }}</td>
            <td>@{{ item.consignee }}</td>
            <td>@{{ item.plate_no }}</td>
            <td>@{{ item.hauler }}</td>
            <td>@{{ item.container_class.class_name }}</td>
            <td>@{{ item.remarks }}</td>
            <td>@{{ moment(item.inspected_date).format('MMMM DD, YYYY') }}</td>
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
      form: {},
      errors: [],
      clientList: [],
      sizeTypeList: [],
      typeList: [],
      bookingNoList: [],
      containerNoList: [],
      emptyLoadedList: [],
      classList: [],
      loading: false,
      containerInList: [],
      tableLoad: false,
      generateLoad: false,
      exportLoad: false
    },
    methods: {
    	dateFormat(date) {
        return moment(date).format('MM/DD/yyyy');
      },
      async getClient () {
        if (this.form.from && this.form.to) {
          let search = {
            keyword: '',
            from: moment(this.form.from).format('YYYY-MM-DD'),
            to: moment(this.form.to).format('YYYY-MM-DD'),
          }
          await axios.get(`/admin/get/client/dateIn?keyword=${search.keyword}&from=${search.from}&to=${search.to}`, search).then( data => {
            this.clientList = data.data
          }).catch(error => {
            console.log('error: ', error)
          })
        }
      },
      async getContainerIn () {
        if (this.form.client && this.form.class && this.form.from && this.form.to) {
          this.generateLoad = true
          let payload = {
            type: this.form.type === undefined || null ? 'NA' : this.form.type,
            sizeType: this.form.sizeType === undefined || null ? 'NA' : this.form.sizeType,
            client: this.form.client,
            class: this.form.class,
            status: this.form.status === undefined || null ? 'NA' : this.form.status,
            from: moment(this.form.from).format('YYYY-MM-DD'),
            to: moment(this.form.to).format('YYYY-MM-DD')
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
        if (this.form.client && this.form.class && this.form.from && this.form.to) {
          this.exportLoad = true
          let payload = {
            type: this.form.type === undefined || null ? 'NA' : this.form.type,
            sizeType: this.form.sizeType === undefined || null ? 'NA' : this.form.sizeType,
            client: this.form.client,
            class: this.form.class,
            status: this.form.status === undefined || null ? 'NA' : this.form.status,
            from: moment(this.form.from).format('YYYY-MM-DD'),
            to: moment(this.form.to).format('YYYY-MM-DD')
          }
          await axios.get(`/excel/daily_container_in/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.status}/${payload.from}/${payload.to}`).then(data => {
            this.exportLoad = false
            window.open(`${location.origin}/excel/daily_container_in/${payload.type}/${payload.sizeType}/${payload.client}/${payload.class}/${payload.status}/${payload.from}/${payload.to}`, "_blank");
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
      this.getContainerNo()
      this.getClass()
      this.getEmptyLoaded()
    }
  })

</script>

<style type="text/css">
  .form-control {
    color: black !important;
  }
</style>
