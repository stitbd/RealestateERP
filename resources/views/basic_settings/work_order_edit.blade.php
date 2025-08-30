@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Work Order Edit
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('update-work-order',$work_order->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            {{-- @if (session('message'))
                                <div class="alert alert-danger">
                                    {{ session('message') }}
                                </div>
                            @endif --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="date">Work Order Date<i class="text-danger">*</i></label>
                                    <input type="date" name="date" required class="form-control" value="{{$work_order->date}}">
                                </div>
                                <div class="col-md-12">
                                    <label for="supplier">Supplier<i class="text-danger">*</i></label>
                                    <select name="supplier_id" class="form-control" required>
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" @if($supplier->id == $work_order->supplier_id) selected @endif>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="type">Type<i class="text-danger">*</i></label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="Service" @if($work_order->type == 'Service') Selected @endif>Service</option>
                                        <option value="Product" @if($work_order->type == 'Product') Selected @endif>Product</option>
                                    </select>
                                </div>
                                @if($work_order->type == "Service")
                                <div class="col-lg-12" id="service">
                                    <label class="form-label">Service Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="service_name"
                                        placeholder="Service Name" value="{{$work_order->name}}">
                                </div>
                                @endif
                                @if($work_order->type == "Product")
                                <div class="col-lg-12" id="product">
                                    <label class="form-label">Product Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="product_name"
                                        placeholder="Product Name" value="{{$work_order->name}}">
                                </div>
                                @endif
                                <div class="col-lg-12">
                                    <label class="form-label">Unit Price<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="unit_price" id="unit_price"
                                        placeholder="Unit Price" required value="{{$work_order->unit_price}}">
                                </div>
                                @if($work_order->p_quantity)
                                <div class="col-lg-12" id="p_quantity">
                                    <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="p_quantity" placeholder="Quantity" value="{{$work_order->p_quantity}}">
                                </div>
                                @endif
                                @if($work_order->s_quantity)
                                <div class="col-lg-12" id="s_quantity">
                                    <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="s_quantity" value="1" readonly value="{{$work_order->s_quantity}}">
                                </div>
                                @endif
                                <div class="col-lg-12">
                                    <label class="form-label">Total Price</label>
                                    <input type="number" class="form-control" name="total_price" id="total_price"
                                        placeholder="Total Price" readonly value="{{$work_order->total_price}}">
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label">Note</label>
                                    <textarea class="form-control" name="note" id="note" placeholder="Note">{{$work_order->note}}</textarea>
                                </div>

                                    <button type="submit" class="btn btn-success float-right col-sm-3 mt-3"><i class="fa fa-check"></i>
                                        Update </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function calculatePrice() {
            var s_quantity = parseFloat($('#s_quantity input').val());
            var p_quantity = parseFloat($('#p_quantity input').val());
            // console.log(p_quantity);
            var unitPrice = parseFloat($('#unit_price').val());

            if (p_quantity) {
                var total_price = p_quantity * unitPrice;
            } else {
                var total_price = s_quantity * unitPrice;
                // console.log(total_price);
            }

            $('#total_price').val(total_price);
        }

        $('#s_quantity input, #p_quantity input, #unit_price').on('input', function() {
            calculatePrice();
        });

        $('#type').change(function() {
            var selectedtype = $(this).val();
            $.ajax({
                url: '/check-type',
                type: 'POST',
                data: {
                    type: selectedtype
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    var typeName = response.typeName;
                    if (typeName === 'Service Name') {
                        $('#service').show();
                        $('#s_quantity').show().find('input').trigger('input');
                        $('#product').hide();
                        $('#p_quantity').hide();
                    } else if (typeName === 'Product Name') {
                        $('#service').hide();
                        $('#s_quantity').hide();
                        $('#product').show();
                        $('#p_quantity').show().find('input').trigger('input');
                    } else {
                        $('#service').hide();
                        $('#product').hide();
                        $('#s_quantity').hide();
                        $('#p_quantity').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#type').change();
    });
</script>
