<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Book;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    
    public function collection()
    {
        return Book::select(
            'BookID', 'Title', 'AuthorName','ISBN','Publisher','CategoryID','Description')->get();
        
    }

    public function headings(): array
    {
        return [
            'BookID', 'Title', 'AuthorName','ISBN','Publisher','CategoryID','Description'];
        
    }
}
