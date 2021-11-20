<div id="containerIn">

  <div class="panel panel-default" style="margin-top: 15px;">
    
    <div class="panel-body" style="background-color: #fff; border: 0;">
      <div class="row">
        <div class="col-xs-12" style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-weight: bold; font-size: 18px;">Daily Container In Report</span>
          <button class="btn btn-success">Export to Excel</button>
        </div>
        <div class="col-xs-12" style="margin-bottom: 0;">
          <hr style="margin: 5px 0;">
        </div>
        <div class="col-xs-12">
          <div class="row" style="padding: 0 15px;">
          	
          	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
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
            
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
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
            
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
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
              <label for="client" class="form-control-placeholder"> Booking No.</label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
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
              <label for="lastname" class="form-control-placeholder"> Container No.</label>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
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

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-group" style="padding-right: 5px; padding-left: 5px;">
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

          </div>
        </div>
      </div>
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
      choosenSize: {},
      choosenClient: {},
      sizeSearch: '',
      clientSearch: '',
      loading: false
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
      }
    },
    mounted () {
      this.getSize()
      this.getClient()
    }
  })

</script>

<style type="text/css">
  .form-control {
    color: black !important;
  }
</style>
