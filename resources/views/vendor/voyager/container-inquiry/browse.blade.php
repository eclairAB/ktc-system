@extends('voyager::master')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
<style>
    .containers_ {
      margin: .5%;
    }
</style>
<body>
    <div style="font-size: 15px; color: #555; font-weight: bold; display: flex; margin: 20px 10px 10px; align-items: center;">
        <i style="font-size: 25px; margin-right: 10px; height: 35px;" class="voyager-list"></i> <span>Containers</span>
    </div>
    <div id="container-inquiry">
        <div class="row" style="margin: 0; display: flex; padding: 0 7px;">
            <form method="get" class="form-search" v-on:submit.prevent="submitForm" style="width: 100%;">
                <div id="search-input" style="margin: 0; position: unset !important;">
                    <div class="input-group col-xs-12">
                        <input type="hidden" name="page">
                        <input type="text" class="form-control" placeholder="Search container" name="search_input" v-on:keyup.13="submitForm" v-model="searchinput">
                        <span class="input-group-btn" style="width: 15px;">
                            <button class="btn btn-info btn-lg" type="submit">
                                <i class="voyager-search"></i>
                            </button>
                        </span> 
                    </div>
                </div>
               
            </form>
            <button class="btn btn-sm btn-primary pull-right edit" @click="clearFilters()">
                Clear
            </button>
        </div>
        <div row>
            <div class="col-md-12 paginator_ containers_">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                          <th style="padding: 10px 10px;">
                              Container
                          </th>
                          <th style="padding: 10px 10px;">
                              Size
                          </th>
                          <th style="padding: 10px 10px;">
                              Type
                          </th>
                          <th style="padding: 10px 10px;">
                              Eir-in
                          </th>
                          <th style="padding: 10px 10px;">
                              Eir-out
                          </th>
                          <th style="padding: 10px 10px;">
                              Status
                          </th>
                          <th style="padding: 10px 10px;">
                              Client
                          </th>
                          <th style="padding: 10px 10px;">
                              Gate-in
                          </th>
                          <th style="padding: 10px 10px;">
                              Gate-out
                          </th>
                          <th style="padding: 10px 10px;">
                              Remarks
                          </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($containers as $item)
                            <tr style="
                                    border-top: solid #5c5c5c29 1px;
                                    line-height: 30px;
                                "
                            >
                                <td style="padding: 0 10px">
                                    {{ $item->container_no }}
                                </td>
                                <td style="padding: 0 10px">
                                    {{ $item->sizeType->size??'' }}
                                </td> 
                                <td style="padding: 0 10px">
                                    {{ $item->type->code??'' }}
                                </td>    
                                <td style="padding: 0 10px">
                                    {{ $item->eirNoIn->eir_no??'' }}
                                </td>    
                                <td style="padding: 0 10px">
                                    {{ $item->eirNoOut->eir_no??'' }}
                                </td> 
                                <td style="padding: 0 10px">
                                    {{ $item->receiving->empty_loaded??'' }}
                                </td>    
                                <td style="padding: 0 10px">
                                    {{ $item->client->code??'' }}
                                </td>    
                                <td 
                                    class="viewItemOnClick"
                                    style="padding: 0 10px"
                                    v-on:click="rerouteReceiving('{{ $item->receiving_id }}')"
                                >
                                    {{ is_null($item->receiving)?'':Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d') }}
                                </td>    
                                <td 
                                    :class="'{{ $item->releasing_id }}' ? 'viewItemOnClick' : ''"
                                    style="padding: 0 10px"
                                    v-on:click="rerouteReleasing('{{ $item->releasing_id }}')"
                                >
                                    {{ is_null($item->releasing)?'':Carbon\Carbon::parse($item->releasing->inspected_date)->format('Y-m-d') }}
                                </td> 
                                <td style="padding: 0 10px">
                                    {{ $item->receiving->remarks??'' }}
                                </td>     
                            </tr>
                        @empty
                            <tr style="border-top: solid #5c5c5c29 1px; font-weight: bold; color: #979797;">
                                <td style="padding: 0 10px;">No record at this moment</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <br>
                {{ $containers->links() }}
            </div>
        </div>      
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
    var vm = new Vue({
    el: '#container-inquiry',
    data:{
        searchinput: '',
    },
    methods: {
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
        submitForm(page_) {
            const page = document.querySelector("ul.pagination li.active span")
            let filter = {
                search_input: this.searchinput
            }

            if ( Number.isInteger(page_) ) { // when paginate buttons are clicked
                filter.page = page_
            }
            else { // when filters are interacted
                if (page) { // when filter returns only 1 page
                    filter.page = Number(page.innerHTML)
                }
                else {
                    filter.page = 1
                }
            }
            document.querySelector("input[name='page']").value = filter.page

            if (!page) { // when filter returns only 1 page
              document.querySelector("input[name='page']").value = 1          
            }

            localStorage.setItem('inquiry_request_filters', JSON.stringify(filter))
            document.querySelector("form.form-search").submit()
        },
        getFilters () {
            const filter = JSON.parse(localStorage.getItem('inquiry_request_filters'))
            if (filter) {
                this.searchinput = filter.search_input
                if (`{!! $containers !!}` && {!! $containers->count() !!} == 0) {
                    document.querySelector("input[name='page']").value = 1
                    document.querySelector("input[name='search_input']").value = filter.search_input
                    this.submitForm()
                }
            }
        },
        clearFilters() {
            document.querySelector("input[name='page']").value = 1
            document.querySelector("input[name='search_input']").value = ""
            localStorage.removeItem('inquiry_request_filters')
            document.querySelector("form.form-search").submit()
        }
    },
    created() {
        this.getFilters()
    }
  })
</script>
<script>
    function onPaginationClick(page) {
        vm.submitForm(Number(page))
    }

    function setEventLIstener() {
        const paginationButtons = document.querySelector("ul.pagination")
        if (paginationButtons) {
            for(let i in paginationButtons.children) {
                if(paginationButtons.children[i].firstChild) {
                    const button = paginationButtons.children[i].firstChild
                    if(button.href) {
                    const page = button.href.split("=")[1]
                    button.addEventListener("click", () => { onPaginationClick(page) })
                    button.removeAttribute("href")
                    }
                }
            }
        }
    }

    setEventLIstener()
</script>
@endsection
