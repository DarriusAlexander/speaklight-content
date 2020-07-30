jQuery(function() {
    
    jQuery("._crowdfundingfromdatepicker_class").datepicker();
    
    jQuery("._crowdfundingtodatepicker_class").datepicker();
    
    jQuery("._crowdfundingfromdatepicker_class").datepicker({
        changeMonth: true,
        onClose: function(selectedDate) {
            var maxDate = new Date(Date.parse(selectedDate));
            maxDate.setDate(maxDate.getDate() + 1);
            jQuery("._crowdfundingtodatepicker_class").datepicker("option", "minDate", maxDate);
        }
    });
    jQuery("._crowdfundingtodatepicker_class").datepicker({
        changeMonth: true,
        onClose: function(selectedDate) {
          //  jQuery("._crowdfundingfromdatepicker_class").datepicker("option", "maxDate", selectedDate);
        }
    });
});
