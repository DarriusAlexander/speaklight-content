(function ($) {
    'use strict';
    let CMC_REQUEST = 'main_list';
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
    var watchTitle,unwatchTitle;
 $.fn.cmcDatatable = function () {

    var $cmc_table = $(this);
    var columns = [];
    var fiatSymbol = $cmc_table.data('currency-symbol');
    var fiatCurrencyRate = $cmc_table.data('currency-rate');
    var pagination = $cmc_table.data('pagination');
        watchTitle = $cmc_table.data('watch-title');
        unwatchTitle = $cmc_table.data('unwatch-title');
    var totalCoins = $cmc_table.data('total-coins');
    var fiatCurrency = $cmc_table.data('old-currency');
    var preloaderPath = $cmc_table.find('thead').data('preloader');
    var prevtext= $cmc_table.data("prev-coins");
    var nexttext = $cmc_table.data("next-coins");
    var is_milbil_enable = $cmc_table.data('number-formating');
    var zeroRecords = $cmc_table.data("zero-records");
    var linksTab = $cmc_table.data("link-in-newtab");
    var loadingLbl = $cmc_table.data("loadinglbl");
    var defaultLogo= $cmc_table.parents('#cryptocurency-market-cap-wrapper').data('default-logo');
    $cmc_table.find('thead th').each(function (index) {
        var index = $(this).data('index');
        var thisTH=$(this);
        var classes = $(this).data('classes');

        columns.push({
            data: index,
            name: index,
            render: function (data, type, row, meta) {
                
                if (meta.settings.json === undefined) { return data; }
                switch (index) {
                    case 'rank':
                            
                        if( localStorage.getItem('cmc_watch_list') !== null ){
                            let arr = cmc_get_watch_list();
                            let coin_exist =  arr.findIndex(ar=>{ return ar == row.coin_id });
                            if( coin_exist>-1 ){
                                var html ='<div data-coin-id="'+row.coin_id+'" class="btn_cmc_watch_list cmc_onwatch_list cmc_icon-star" title="'+unwatchTitle+'"></div>';
                            }else{
                                var html ='<div data-coin-id="'+row.coin_id+'" class="btn_cmc_watch_list cmc_icon-star-empty" title="'+watchTitle+'"></div>';
                            }
                        }else{
                            var html ='<div data-coin-id="'+row.coin_id+'" class="btn_cmc_watch_list cmc_icon-star-empty"></div>';
                        }
                        return html+' '+data ;
                        break;
                    case 'name':
                        var singleUrl = thisTH.data('single-url');
                        var urlType = thisTH.data('url-type');
                        var tabLink = thisTH.data('link-in-newtab');
                        var link_target='_self';
                        if(parseInt(tabLink)==1){
                            var link_target='_blank';
                        }

                        if (urlType=="default"){
                            var url = singleUrl + '/' + row.symbol + '/' + row.coin_id+ '/';
                        }else{
                            var url = singleUrl + '/' + row.symbol + '/' + row.coin_id + '/' + fiatCurrency+ '/';
                        }
                       
                        var html = '<div class="'+classes+'"><a target="'+link_target+'" title ="'+data+'" href = "'+url+'" style = "position: relative; overflow: hidden;" ><span class="cmc_coin_logo">                             <img style="width:32px;" id="'+data+'"  src="'+row.logo+'"  onerror="this.src=\''+defaultLogo+'\';"></span>                             <span class="cmc_coin_symbol">('+row.symbol+')</span>                             <br>                             <span class="cmc_coin_name cmc-desktop">'+data+'</span>                             </a></div>';
                        return html;
                    case 'price':
                        if (typeof data !== 'undefined' && data !=null){
                        if (data >= 25) {
                            var formatedVal = numeral(data).format('0,0.00');
                        } else if (data >= 0.50 && data < 25) {
                            var formatedVal = numeral(data).format('0,0.000');
                        } else if (data >= 0.01 && data < 0.50) {
                            var formatedVal = numeral(data).format('0,0.0000');
                        } else if (data >= 0.0001 && data < 0.01) {
                            var formatedVal = numeral(data).format('0,0.00000');
                        } else {
                            var formatedVal = numeral(data).format('0,0.00000000');
                        }
                            return html = '<div data-val="'+row.usd_price+'" class="'+classes+'"><span class="cmc-formatted-price">'+fiatSymbol + formatedVal+'</span></div>';
                     }else{
                            return html = '<div class="'+classes+'>?</div>';
                       }
                        break;
                    case 'percent_change_24h':
                        if (typeof data !== 'undefined' && data != null) {
                        var changesCls = "up";
                            var wrpchangesCls = "cmc-up";
                            if (typeof Math.sign === 'undefined') { Math.sign = function (x) { return x > 0 ? 1 : x < 0 ? -1 : x; } }
                        if (Math.sign(data) == -1) {
                            var changesCls = "down";
                            var wrpchangesCls = "cmc-down";
                        }
                        var html = '<div class="'+classes + ' ' + wrpchangesCls+'"><span class="changes '+changesCls+'"><i class="cmc_icon-'+changesCls+'" aria-hidden="true"></i>'+data+'%</span></div>';
                        return html;
                    }else{
                          return html='<div class="'+classes+'">?</span></div>';
                    }
                        break;
                    case 'market_cap':
                        var formatedVal = data;

                        if( typeof is_milbil_enable != 'undefined' && is_milbil_enable == '1' ){
                            formatedVal = numeral(data).format('(0.00 a)');
                        }else{
                            formatedVal = formatedVal.toString();
                        }
                        if (typeof data !== 'undefined' && data != null) {
                            return html = '<div data-val="'+row.usd_market_cap+'" class="'+classes+'">'+fiatSymbol + formatedVal.toUpperCase()+'</div>';
                        }else{
                            return html = '<div class="'+classes+'">?</span></div>'; 
                        }
                        break;
                    case 'volume':
                        var formatedVal = data;
                        
                        if( typeof is_milbil_enable != 'undefined' && is_milbil_enable == '1'  ){
                            formatedVal = numeral(data).format('(0.00 a)');
                        }else{
                            formatedVal = formatedVal.toString();
                        }
                        if (typeof data !== 'undefined' && data != null) {
                            return html = '<div data-val="'+row.usd_volume+'" class="'+classes+'">'+fiatSymbol + formatedVal.toUpperCase()+'</div>';
                        } else {
                            return html = '<div class="'+classes+'">?</span></div>';
                        }
                        break;
                    case 'supply':
                        if (typeof data !== 'undefined' && data != null) {
                        var formatedVal = numeral(data).format('(0.00 a)');
                        return html ='<div class="'+classes+'">'+formatedVal.toUpperCase() + ' ' + row.symbol+'</div>';
                         } else {
                            return html = '<div class="'+classes+'">?</span></div>';
                        }
                        break;
                    case 'weekly_chart':
                        var chart_data='';
                        var gChart='';
                        //green
                        var dynamic_color = "data-bg-color='#90EE90' data-color='#006400'";
                        var chart_cls='weekly_up';
                        if (row.weekly_chart =="false"){
                            chart_data ="undefined";
                            var gChart ='false';
                        }else{
                            chart_data= row.weekly_chart;
                           var data_array= JSON.parse(chart_data);
                            var first_ele=data_array[0];
                            var last_ele=data_array[data_array.length-1];
                            var gChart = 'true';
                        if (parseFloat(last_ele)>parseFloat(first_ele)) {
                          //green
                            var dynamic_color = "data-bg-color='#90EE90' data-color='#006400'";
                            var chart_cls='weekly_up';
                        }else{
                            //red 
                            var dynamic_color = "data-bg-color='#ff9999' data-color='#ff0000'";
                            var chart_cls = 'weekly_down';
                        }
                  }
           
                   return html = '<div class="'+classes + " " + chart_cls+'"><div class="cmc_spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div><div style="width:100%;height:100%;" class="ccpw-chart-container"><canvas  data-content='+chart_data+   ' '+   dynamic_color+  'data-coin-symbol="'+row.symbol+'"   data-create-chart="'+gChart+'"  data-cache="true"  class="cmc-sparkline-charts"  id="small-coinchart" width="168" height="50"  style="display: block; height: 40px;  width: 135px;"></canvas></div>';
                 
                   break;                   

                }
            },
            "createdCell": function (td, cellData, rowData, row, col) {
                if(col!=7){
                $(td).attr('data-sort', cellData);
                }
            }
        });
    });
        $cmc_table.DataTable({
            "deferRender": true,
            "serverSide": true,
            "ajax": {
                "url": data_object.ajax_url,
                "type": "POST",
                "dataType": "JSON",
                "data": function (d) {
                    d.nonce=data_object.nonce;
                    d.action = "dt_get_coins_list";
                    d.currency =fiatCurrency;
                    if( CMC_REQUEST == 'watch_list' ){
                        d.coinID = cmc_get_watch_list();
                        d.totalCoins = d.coinID.length ;
                    }else if( CMC_REQUEST == 'main_list' ){
                        d.totalCoins = totalCoins;
                    }
                    d.currencyRate = fiatCurrencyRate;
                
                    // etc
                },
              
                "error": function (xhr, error, thrown) {
                  //  alert('Something wrong with Server');
                }
            },
            "ordering": false,
            "destroy": true,
            "searching": false,
            "pageLength":pagination,
            "columns": columns,
            "lengthChange": false,
            "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            "pagingType": "simple",
            "processing": true,
            "language": {
                "processing":loadingLbl,
                "loadingRecords":loadingLbl,
                "paginate": {
                    "next":  nexttext,
                    "previous":prevtext
                },
            },
            "zeroRecords":zeroRecords,
            "emptyTable":zeroRecords,
            "renderer": {
                "header": "bootstrap",
            },
           "drawCallback": function (settings) {
                $cmc_table.find(".cmc-sparkline-charts").each(function (index) {
                    $(this).cmcgenerateSmallChart();
                });
                $cmc_table.tableHeadFixer({
                    // fix table header
                    head: true,
                    // fix table footer
                    foot: false,
                    left:2,
                    right:false,
                    'z-index':1
                    }); 
                    
            },
            
            "createdRow": function (row, data, dataIndex) {
                 $(row).attr('data-coin-id',data.coin_id);
                 $(row).attr('data-coin-old-price', data.price);
                 $(row).attr('data-coin-symbol', data.symbol);
            },
          
        });
    
    }

    $("#cmc_coinslist").cmcDatatable();
 
    new Tablesort(document.getElementById('cmc_coinslist'), {
        descending: true
    });
        // var content = $("#cmc_search_html").html();
        // var search_data = JSON.parse(content);

        var source;
        var cmc_search_cache = lscache.get('cmc_coin_search')
        if(  cmc_search_cache == null ){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: data_object.ajax_url,
                data: {'action':'cmc_ajax_search'},
                async: !0,
                beforeSend: function() {
                    // hide search-field before 
                    $('.typeahead.tt-input').attr('disabled','disabled');
                },
                success: function (response) {
                    lscache.set('cmc_coin_search',response, 60 * 24 );
                            source = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace("name"),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                local:response
                            });
                            $('.typeahead.tt-input').removeAttr('disabled');
                            cmc_init_search()
                }
            })
        }else{
            source = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace("name"),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: cmc_search_cache
            });
            cmc_init_search()
        }
       

        function cmc_init_search(){
            source.initialize();
            // var noresult = $("#custom-templates").data('no-result');
            $('#custom-templates .typeahead').typeahead( null, {
                name: 'matched-links',
                displayKey: 'name',
                source: source.ttAdapter(),
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'unable to find any results',
                        '</div>'
                    ].join('\n'),
                    // suggestion: Handlebars.compile(document.getElementById("search_temp").innerHTML)
                    header: '<h6 class="league-name">Result</h6>',
                    suggestion: function( coin ){
                        var html = '<div class="cmc-search-sugestions"><a href="'+ coin.link+'" onclick="'+ coin.link+'"">';
                        if( coin.logo.local ){
                            html +='<img src="' + coin.logo.logo+'"></img>';
                        }else{
                            html +='<img src="' + coin.logo.logo + '"></img>';
                        }
                        html += coin.name+'</a></div>';
                        return html;
                    }
                }
            });
        }


        $(".cmc_conversions").on("change", function () {
            var selected_curr = $('option:selected',this).val();
            var currencySymbol = $('option:selected', this).data('currency-symbol');
            var currencyRate = $('option:selected', this).data('currency-rate');
            
            $("#cmc_coinslist").find('tbody tr').each(function (index) {
              var  priceTD = $(this).find('.cmc-price');
              var  volTD = $(this).find('.cmc-vol');
              var  capTD = $(this).find('.cmc-market-cap');
              var is_milbil_enable = $(this).parents("#cmc_coinslist").data('number-formating');

                var coinPrice = priceTD.data('val');
                var cmcVol = volTD.data('val');
                var cmcMarketCap = capTD.data('val');
               
                if (selected_curr=="BTC"){
                    var convertedPrice = coinPrice / currencyRate;
                    var convertedVol = cmcVol / currencyRate;
                    var convertedCap = cmcMarketCap / currencyRate;

                     var formatedPrice = numeral(convertedPrice).format('0,0.0000000');
                    var formatedVol = numeral(convertedVol).format('0,0');
                    var formatedCap = numeral(convertedCap).format('0,0');
         
                    }else{
                    var convertedPrice = coinPrice * currencyRate;
                    var convertedVol = cmcVol * currencyRate;
                    var convertedCap = cmcMarketCap * currencyRate;
                    if (convertedPrice < 0.50) {
                        var formatedPrice = numeral(convertedPrice).format('0,0.000000');
                    } else {
                        var formatedPrice = numeral(convertedPrice).format('0,0.00');
                    }
                        if( typeof is_milbil_enable != 'undefined' && is_milbil_enable == '1'  ){
                            var formatedVol = numeral(convertedVol).format('(0.00 a)').toUpperCase();
                            var formatedCap = numeral(convertedCap).format('(0.00 a)').toUpperCase();
                        }else{
                            var formatedVol = convertedVol;
                            var formatedCap = convertedCap;
                        }
                    }
                priceTD.html(currencySymbol+'<span class="cmc-formatted-price">' + formatedPrice + '</span>');
                capTD.html(currencySymbol+formatedCap);
                volTD.html(currencySymbol+formatedVol);
            });   
        });

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
                    THIS.attr( 'title', watchTitle );
                }else{
                    arr.push(ID);
                    THIS.removeClass('cmc_icon-star-empty').addClass('cmc_onwatch_list cmc_icon-star');
                    THIS.attr( 'title', unwatchTitle );
                }
            }else{
                arr.push(ID);
                THIS.removeClass('cmc_icon-star-empty').addClass('cmc_onwatch_list cmc_icon-star');
                THIS.attr( 'title', unwatchTitle );
            }
            if( arr.length == 0 ){
                localStorage.removeItem('cmc_watch_list');
            }else{
                localStorage.setItem('cmc_watch_list',arr );
            }
        });

        jQuery(document).on('click', '#cmc_toggel_fav', function(event){
            event.preventDefault();
            var THIS = $(this);
            if( THIS.hasClass('cmc_icon-star-empty') ){
                THIS.removeClass('cmc_icon-star-empty').addClass('cmc_icon-star');
                CMC_REQUEST = 'watch_list';
                $("#cmc_coinslist").cmcDatatable();
            }else{
                CMC_REQUEST = 'main_list';
                THIS.removeClass('cmc_icon-star').addClass('cmc_icon-star-empty');
                $("#cmc_coinslist").cmcDatatable();
            }
        });

    })(jQuery);