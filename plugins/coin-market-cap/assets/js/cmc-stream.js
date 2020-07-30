(function($) {
  'use strict';
  $(document).ready(function(){
    if ($("#cmc_coinslist").hasClass("cmc_live_updates")) {
        setupWebSocket();
    }
  });

  function setupWebSocket(){
    var $liveUpdates = $(".cmc_live_updates tbody");
    const pricesWs = new WebSocket('wss://ws.coincap.io/prices?assets=ALL');
    pricesWs.onmessage = function (msg) {
      var sdata=JSON.parse(msg.data);
      for(var indexkey in sdata) {
        if (sdata.hasOwnProperty(indexkey)) {
          var $row = $liveUpdates.find('tr[data-coin-id="' +indexkey+ '"]');
          if ($row.length>0) {
            var coinIndex=$row.attr("data-coin-id");
            var coinOldPrice=$row.attr('data-coin-old-price'); 
            var newPrice=sdata[coinIndex]; 
            
            var currency_rate = $('#cmc_usd_conversion_box option:selected').data('currency-rate');
            var currency_name = $('#cmc_usd_conversion_box option:selected').val();
            var currency_symbol = $('#cmc_usd_conversion_box option:selected').data('currency-symbol');
          
            var coinLivePrice=newPrice;
            if (currency_name == "USD") {
              var formatted_price =coinLivePrice;
            }
            else if (currency_name == "BTC") {
              if (response.coin != "BTC") {
                  var formatted_price =coinLivePrice / currency_rate
              } else {
                  formatted_price = '1.00'
              }
            } else {
              var formatted_price =coinLivePrice * currency_rate
            }
        
            if (formatted_price >= 25) {
                var priceHtml = numeral(formatted_price).format('0,0.00');
            } else if (formatted_price >= 0.50 && formatted_price < 25) {
                var priceHtml = numeral(formatted_price).format('0,0.000');
            } else if (formatted_price >= 0.01 && formatted_price < 0.50) {
                var priceHtml = numeral(formatted_price).format('0,0.0000');
            } else if (formatted_price >= 0.0001 && formatted_price < 0.01) {
                var priceHtml = numeral(formatted_price).format('0,0.00000');
            } else {
                var priceHtml = numeral(formatted_price).format('0,0.00000000');
            }

            if(parseFloat(priceHtml.replace(/,/g , '')) > parseFloat(coinOldPrice)) {
              $row.addClass("price-plus");
            } else if(parseFloat(priceHtml.replace(/,/g , ''))<parseFloat(coinOldPrice)) {
              $row.addClass("price-minus");
            } else{
              //nothing to do
            }

            $row.attr('data-coin-old-price', parseFloat(priceHtml.replace(/,/g , '')));
            $row.find('.cmc-price').html(currency_symbol + '<span class="cmc-formatted-price">' + priceHtml + '</span>');
          
          }
        }
      }/// loop

      setTimeout(function() {
        $liveUpdates.find("tr").removeClass('price-plus').removeClass('price-minus');
      },700); 
    }
  };

})(jQuery)