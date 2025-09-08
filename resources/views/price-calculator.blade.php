@if(request()->route()->getName() == 'price.calculation')

<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!doctype html>
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/skin-modes.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('assets/colors/color1.css') }}" />
</head>

@push('css')
<style>
    .wrap-login100 {
        width: 100% !important;
    }
</style>
@endpush

<style>
    .select2-container {
        z-index:1000;
    }
</style>

<body class="app sidebar-mini ltr">
    <div class="page">
        <div class="container-login100" >
            <div class="wrap-login100 p-6  w-100">
                <div class="card">
                    <div class="card-body" style="background-color: antiquewhite;">
                        <h4>PRICE CALCULATION</h4>
                        @if(request()->route()->getName() == 'price.calculation')
                        <div class="form-group col-md-3">
                            <label for="mrp" class="form-label">{{ __('MRP') }}<span class="text-danger">*</span><span class="text-success"> ( Maximum Retail Price)</span></label>
                            <input id="mrp" type="number" class="form-control @error('mrp') is-invalid @enderror" name="mrp" value="{{ old('mrp') }}" autocomplete="mrp" autofocus placeholder="MRP">
                            <span class="error-message mrp" style="color:red;"></span>
                
                            @error('mrp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="pub_name" class="form-label">{{ __('Publication') }}</label>
                                <select class="searchable_dropdown form-control" name="pub_name" id="pub_name">
                                    <option value="">--Publication--</option>
                                    @foreach($publications as $pub)
                                    <option value="{{ $pub->id }}">{{ $pub->pub_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="form-group col-md-3">
                                <label for="book_name" class="form-label">{{ __('Book Type') }}</label>
                                <select class="form-control" name="book_name" id="book_name">
                                    <option value="">-- Select Book --</option>
                                </select>
                            </div>
                
                
                            <div class="form-group col-md-3 text-danger">
                                <label class="form-label">{{ __('Selling Prices ( + 45 )') }}</label>
                                <div class="selling-prices">
                                    <strong>Min Selling Price:</strong> <span id="selling_price1">--</span> <br>
                                    <strong>Max Selling Price:</strong> <span id="selling_price2">--</span>
                                </div>
                            </div>
                
                            <div class="form-group col-md-2 text-success">
                                <label class="form-label">{{ __('Selling Prices ( - 45 )') }}</label>
                                <div class="selling-prices">
                                    <strong>Min Selling Price:</strong> <span id="selling_price_minus1">--</span> <br>
                                    <strong>Max Selling Price:</strong> <span id="selling_price_minus2">--</span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <strong>Weight:</strong> <span id="weight">--</span> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>

@else 
    <div class="card">
        <div class="card-body" style="background-color: antiquewhite;">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="pub_name" class="form-label">{{ __('Publication') }}</label>
                    <select class="searchable_dropdown form-control" name="pub_name" id="pub_name">
                        <option value="">--Publication--</option>
                        @foreach($publications as $pub)
                        <option value="{{ $pub->id }}">{{ $pub->pub_name }}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="form-group col-md-3">
                    <label for="book_name" class="form-label">{{ __('Book Type') }}</label>
                    <select class="form-control" name="book_name" id="book_name">
                        <option value="">-- Select Book --</option>
                    </select>
                </div>
        
        
                <div class="form-group col-md-3 text-danger">
                    <label class="form-label">{{ __('Selling Prices ( + 45 )') }}</label>
                    <div class="selling-prices">
                        <strong>Min Selling Price:</strong> <span id="selling_price1">--</span> <br>
                        <strong>Max Selling Price:</strong> <span id="selling_price2">--</span>
                    </div>
                </div>
        
                <div class="form-group col-md-2 text-success">
                    <label class="form-label">{{ __('Selling Prices ( - 45 )') }}</label>
                    <div class="selling-prices">
                        <strong>Min Selling Price:</strong> <span id="selling_price_minus1">--</span> <br>
                        <strong>Max Selling Price:</strong> <span id="selling_price_minus2">--</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <strong>Weight:</strong> <span id="weight">--</span> <br>
                </div>
            </div>
        </div>
    </div>
@endif

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".searchable_dropdown").select2();
        
        const publications = @json($publications);
        const $pubDropdown = $("#pub_name");
        const $bookDropdown = $("#book_name");
        const $mrpInput = $("#mrp");
        let discount = 0,
            locationDis = 0,
            enteredMRP = 0;
    
        // Jab Publication select ho
        $pubDropdown.on("change", function() {
            const selectedPub = publications.find(pub => pub.id == $(this).val());
            $bookDropdown.html('<option value="">-- Select Book --</option>');
    
            if (selectedPub) {
                for (let i = 1; i <= 6; i++) {
                    const bookType = selectedPub[`book_type_${i}`];
                    if (bookType) {
                        $bookDropdown.append(`<option value="book_discount_${i}">${bookType}</option>`);
                    }
                }
            }
    
            calculatePrice();
        });
    
        // Jab Book select ho
        $bookDropdown.on("change", function() {
            const selectedPub = publications.find(pub => pub.id == $pubDropdown.val());
            if (selectedPub && $(this).val()) {
                discount = parseInt(selectedPub[$(this).val()]) || 0;
                locationDis = parseInt(selectedPub.location_dis) || 0;
            }
            calculatePrice();
        });
    
        // Jab MRP change ho to naye value store karo aur calculation karo
        $mrpInput.on("input", function() {
            enteredMRP = parseInt($(this).val()) || 0;
            calculatePrice();
        });
    
        function calculatePrice() {
            const mrp = parseInt($mrpInput.val()) || 0;
            // const locationDiscount = (locationDis / 100) * mrp;
            const locationDiscount = locationDis;
            const netDis = discount - locationDiscount;
            const finalPrice = mrp - ((netDis*mrp)/100);
            // console.log("mrp", mrp)
            // console.log("finalPrice", finalPrice)
    
            if (mrp && finalPrice > 0) {
                $.get("{{ route('listing.getPriceRecords') }}", {
                        price: finalPrice
                    })
                    .done(function(data) {
                        if (!data.length) {
                            // console.error("No data received.");
                            alert("No data according to" + finalPrice)
                            return;
                        }
    
                        const record = data[0];
                        const courier_rate = parseInt(record.courier_rate) || 0;
                        const packing_charge = parseInt(record.packing_charge) || 0;
                        const our_min_profit_value = parseInt(record.our_min_profit) || 0;
                        const max_profit_value = parseInt(record.max_profit) || 0;
    
                        // Calculate transaction cost
                        const transactionCost = (finalPrice + courier_rate + packing_charge) * (3 / 100);
                        const selling_price = finalPrice + courier_rate + packing_charge + transactionCost;
    
                        const selling_price1 = selling_price + our_min_profit_value;
                        const selling_price2 = selling_price + max_profit_value;
    
                        $("#selling_price1").text(selling_price1.toFixed(2));
                        $("#selling_price2").text(selling_price2.toFixed(2));
                        
                            
                        $("#selling_price_minus1").text(selling_price1.toFixed(2) - 45);
                        $("#selling_price_minus2").text(selling_price2.toFixed(2) - 45);
                        $("#weight").text(record.weight);
                    })
                    .fail(function(error) {
                        console.error("Error fetching records:", error);
                    });
            }
        }
    });
</script>