<link href="{{ URL::asset('assets/libs/bootstrap-table/bootstrap-table.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ URL::asset('assets/libs/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/pdfmake/pdfmake.min.js')}}"></script>
<script type="text/javascript">
$(function() {
    var table = $('#{{$dataTableId}}').DataTable({
        processing: true,
        serverSide: true,
        search: {
            regex: true
        },
        ajax: "{{$dateTableUrl}}",
        searchable:true,
        columns: JSON.parse(`<?php echo json_encode($dateTableFields);?>`),
        order: [ [0, 'desc'] ],
        dom: 'Bfrtip',
        lengthMenu: [[25, 100, -1], [25, 100, "All"]],
        buttons: [
            {
                extend: 'csv',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                }
                // action: function ( e, dt, node, config ) {
                //     var myButton = this;
                //     dt.one( 'draw', function () {
                //     jQuery.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
                // });
                //     dt.page.len(dt.page.len()).draw();
                //     dt.page.len(100000000).draw();
                // }
            }
        ],
    });

    $('#filter-form').on('submit', function(e) {
        var obj = {};
        // var data = $(this).serialize().split("&");
        var obj = $(this).serializeObject();
        // for (var key in data) {
        //     obj[data[key].split("=")[0]] = data[key].split("=")[1];
        // }
        $.ajaxSetup({
            data: obj
        });
        table.draw();
        e.preventDefault();
    });
});
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};  
</script>
