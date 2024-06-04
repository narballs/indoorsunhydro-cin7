<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap4.css" rel="stylesheet">
</head>
<body>
    <div class="invoice">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Invoice {{$order_reference}}
                </h3> 
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cin7_invoice" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Item Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>${{ number_format($item['unit_price'] / 100, 2) }}</td>
                                <td>${{ number_format($item['total'] / 100, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right !important"><strong>Subtotal:</strong><strong>  ${{ number_format($subtotal / 100, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right !important"><strong>Tax:</strong><strong><strong>  ${{ number_format($tax / 100, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right !important"><strong>Shipment:</strong><strong>  ${{ number_format($shipment / 100, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right !important"><strong><strong>Total:</strong>  ${{ number_format($total / 100, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script>
        new DataTable('#cin7_invoice', {
            layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="fa fa-file-excel"></i> Excel',
                            className: 'btn btn-info btn-sm'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa fa-file-pdf"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            customize: function(doc) {
                                    // Apply borders to all cells
                                doc.styles.tableHeader = {
                                    fillColor: '#f2f2f2',
                                    color: 'black',
                                    alignment: 'center',
                                    bold: true,
                                    border: [true, true, true, true]
                                };
                                doc.styles.tableBodyEven = {
                                    fillColor: '#ffffff',
                                    color: 'black',
                                    alignment: 'center',
                                    border: [true, true, true, true]
                                };
                                doc.styles.tableBodyOdd = {
                                    fillColor: '#f9f9f9',
                                    color: 'black',
                                    alignment: 'center',
                                    border: [true, true, true, true]
                                };

                                // Adjust columns to have equal width
                                var table = doc.content[1].table;
                                table.widths = Array(table.body[0].length).fill('*');
                            },
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i> Print',
                            className: 'btn btn-warning btn-sm'
                        }
                    ]
                },
                topEnd: false,
                ordering: false,
                search: false,
                info: false,
                lengthChange: false,
                paging: false
            }
        });
    </script>
</body>
</html>
