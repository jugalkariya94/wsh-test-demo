<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;
use App\Services\ShopifyAPIService;
use http\Env\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BookController extends Controller
{

    protected BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        return new BookCollection(Book::paginate(2));
    }

    public function store(BookCreateRequest $request)
    {
        // TODO: implement SQL transactions to rollback failed attempts
        try {
            $data = $request->all();
            $book = $this->bookService->save($data);
            return response()->json(['data' => $book, 'success' => true, 'message' => 'Book created successfully.'], \Illuminate\Http\Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getTrace()], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, Book $book)
    {
        try {
            $bookResponse = BookResource::make($book);
            return response()->json(['data' => $bookResponse, 'success' => true], \Illuminate\Http\Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
