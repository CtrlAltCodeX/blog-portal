<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BackupListing;
use App\Models\ListingModifyRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Mail\RequestNotificationMail;
use Illuminate\Support\Facades\Mail;

class ListingModifyController extends Controller
{
    public function __construct()
    {
        $this->middleware('role_or_permission:Product Listing -> Request to Modify', ['only' => ['index']]);
        $this->middleware('role_or_permission:Product Listing -> Modified Listing Status', ['only' => ['requested']]);
        $this->middleware('role_or_permission:Product Listing -> Review Modify Listing (DB)', ['only' => ['approval']]);
    }

    public function index()
    {
        return view('modify-listing.index');
    }

    public function fetchProduct(Request $request)
    {
        $product = BackupListing::where('product_id', $request->product_id)->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'data' => [
                    'publisher' => $product->publisher,
                    'book_name' => $product->title,
                    'mrp' => $product->mrp,
                    'selling_price' => $product->selling_price,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found']);
    }

    public function store(Request $request)
    {
        $data = $request->input('rows');

        foreach ($data as $row) {
            ListingModifyRequest::create([
                'product_id' => $row['product_id'],
                'category' => $row['category'],
                'requested_by' => auth()->id(),
                'status' => 'Requested'
            ]);
        }

        // Send Email Notification to all Active Users
        $activeUsers = User::where('status', 1)->get();
        $data = [
            'subject' => 'New Listing Modification Request',
            'type' => 'Modify Listing Request',
            'user_name' => auth()->user()->name,
            'details' => "Total " . count($data) . " modification requests have been submitted."
        ];

        foreach ($activeUsers as $user) {
            Mail::to($user->email)->send(new RequestNotificationMail($data));
        }

        return response()->json(['success' => true, 'message' => 'Requests saved successfully']);
    }

    public function requested()
    {
        $requested = ListingModifyRequest::with(['product', 'requestedBy', 'updatedBy'])
            ->where('status', 'Requested')
            ->get();

        $completed = ListingModifyRequest::with(['product', 'requestedBy', 'updatedBy'])
            ->where('status', 'Completed')
            ->get();

        return view('modify-listing.requested', compact('requested', 'completed'));
    }

    public function downloadSample()
    {
        $headers = ['Product ID', 'Category'];
        $data = [
            ['12345', 'Exchange with Others'],
            ['12345', 'Update To Latest'],
        ];

        return response()->streamDownload(function () use ($headers, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'sample_modify_listing.csv');
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $data = [];
        while ($row = fgetcsv($handle)) {
            $productId = $row[0];
            $category = $row[1] ?? '';

            $product = BackupListing::where('product_id', $productId)->first();

            $data[] = [
                'product_id' => $productId,
                'category' => $category,
                'publisher' => $product->publisher ?? 'N/A',
                'book_name' => $product->title ?? 'N/A',
                'mrp' => $product->mrp ?? '0',
                'selling' => $product->selling_price ?? '0',
            ];
        }
        fclose($handle);

        return response()->json($data);
    }

    public function approval()
    {
        $exchange = ListingModifyRequest::with('product', 'requestedBy')
            ->where('category', 'Exchange with Others')
            ->where('status', 'Requested')
            ->get();

        $update = ListingModifyRequest::with('product', 'requestedBy')
            ->where('category', 'Update To Latest')
            ->where('status', 'Requested')
            ->get();

        return view('modify-listing.approval', compact('exchange', 'update'));
    }

    public function destroy($id)
    {
        $request = ListingModifyRequest::findOrFail($id);
        $request->delete();

        return redirect()->back()->with('success', 'Record deleted successfully');
    }
}
