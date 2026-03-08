@extends('layouts.master')

@section('title', __('Market Place Calculation'))

@section('content')
<div class="card mt-5">
    <div class="card-header">
        <h3 class="card-title">{{ __('Market Place Calculation') }}</h3>
    </div>
    <div class="card-body">
        <form id="calculationForm">
            @csrf
            <!-- STAGE 1: BASIC COSTING -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('MRP.:') }}</label>
                        <input type="number" step="0.01" class="form-control calc-trigger" name="mrp" id="mrp" placeholder="Enter MRP">
                    </div>
                </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Discount %:') }}</label>
                        <input type="number" step="0.01" class="form-control calc-trigger" name="discount" id="discount" value="0" >
                    </div>
                </div>
                <div class="col-md-3">
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('Publication / Sub Category: ') }}</label>
                          <div class="d-flex">
                        <select class="form-control" name="publication" id="publication">
                            <option value="">Select Publication</option>
                            @foreach($publications as $pub)
                                <option value="{{ $pub->pub_name }}">{{ $pub->pub_name }}</option>
                            @endforeach
                        </select>
                         <select class="form-control" name="sub_category" id="sub_category">
                            <option value="">Select Sub Category</option>
                        </select>
</div>
                    </div>

                </div>
            <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Transportation %:') }}</label>
                        <div class="d-flex">
                            <input type="number" step="0.01" class="form-control calc-trigger" name="transportation" id="transportation" value="0">
                            <select class="form-control ms-2" id="city_dropdown">
                                <option value="">Select City</option>
                                @foreach($cityCosts as $city)
                                    <option value="{{ (float)$city->cost_percentage }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
              
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Purchase Price:') }}</label>
                        <input type="text" class="form-control" id="res_purchase_price" readonly>
                    </div>
                </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Weight (gms):') }}</label>
                        <input type="number" step="0.01" class="form-control" name="weight" id="weight" placeholder="Enter Weight">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label text-success">{{ __('Packaging Cost:') }}</label>
                        <input type="number" step="0.01" class="form-control calc-trigger" name="packaging_cost" id="packaging_cost" value="0" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label text-success">{{ __('Courier Charges:') }}</label>
                        <input type="number" step="0.01" class="form-control calc-trigger" name="courier_charges" id="courier_charges" value="0" readonly>
                    </div>
                </div>
            </div>
        


            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Net Cost:') }}</label>
                        <input type="text" class="form-control" id="res_net_cost" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Commission:') }}</label>
                        <input type="text" class="form-control" id="res_commission" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Final Costing (+2% Profit):') }}</label>
                        <input type="text" class="form-control" id="res_final_costing" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label font-weight-bold">{{ __('Final Costing (Round-Up):') }}</label>
                        <input type="text" class="form-control bg-light" id="res_final_rounded" readonly style="font-size: 1.2rem; font-weight: bold; color: #1e40af;">
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label font-weight-bold">{{ __('Pre-Defined Shipping:') }}</label>
                        <input type="number" step="0.01" class="form-control calc-trigger" name="pre_defined_shipping" id="pre_defined_shipping" value="0">
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered text-center">
                    <thead class="bg-warning text-dark">
                        <tr>
                            <th colspan="2">WITHOUT PRE-DEFINED SHIPPING</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Min Price - 1:</strong> (Final Costing) <span id="span_min_price_1" class="ml-2 font-weight-bold">0</span></td>
                            <td><strong>Max. Price:</strong> Show MRP. <span id="span_max_price_1" class="ml-2 font-weight-bold">0</span></td>
                        </tr>
                    </tbody>
                    <thead class="bg-warning text-dark">
                        <tr>
                            <th colspan="2">WITH PRE-DEFINED SHIPPING</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Min Price - 2:</strong> (Final Cost - Shipping) <span id="span_min_price_2" class="ml-2 font-weight-bold">0</span></td>
                            <td><strong>Max. Price:</strong> Show MRP. <span id="span_max_price_2" class="ml-2 font-weight-bold">0</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- STAGE 2 Header -->
            <div class="text-center bg-dark text-white p-2 mb-4">
                <h3 class="m-0">STAGE - 2</h3>
            </div>

            <div class="bg-light p-4 rounded">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('Other Seller Sold (Product Price):') }}</label>
                            <input type="number" step="0.01" class="form-control calc-trigger" name="seller_price" id="seller_price" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('Shipping Charges:') }}</label>
                            <input type="number" step="0.01" class="form-control calc-trigger" name="seller_shipping" id="seller_shipping" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('Fulfilment Type:') }}</label>
                            <select class="form-control calc-trigger" name="fulfilment_id" id="fulfilment_id">
                                @foreach($fulfilmentTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ (float)$type->difference_amount }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label font-weight-bold text-primary">{{ __('Competitor Price:') }}</label>
                            <small class="text-muted d-block mb-1">(Product Price + Shipping – Fulfilment)</small>
                            <input type="text" class="form-control bg-white" id="res_competitor_price" readonly>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 pt-3 border-top">
                    <div class="col-md-6 text-center border-right">
                        <h5 class="text-info">{{ __('Your Product Price:') }}</h5>
                        <div id="res_your_product_price" class="display-4 font-weight-bold text-dark">0</div>
                    </div>
                    <div class="col-md-6 text-center">
                        <h5 class="text-info">{{ __('Your Shipping Set:') }}</h5>
                        <div id="res_your_shipping_set" class="display-4 font-weight-bold text-dark">0</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Publication change -> Load Book Types
    $('#publication').on('change', function() {
        const pubName = $(this).val();
        $('#sub_category').html('<option value="">Select Sub Category</option>');
        $('#discount').val(0);
        
        if (pubName) {
            $.post("{{ route('marketplace.get_book_types') }}", { pub_name: pubName }, function(data) {
                data.forEach(function(type) {
                    $('#sub_category').append(`<option value="${type.id}">${type.name}</option>`);
                });
            });
        }
        performCalculation();
    });

    // Sub Category change -> Load Discount
    $('#sub_category').on('change', function() {
        const typeId = $(this).val();
        const pubName = $('#publication').val();
        
        if (typeId && pubName) {
            $.post("{{ route('marketplace.get_discount') }}", { pub_name: pubName, type_id: typeId }, function(data) {
                $('#discount').val(data.discount);
                performCalculation();
            });
        } else {
            $('#discount').val(0);
            performCalculation();
        }
    });

    // Weight change -> Load Charges
    $('#weight').on('input', function() {
        const weight = $(this).val();
        if (weight > 0) {
            $.post("{{ route('marketplace.get_weight_charges') }}", { weight: weight }, function(data) {
                $('#packaging_cost').val(data.packing_charge);
                $('#courier_charges').val(data.courier_rate);
                performCalculation();
            });
        } else {
            $('#packaging_cost').val(0);
            $('#courier_charges').val(0);
            performCalculation();
        }
    });

    // City selection updates transportation manually but allows override
    $('#city_dropdown').on('change', function() {
        if($(this).val()) {
            $('#transportation').val($(this).val());
            performCalculation();
        }
    });

    $('.calc-trigger').on('input change', function() {
        performCalculation();
    });

    function performCalculation() {
        const formData = $('#calculationForm').serialize();
        
        $.ajax({
            url: "{{ route('marketplace.calculate') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                $('#res_purchase_price').val(response.purchase_price);
                $('#res_net_cost').val(response.net_cost);
                $('#res_commission').val(response.commission);
                $('#res_final_costing').val(response.final_costing);
                $('#res_final_rounded').val(response.final_costing_rounded);

                // Update Stage 2 base values
                $('#span_min_price_1').text(response.min_price_1);
                $('#span_min_price_2').text(response.min_price_2);
                $('#span_max_price_1').text($('#mrp').val() || 0);
                $('#span_max_price_2').text($('#mrp').val() || 0);

                // Update Competitor results
                $('#res_competitor_price').val(response.competitor_price);
                $('#res_your_product_price').text(response.your_product_price);
                $('#res_your_shipping_set').text(response.your_shipping_set);
            }
        });
    }
});
</script>
@endpush
