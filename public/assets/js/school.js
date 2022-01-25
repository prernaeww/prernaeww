$(function() {
    $('.range_datepicker').flatpickr({
      mode: "range",
      minDate: "today"
    });
});

$(document).on('click','.remove-btn',function(){
        $(this).closest(".row").remove();
    });

    var count = 1;
    $(document).on('click','#holiday_button',function(){

        count = count + 1;

        var variant_row = 
            '<div class="row">'+
                '<div class="col-lg-10">'+
                    '<div class="form-group">'+
                        '<input type="text" name="range_datepicker[]" required class="form-control dates range_datepicker'+count+'" placeholder="2018-10-03 to 2018-10-10">'+
                    '</div>'+
               ' </div>'+
                '<div class="col-lg-2">'+
                    '<label class="btn btn-danger waves-effect waves-light remove-btn remove" title="Remove"><i class="fa fa-times"></i></label>'+
                '</div>'+
            '</div>';

        $('#holidays').append(variant_row);

        $('.range_datepicker'+count).flatpickr({
            mode: "range",
            minDate: "today"
        });
        
    });

    $(document).on('submit','form',function(event){
        var return_val = true;
        var splashArray = new Array();
        $( ".dates" ).each(function( index ) {
            var date = $(this).val();
            if(date.indexOf(' to ') !== -1){
                // console.log(date);
                var items = date.split(' to ');
                var start = moment(items[0]);
                var end = moment( items[1]);
                var list = [];
                for (var current = start; current <= end; current.add(1, 'd')) {
                    list.push(current.format("YYYY-MM-DD"))
                }
                splashArray = splashArray.concat(list);
            }else{
                splashArray.push(date);
            }
        });
        sortedArray = splashArray.sort((a,b) => moment(a).valueOf() - moment(b).valueOf());
        var out = true;
        var dates = [];
        $.each(sortedArray, function (key, value) {
        if($.inArray(value, dates) === -1) {
                dates.push(value);
            }else{
                Notiflix.Notify.Warning(value+' is already selected!');
                out = false;
            }
        });
        if (!out) { 
            event.preventDefault(); 
            return false;
        }
        var stringdates = dates.toString();
        $("#dates").val(stringdates);
    });