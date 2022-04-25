@extends('layouts.admin')

@section('title', 'Open POS')
<style>
    .order-product {
        height: 59vh;
        overflow-x: hidden;
        overflow-y: auto;
        text-align: center;
    }

    #cart {
        overflow-x: hidden;
    }

    .print {
        margin-top: 5px;
    }

</style>
@section('content')
    <div id="cart">

    </div>
    <div className="row">
        <div class="col-m d-6 col-lg-4" style="padding: 0;padding-right: 20px; display: none">
            <button type="button" class="btn btn-success btn-block print"
            data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-print"></i>&nbsp; Print</button>
            {{-- <button type="button" class="btn btn-success btn-block print" 
          data-toggle="modal" data-target="#exampleModal">
          <i class="fas fa-print"></i>&nbsp; Print</button> --}}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Amounts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Total</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="print-data">
                                {{-- @foreach ($orders->items as $item)
                                    <td>{{ config('settings.currency_symbol') }} {{ $orders->formattedTotal() }}</td>
                                @endforeach
                                @foreach ($orders->payments as $item)
                                    <td>{{ $item->amount }}</td>
                                @endforeach
                                <td>{{ config('settings.currency_symbol') }}
                                    {{ number_format($orders->total() - $orders->receivedAmount(), 2) }}</td>
                                <td>@mdo</td> --}}

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" style="justify-content: flex-start;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.print').on('click', function() {
            $.ajax({
                "url": "{{ route('print-this') }}",
                "type": "GET",
                "datatype": "json",
                success: function(data) {
                  data.items.forEach(element => {
                    // console.log(element.price);
                    $('.print-data').append('<td></dt>').text(data.formattedTotal());
                    // formattedTotal()
                  });

                },
                error: function(reject) {}
            })
        })
    </script>
@endsection
