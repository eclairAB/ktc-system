@extends('voyager::master')
@section('content')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"> --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  #dashboard {
    padding: 0;
    margin: 1vw 2vw;
  }

  #dashboard .chart {
    /*border: solid;*/
  }

  #chart1, #chart2, #chart3 {
    width: 400px;
    height:300px;
  }

  figure.chart-container {
    display: inline-block;
    position: relative;
    margin: .7em .7vw;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 8px;
    box-shadow: 0 0 45px rgb(0 0 0 / 20%);
    padding: 1.5em 2em;
    /*min-width: calc(40vw + 4em);*/
  }

  .app-container {
    min-height: 100%;
    position: relative;
    background: #f9f9f9;
    padding-bottom: 30px;
    width: fit-content;
  }

  @media only screen and (max-width: 1082px) {
    #chart1, #chart2, #chart3 {
      width: 62vw;
      height:300px;
    }
  }
</style>
<body>
  Hello {{ $name }}!

  <div id="dashboard" class="clearfix container-fluid row">
    <figure class="chart-container chart">
      <div id="chart1"></div>
    </figure>
    <figure class="chart-container chart">
      <div id="chart2"></div>
    </figure>
    <br>
    <figure class="chart-container chart">
      <div id="chart3"></div>
    </figure>
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
        /*window.onresize = function() {
          this.option1.vueChart.resize()
        }*/
      },
      initChart2() {
        this.option2.chart2 = echarts.init(document.getElementById('chart2'), null, {renderer: 'svg'})
        this.option2.chart2.setOption(this.option2)
        /*window.onresize = function() {
          this.option2.chart2.resize()
        }*/
      },
      initChart3() {
        this.option3.chart = echarts.init(document.getElementById('chart3'), null, {renderer: 'svg'})
        this.option3.chart.setOption(this.option3)
        this.pieInterval()
        /*window.onresize = function() {
          this.option3.chart.resize()
        }*/
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