<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Services\SaleService;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $query = Sale::with(['user', 'saleItems.product']);

        // Apply filters
        if ($request->filled('customer_name')) {
            $query->filterByCustomer($request->customer_name);
        }

        if ($request->filled('product_name')) {
            $query->filterByProduct($request->product_name);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->filterByDateRange($request->start_date, $request->end_date);
        }

    

        $sales = $query->orderBy('created_at', 'desc')->paginate(15);
        
    //  dd($query);
        $pageTotal = $sales->sum('total_amount');

        return view('index', compact('sales', 'pageTotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = User::orderBy('id','desc')->get();
        $data['products'] = Product::orderBy('id','desc')->get();
       
        return view('create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreSaleRequest $request)
{
    try {
        $sale = (new SaleService())->createSale(
            saleData: [
                'user_id' => $request->user_id,
                'sale_date' => $request->sale_date,
            ],
            itemsData: $request->items,
        );

       $content = $request->input('content');
        if (!empty($content) && trim($content) !== '') {
            $note = new Note();
            $note->notable_id = $sale->id; 
            $note->notable_type = Sale::class;
            $note->content = $content;
            $note->save();
            
         
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully!',
                'sale' => $sale->load(['user', 'saleItems.product', 'notes']),
            ]);
        }

        return redirect()->route('show', $sale)->with('success', 'Sale created successfully!');
    } catch (\Exception $e) {
      
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating sale: ' . $e->getMessage(),
            ], 500);
        }

        return back()->withInput()->with('error', 'Error creating sale: ' . $e->getMessage());
    }
}
    /**
     * Display the specified resource.
     */
      public function show(Sale $sale)
    {
        $sale->load(['user', 'saleItems.product', 'notes']);
        //   dd( $sale->load(['user', 'saleItems.product', 'notes']));
        return view('show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Sale moved to trash successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trash()
    {
        $trashedSales = Sale::onlyTrashed()
            ->with(['user', 'saleItems.product'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('trash', compact('trashedSales'));
    }

    public function restore($id)
    {
        try {
            $sale = Sale::withTrashed()->findOrFail($id);
            $sale->restore();
            
            return response()->json([
                'success' => true,
                'message' => 'Sale restored successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error restoring sale: ' . $e->getMessage()
            ], 500);
        }
    }

   
}
