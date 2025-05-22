@extends('welcome')
@section('main')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Sale</h4>
                </div>
                
                <div class="card-body">
                    <form id="saleForm">
                        @csrf
                        
                        <!-- Sale Header -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Customer *</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sale Date *</label>
                                <input type="date" name="sale_date" class="form-control" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Notes</label>
                                <input type="text" name="content" class="form-control" 
                                       placeholder="Optional content">
                            </div>
                        </div>

                        <!-- Sale Items -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Sale Items</h5>
                                <button type="button" class="btn btn-success" onclick="addLineItem()">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th width="30%">Product</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Unit Price</th>
                                            <th width="15%">Discount %</th>
                                            <th width="15%">Total</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end" id="subtotal">0.00 BDT</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Discount:</strong></td>
                                        <td class="text-end" id="totalDiscount">0.00 BDT</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax (15%):</strong></td>
                                        <td class="text-end" id="taxAmount">0.00 BDT</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Grand Total:</strong></td>
                                        <td class="text-end" id="grandTotal"><strong>0.00 BDT</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Create Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/template" id="productOptionsTemplate">
    <option value="">Select Product</option>
    @foreach($products as $product)
    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
    @endforeach
</script>

<script src="{{ asset('js/sales-form.js') }}"></script>

@endsection