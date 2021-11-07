@extends('voyager::master')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1">
<body>
  {{-- Hello {{ $name }}! --}}
  <div id="dashboard" class="clearfix container-fluid row">
    <div class="col-lg-5 col-md-12">
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" onclick="location.href='{{ url('admin/container-receivings/create') }}'">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Container Receiving</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit" onclick="location.href='{{ url('admin/container-releasings/create') }}'">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Container Releasing</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Container Aging and Inventory</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Daily In Container</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Daily Out Container</h4>
        </button>
      </div>
      <div>
        <button class="btn btn-info btn-lg dashboard-buttons" type="submit">
          {{-- <i class="voyager-search"></i> --}}
          <h4>Container Inquiry</h4>
        </button>
      </div>
      {{-- <div>
        <button class="btn btn-success btn-lg dashboard-buttons" type="submit">
          <i class="voyager-search"></i>
          Owooo
        </button>
      </div>
      <div>
        <button class="btn btn-warning btn-lg dashboard-buttons" type="submit">
          <i class="voyager-search"></i>
          Owooo
        </button>
      </div> --}}
    </div>
    <div class="col-lg-7 col-md-12">
      <div class="chart-container-width-basis"></div>
      <figure class="chart-container">
        <span>Containers Received in the past 12 months</span>
        <div id="chart1" class="chart"></div>
      </figure>
      <figure class="chart-container">
        <span>Damaged products in the past 12 months</span>
        <div id="chart2" class="chart"></div>
      </figure>
      <br>
      <figure class="chart-container">
        <span>Containers</span>
        <div id="chart3" class="chart"></div>
      </figure>
    </div>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.2.1/dist/echarts.min.js" integrity="sha256-EJb5T/UXVVwHY/BJ33bAFqyyzsAqdl4ZCElh3UYvaLk=" crossorigin="anonymous"></script>
<script>
  var vm = new Vue({
    el: '#dashboard',
    data:{
      option1: {
        vueChart: "",
        vueChartOtpion: {
          title:{
            // text:'Users',
          },
          xAxis: {
            type: "category",
            data: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
          },
          yAxis: {
            type: "value"
          },
          series: [
            {
              data: [100, 1000, 100, 1000, 100, 1000, 100],
              type: "line",
              smooth: true
            }
          ]
        },
      },
      option2: {
        chart2: '',
        legend: {},
        tooltip: {},
        title:{
            // text:'Containers',
          },
        dataset: {
          dimensions: ['product', '2015', '2016', '2017'],
          source: [
            { product: 'Matcha Latte', '2015': 43.3, '2016': 85.8, '2017': 93.7 },
            { product: 'Milk Tea', '2015': 83.1, '2016': 73.4, '2017': 55.1 },
            { product: 'Cheese Cocoa', '2015': 86.4, '2016': 65.2, '2017': 82.5 },
            { product: 'Walnut Brownie', '2015': 72.4, '2016': 53.9, '2017': 39.1 }
          ]
        },
        xAxis: { type: 'category' },
        yAxis: {},
        series: [{ type: 'bar' }, { type: 'bar' }, { type: 'bar' }]
      },
      option3: {
        chart: '',
        currentIndex: -1,
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        legend: {
          orient: 'vertical',
          left: 'left',
          data: [
            'Direct Access',
            'Email Marketing',
            'Affiliate Ads',
            'Video Ads',
            'Search Engines'
          ]
        },
        series: [
          {
            name: 'Access Source',
            type: 'pie',
            radius: '55%',
            center: ['50%', '60%'],
            data: [
              { value: 335, name: 'Direct Access' },
              { value: 310, name: 'Email Marketing' },
              { value: 234, name: 'Affiliate Ads' },
              { value: 135, name: 'Video Ads' },
              { value: 1548, name: 'Search Engines' }
            ],
            emphasis: {
              itemStyle: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
              }
            }
          }
        ]
      }
    },
    created() {
      this.$nextTick(() => {
        this.initChart1()
        this.initChart2()
        this.initChart3()
      })
    },
    methods: {
      initChart1() {
        this.option1.vueChart = echarts.init(document.getElementById('chart1'), null, {renderer: 'svg'})
        this.option1.vueChart.setOption(this.option1.vueChartOtpion)
        this.setIntervalData()
      },
      initChart2() {
        this.option2.chart2 = echarts.init(document.getElementById('chart2'), null, {renderer: 'svg'})
        this.option2.chart2.setOption(this.option2)
      },
      initChart3() {
        this.option3.chart = echarts.init(document.getElementById('chart3'), null, {renderer: 'svg'})
        this.option3.chart.setOption(this.option3)
        this.pieInterval()
      },
      setIntervalData(){
        setInterval(()=>{ 
         this.option1.vueChartOtpion.series[0].data= this.option1.vueChartOtpion.series[0].data.map(v=> {
           return v+v/10;
         })   
          this.option1.vueChart.setOption(this.option1.vueChartOtpion)
        },2000)
        
      },
      pieInterval() {
        setInterval(() => {
          var dataLen = this.option3.series[0].data.length
          this.option3.chart.dispatchAction({
            type: 'downplay',
            seriesIndex: 0,
            dataIndex: this.option3.currentIndex
          })
          this.option3.currentIndex = (this.option3.currentIndex + 1) % dataLen
          this.option3.chart.dispatchAction({
            type: 'highlight',
            seriesIndex: 0,
            dataIndex: this.option3.currentIndex
          })
          this.option3.chart.dispatchAction({
            type: 'showTip',
            seriesIndex: 0,
            dataIndex: this.option3.currentIndex
          })
        }, 1000)
      }
    }
  })
</script>
@stop