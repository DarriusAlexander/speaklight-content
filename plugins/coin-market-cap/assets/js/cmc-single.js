(function ($) {

  'use strict';
    function cmc_get_watch_list(){
        if( localStorage.getItem('cmc_watch_list') === null ){
            return false;
        }
        let oldArr = localStorage.getItem('cmc_watch_list').split(',');
        let arr = new Array();
        if( oldArr.length !== false ){
            for(const el of oldArr ){
                arr.push(el);
            }
        }
        return arr;
    }

    let arr = cmc_get_watch_list();
    let el = $('.btn_cmc_watch_list');
    let ID = el.attr('data-coin-id');
    let coin_exist = -1;
    if( arr != false ){
        coin_exist =  arr.findIndex(ar=>{ return ar == ID });
    }
    if( coin_exist > -1 ){
        el.removeClass('cmc_icon-star-empty').addClass('cmc_onwatch_list cmc_icon-star');
        el.text( el.attr('data-unwatch-text') );
        el.attr( 'title', el.attr('data-unwatch-title') );
    }else{
        el.removeClass('cmc_onwatch_list cmc_icon-star').addClass('cmc_icon-star-empty');
        el.text( el.attr('data-watch-text') );
        el.attr( 'title', el.attr('data-watch-title') );
    }

  var chartLoaded = null;
  window.mobilecheck = function () {
      var check = !1;
      (function (a) {
          if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = !0
      })(navigator.userAgent || navigator.vendor || window.opera);
      return check
  };
  $.fn.cmcGernateChart = function () {
      var coinId = $(this).data("coin-id");
      var chart_color = $(this).data("chart-color");
      var coinperiod = $(this).data("coin-period");
      var chartfrom = $(this).data("chart-from");
      var chartto = $(this).data("chart-to");
      var chartzoom = $(this).data("chart-zoom");
      var pricelbl = $(this).data("chart-price");
      var volumelbl = $(this).data("chart-volume");
      var fiatCurrencyRates = $(this).data("fiat-c-rate");
      var currentPrice = $(this).data("coin-current-price");
      var currentVol = $(this).data("coin-current-vol");
      var price_section = $(this).find(".CCP-" + coinId);
      var milliseconds = (new Date).getTime();
      if (currentPrice < 0.50) {
          var formatedPrice = numeral(currentPrice).format('00.000000')
      } else {
          var formatedPrice = numeral(currentPrice).format('00.00')
      }
      var formatedVol = numeral(currentVol).format('00.00');
      var currentPriceIndex = {
          date: milliseconds,
          value: formatedPrice,
          volume: currentVol
      };
      var priceData = [];
      var volData = [];
      $(this).find(".cmc-preloader").show();
      var mainThis = $(this);
      var price_section = $(this).find(".CCP-" + coinId);
      var mobile = window.mobilecheck();
      var marginLeft = 90;
      if (mobile) {
          marginLeft = 0
      }

      var ChartCache   = coinId+'-historicalData';
      var BrowserCache = lscache.get(ChartCache);
      if(BrowserCache){
          mainThis.find("#cmc-chart-preloader").hide();
          gernateChart(coinId, BrowserCache, chart_color, chartfrom, chartto, chartzoom, pricelbl, volumelbl)
      }else{
            var request_data = {
                'action': 'cmc_coin_chart',
                'symbol': coinId,
                'type': 'chart',
                'nonce':data_object.nonce
            };

            jQuery.ajax({
                type: "get",
                dataType: "json",
                url: data_object.ajax_url,
                data: request_data,
                async: !0,
                success: function (response) {
                    if (response.status == "success") {
                        if (response.data && response.data != null) {
                            var historicalData = response.data;
                            var lastIndex = historicalData[historicalData.length - 1];
                            currentPriceIndex.volume = lastIndex.volume;
                            historicalData.push(currentPriceIndex);
                            lscache.set(ChartCache,historicalData,60);
                            mainThis.find("#cmc-chart-preloader").hide();
                            gernateChart(coinId, historicalData, chart_color, chartfrom, chartto, chartzoom, pricelbl, volumelbl)
                        } else {
                            mainThis.find("#cmc-chart-preloader").hide();
                            mainThis.find("#cmc-no-data").show();
                            mainThis.css('height', 'auto')
                        }
                    }
                }
            })
      }
      return true;
  }
  var gernateChart = function (coinId, coinData, color, chartfrom, chartto, chartzoom, pricelbl, volumelbl) {
      var mobile = window.mobilecheck();
      var marginLeft = 90;
      if (mobile) {
          marginLeft = 0
      }
      var chart = AmCharts.makeChart('CMC-CHART-' + coinId, {
          "type": "stock",
          "theme": "light",
          "hideCredits": !0,
          "categoryAxesSettings": {
              "gridColor": "#eee",
              "gridAlpha": 1,
              "minPeriod": "mm"
          },
          "panelsSettings": {
              "plotAreaFillColors": "#f9f9f9",
              "plotAreaFillAlphas": 0.8,
              "marginLeft": marginLeft,
              "marginTop": 5,
              "marginBottom": 5
          },
          "valueAxesSettings": {
              "gridColor": "#eee",
              "gridAlpha": 1,
              "inside": mobile,
              "showLastLabel": !0
          },
          "dataSets": [{
              "title": "USD",
              "color": color,
              "fieldMappings": [{
                  "fromField": "value",
                  "toField": "value"
              }, {
                  "fromField": "volume",
                  "toField": "volume"
              }],
              "dataProvider": coinData,
              "categoryField": "date"
          }],
          "panels": [{
              "showCategoryAxis": !0,
              "title": pricelbl,
              "percentHeight": 70,
              "stockGraphs": [{
                  "id": "g1",
                  "valueField": "value",
                  "lineThickness": 2,
                  "bullet": "round",
                  "bulletSize": 5,
                  "fillAlphas": 0.1,
                  "comparable": !0,
                  "compareField": "value",
                  "balloonText": "[[title]]:<b>[[value]]</b>",
                  "compareGraphBalloonText": "[[title]]:<b>[[value]]</b>"
              }],
              "stockLegend": {
                  "periodValueTextComparing": "[[percents.value.close]]%",
                  "periodValueTextRegular": "[[value.close]]"
              },
              "allLabels": [{
                  "x": 200,
                  "y": 115,
                  "text": "",
                  "align": "center",
                  "size": 16
              }],
              "drawingIconsEnabled": !1
          }, {
              "title": volumelbl,
              "percentHeight": 30,
              "stockGraphs": [{
                  "valueField": "volume",
                  "type": "column",
                  "showBalloon": !1,
                  "cornerRadiusTop": 2,
                  "fillAlphas": 1
              }],
              "stockLegend": {
                  "periodValueTextRegular": "[[value.close]]"
              },
          }],
          "chartScrollbarSettings": {
              "graph": "g1",
              "usePeriod": "10mm",
              "position": "bottom",
              "backgroundColor": "#555",
              "graphFillColor": "#333",
              "graphFillAlpha": 0.8,
              "gridColor": "#666",
              "selectedBackgroundColor": "#888",
              "selectedGraphFillColor": "#111"
          },
          "chartCursorSettings": {
              "valueBalloonsEnabled": !0,
              "fullWidth": !0,
              "cursorAlpha": 0.1,
              "valueLineBalloonEnabled": !0,
              "valueLineEnabled": !0,
              "valueLineAlpha": 0.5
          },
          "periodSelector": {
              "position": "top",
              "periodsText": chartzoom,
              "fromText": chartfrom,
              "toText": chartto,
              "periods": [{
                  "period": "DD",
                  "count": 1,
                  "label": "1D"
              }, {
                  "period": "DD",
                  "count": 7,
                  "label": "7D"
              }, {
                  "period": "MM",
                  "selected": !0,
                  "count": 1,
                  "label": "1M"
              }, {
                  "period": "MM",
                  "count": 3,
                  "label": "3M"
              }, {
                  "period": "MM",
                  "count": 6,
                  "label": "6M"
              }, {
                  "period": "MAX",
                  "label": "1Y"
              }]
          },
          "export": {
              "enabled": !1,
              "position": "top-right"
          }
      })
  }
  $.fn.gernateTable = function () {
      var hColumns = [];
      var fiatSymbol = $(this).data('currency-symbol');
      var fiatPrice = $(this).data("fiat-currency-price");
      var is_milbil_enable = $(this).data('number-formating');
      var zeroRecords = $(this).data("no-data-lbl");
      var thisTbl = $(this);
      var perPage = $(this).data("per-page");
      $(this).find('thead th').each(function (index) {
          var index = $(this).data('index');
          var thisTH = $(this);
          var classes = $(this).data('classes');
          hColumns.push({
              data: index,
              name: index,
              render: function (data, type, row, meta) {
                  if (meta.settings.json === undefined) {
                      return data
                  }
                  if (type === 'display') {
                      switch (index) {
                          case 'date':
                              var formateddate = timeStamp(data);
                              var html = '<span style="display:none">"+data+"</span><span class="raw_values" style="display:none">"${data}"</span><div class="'+classes+'">'+formateddate+'</div>';
                              return html;
                              break;
                          case 'value':
                              if (data < 0.50) {
                                  var formatedVal = numeral(data).format('0,0.000000')
                              } else {
                                  var formatedVal = numeral(data).format('0,0.00')
                              }
                              var html = '<span class="raw_values" style="display:none">"+data+"</span><div class="'+classes+'"> <span class="cmc-formatted-price">'+fiatSymbol + formatedVal+'</span>  </div>';
                              return html;
                              break;
                          case 'market_cap':
                              var formatedVal = data;
                              if (typeof is_milbil_enable != 'undefined' && is_milbil_enable == '1') {
                                  formatedVal = numeral(data).format('(0.00 a)')
                              } else {
                                  formatedVal = formatedVal.toString()
                              }
                              var html = '<span class="raw_values" style="display:none">+data+</span><div class="'+classes+'"> '+fiatSymbol + formatedVal.toUpperCase()+'</div>';
                              return html;
                              break;
                          case 'volume':
                              var formatedVal = data;
                              if (typeof is_milbil_enable != 'undefined' && is_milbil_enable == '1') {
                                  formatedVal = numeral(data).format('(0.00 a)')
                              } else {
                                  formatedVal = formatedVal.toString()
                              }
                              var html = '<span class="raw_values" style="display:none">+data+</span><div class="'+classes+'">'+fiatSymbol + formatedVal.toUpperCase() +'</div>';
                              return html;
                              break
                      }
                  }
                  return data
              },
          })
      });
      var showtxt = $(this).data("show-entries");
      var coin_symbol = $(this).data("coin-id");
      var fiat_price = $(this).data("fiat-currency-price");
      var showprev = $(this).data("prev");
      var shownext = $(this).data("next");
      $(this).DataTable({
          searching: !1,
          pageLength: perPage,
          responsive: !0,
          "order": [
              [0, "desc"]
          ],
          "pagingType": "simple",
          "processing": !0,
          "loadingRecords": "Loading...",
          "language": {
              "paginate": {
                  "next": shownext,
                  "previous": showprev,
              },
              "lengthMenu": showtxt
          },
          "zeroRecords": zeroRecords,
          "emptyTable": zeroRecords,
          columns: hColumns,
          "ajax": {
              "url": data_object.ajax_url,
              "type": "GET",
              "dataType": "JSON",
              "async": !0,
              "data": function (d) {
                  d.action = "cmc_coin_chart",
                   d.symbol = coin_symbol,
                    d.type = 'table',
                   d.nonce=data_object.nonce
              },
              "error": function (xhr, error, thrown) {
                 // alert('Something wrong with Server')
              }
          },
          "drawCallback": function (settings) {
              thisTbl.tableHeadFixer({
                  head: !1,
                  foot: !1,
                  left: 1,
                  right: !1,
                  'z-index': 1
              })
          },
      })
  }
  $.fn.cmcLiveUpdates = function () {
      var thisIndex = $(this);
      var currency_name = thisIndex.data("currency-name");
      var currency_symbol = thisIndex.data("currency-symbol");
      var PriceIndex =thisIndex.find(".cmc_coin_price");
      var currency_rate =thisIndex.data("currency-rate");
      var coin_id =thisIndex.data("coin-id");
   
      const pricesWs = new WebSocket('wss://ws.coincap.io/prices?assets='+coin_id);
    pricesWs.onmessage = function (msg) {
        var sdata=JSON.parse(msg.data);
        var oldPrice = parseFloat( thisIndex.attr('data-coin-price'));
   
       if(sdata[coin_id]!==undefined){
       var newPrice=parseFloat(sdata[coin_id]);
        
            if (currency_name == "USD") {
                var formatted_price = newPrice;
            } else if (currency_name == "BTC") {
                if (response.coin != "BTC") {
                    var formatted_price = newPrice / currency_rate;
                } else {
                    formatted_price = '1.00'
                }
            } else {
                var formatted_price =newPrice * currency_rate;
            }
              if (formatted_price < 0.50) {
                  var priceHtml = numeral(formatted_price).format('0,0.000000')
              } else {
                  var priceHtml = numeral(formatted_price).format('0,0.00')
              }

              if(parseFloat(priceHtml.replace(/,/g , '')) > parseFloat(oldPrice)) {
                thisIndex.addClass("price-plus");
              } else if(parseFloat(priceHtml.replace(/,/g , ''))<parseFloat(oldPrice)) {
                thisIndex.addClass("price-minus");
              } else{
                //nothing to do
              }

              PriceIndex.html(currency_symbol + '<span class="cmc-formatted-price">' + priceHtml + '</span>');
              thisIndex.attr('data-coin-price', priceHtml.replace(/,/g,''));
              setTimeout(function () {
                  thisIndex.removeClass('price-minus').removeClass('price-plus');
              }, 1500);

        }
      };
  }
  $(".cmc_live_updates").cmcLiveUpdates();

  function timeStamp(timestamp) {
      var now = new Date(timestamp);
      var date = [now.getDate(), now.getMonth() + 1, now.getFullYear()];
      var time = [now.getHours(), now.getMinutes(), now.getSeconds()];
      var suffix = (time[0] < 12) ? "AM" : "PM";
      time[0] = (time[0] < 12) ? time[0] : time[0] - 12;
      time[0] = time[0] || 12;
      for (var i = 1; i < 3; i++) {
          if (time[i] < 10) {
              time[i] = "0" + time[i]
          }
      }
      return date.join("/")
  }


  
jQuery(document).ready(function($){

    if( $('.cmc-tabsBtn').length == 0 ){
        chartLoaded = $(".cmc-chart").cmcGernateChart();
        $("#cmc_historical_tbl").gernateTable();
    }else{
        if( $(".cmc-chart").is(":visible") != false ){
            chartLoaded = $(".cmc-chart").cmcGernateChart();
        }
        if( $("#cmc_historical_tbl").is(":visible") != false ){
            $("#cmc_historical_tbl").gernateTable();
        }
    }
    
});

  $('.cmc-tabsBtn').on('click', function(ev){
        ev.preventDefault();
        let link = jQuery( this ).attr('data-id');

        switch(link){
            case '#cmc-container-chart':
                // make sure the chart does not generate again and again
                if( chartLoaded == null ){
                    chartLoaded  =  $('.cmc-chart').cmcGernateChart();
                    console.log(chartLoaded);
                }
            break;
            case '#cmc-container-history-data':
                // Do not generate if already generated
                if( ! $.fn.dataTable.isDataTable( '#cmc_historical_tbl' ) ){
                    $("#cmc_historical_tbl").gernateTable();
                }
            break;
            case '#cmc-container-exchanges':
                // Do not generate if already generated
                if( ! $.fn.dataTable.isDataTable( '#celp_coin_exchanges' ) ){
                    jQuery("#celp_coin_exchanges").celpCoinExchanges();
                }
            break;
            case '#cmc-container-facebook-comments':
                if( !jQuery('#cmc-container-facebook-comments .fb-comments').hasClass('fb_iframe_widget ') ){
                    let app_id = jQuery('#cmc-container-facebook-comments .fb-comments').attr('data-connect-id');
                    var js = document.createElement('script');
                    js.async = true;
                    js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=' + app_id + '&autoLogAppEvents=1';
                    document.body.appendChild(js);
                    
                    window.setTimeout(function(){
                        $('.cmc-comment-preloader').fadeOut('fast');}
                    ,1000);
                }
            break;
        }
        $('.cmc-tabsBtn').removeClass('active');
        $('.cmc-data-container').removeClass('active')
        $(this).addClass('active');
        $(link).addClass('active');
  })

    jQuery(document).on('click','.btn_cmc_watch_list',function(evt){
        evt.preventDefault();
        let THIS = jQuery(this);
        let ID = jQuery(THIS).attr('data-coin-id');
        var arr = new Array();            
        if( localStorage.getItem('cmc_watch_list')!==null && localStorage.getItem('cmc_watch_list')!="" ){
            arr = cmc_get_watch_list();
            let coin_exist =  arr.findIndex(ar=>{ return ar == ID });
            if( coin_exist != -1 ){
                arr.splice(coin_exist,1);
                THIS.removeClass('cmc_onwatch_list cmc_icon-star').addClass('cmc_icon-star-empty');
                THIS.text( THIS.attr('data-watch-text') );
                THIS.attr( 'title', THIS.attr('data-watch-title') );
            }else{
                arr.push(ID);
                THIS.removeClass('cmc_icon-star-empty').addClass('cmc_onwatch_list cmc_icon-star');
                THIS.text( THIS.attr('data-unwatch-text') );
                THIS.attr( 'title', THIS.attr('data-unwatch-title') );
            }
        }else{
            arr.push(ID);
            THIS.removeClass('cmc_icon-star-empty').addClass('cmc_onwatch_list cmc_icon-star');
            THIS.text( THIS.attr('data-unwatch-text') );
            THIS.attr( 'title', THIS.attr('data-unwatch-title') );
        }
        if( arr.length == 0 ){
            localStorage.removeItem('cmc_watch_list');
        }else{
            localStorage.setItem('cmc_watch_list',arr );
        }
    });  


})(jQuery)