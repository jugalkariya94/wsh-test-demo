<?php

namespace App\Repositories;

use App\Models\Book;

/**
 * Class BookRepository.
 */
class BookRepository
{
    public function firstOrNew($id)
    {
        return Book::firstOrNew(['shopify_id' => $id]);
    }

}
