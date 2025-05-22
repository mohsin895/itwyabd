@extends('welcome')
@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Sale Details - {{ $sale->sale_number }}</h4>
                    <div>
                      
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Sale Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                             
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>{{ $sale->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sale Date:</strong></td>
                                    <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                </tr>
                              
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td>{{ number_format($sale->subtotal, 2) }} BDT</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td>{{ number_format($sale->discount_amount, 2) }} BDT</td>
                                </tr>
                               
                                <tr class="table-primary">
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>{{ $sale->formatted_total }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                 @if($sale->notes)
                <div class="alert alert-info">
                    <strong>Notes:</strong> {{ $sale->notes->content }}
                </div>
            @endif


                    <!-- Sale Items -->
                    <h5>Sale Items</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->saleItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }} BDT</td>
                                    <td>{{ $item->discount_percentage }}% ({{ number_format($item->discount_amount, 2) }} BDT)</td>
                                    <td>{{ $item->formatted_total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Notes Section -->
                   
                </div>
            </div>
        </div>
    </div>
</div>



@endsection