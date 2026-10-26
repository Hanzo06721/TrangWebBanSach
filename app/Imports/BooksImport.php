<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Book([
            'BookID'=> $row['bookid'],
            'Title'=> $row['title'],
            'AuthorName'=> $row['authorname'] ?? null,
            'ISBN'=> $row['isbn'],
            'Publisher'=> $row['publisher'] ?? null,
            'CategoryID'=> $row['categoryid'],
            'Description'=> $row['description']??null,
            'CoverImage'=> $row['coverimage']??null,
        ]);
    }

    public function rules():array
    {
        return [
            'bookid' => 'required|string|max:10|unique:books,BookID',
            'title' => 'required|string|max:200',
            'authorname' => 'nullable|string|max:100',
            'isbn' => 'required|max:30|unique:books,ISBN',
            'publisher' => 'nullable|string|max:100',
            'categoryid' => 'required|exists:categories,CategoryID',
            'description' => 'nullable|string',
            'coverimage' => 'nullable|string|max:255',
        ];
    }
}
