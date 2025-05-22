@extends('welcome')
@section('main')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Sales Management</h4>
                    <div>
                        <a href="{{ route('sales.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Sale
                        </a>
                        <a href="{{ route('sales.trash') }}" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Trash
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="row g-3 mb-4" id="filterForm">
                        <div class="col-md-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" 
                                   value="{{ request('customer_name') }}" placeholder="Search by customer">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="product_name" class="form-control" 
                                   value="{{ request('product_name') }}" placeholder="Search by product">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ request('end_date') }}">
                        </div>
                    
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </form>

                    <!-- Page Total -->
                    @if($pageTotal > 0)
                    <div class="alert alert-info">
                        <strong>Page Total: {{ number_format($pageTotal, 2) }} BDT</strong>
                    </div>
                    @endif

                    <!-- Sales Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                 
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                 
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                <tr>
                                    
                                    <td>{{ $sale->user->name }}</td>
                                    <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                    <td>{{ $sale->saleItems->count() }} items</td>
                                    <td>{{ $sale->formatted_total }}</td>
                                  
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                         
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteSale({{ $sale->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No sales found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSale(saleId) {
    if (confirm('Are you sure you want to move this sale to trash?')) {
        fetch(`/sales/${saleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the sale.');
        });
    }
}
</script>

@endsection