@extends('welcome')
@section('main')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Sales Trash</h4>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Sales
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sale #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedSales as $sale)
                                <tr>
                                    <td>{{ $sale->sale_number }}</td>
                                    <td>{{ $sale->user->name }}</td>
                                    <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                    <td>{{ $sale->formatted_total }}</td>
                                    <td>{{ $sale->deleted_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="restoreSale({{ $sale->id }})">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No trashed sales found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $trashedSales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function restoreSale(saleId) {
    if (confirm('Are you sure you want to restore this sale?')) {
        fetch(`/sales/${saleId}/restore`, {
            method: 'POST',
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
            alert('An error occurred while restoring the sale.');
        });
    }
}
</script>

@endsection