<?php

namespace App\Http\Controllers;

use App\Mail\SupportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Page;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $contents = Page::with([
            'category',
            'subCategory',
            'subSubCategory'
        ])
            ->where('status', 'open') // केवल open वाले
            ->orderBy('id', 'DESC')
            ->get();

        return view('home', compact('contents'));
    }



    public function supportMail()
    {
        $data = request()->all();

        foreach (['exam360.in@gmail.com', 'abhishek86478@gmail.com'] as $mail) {
            Mail::to($mail)->send(new SupportMail($data));
        }

        return redirect()->back();
    }
}
