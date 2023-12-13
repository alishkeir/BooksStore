<?php

use Alomgyar\Orders\Mail\OrderMail;
use App\Exports\BooksWithoutPublisherExport;
use App\Exports\DuplicateISBNExport;
use App\Exports\PublishersExport;
use App\Order;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;

Route::get('download', function () {
    return Excel::download(new PublishersExport, 'publishers.xlsx');
});

Route::get('books-without-publishers', function () {
    return Excel::download(new BooksWithoutPublisherExport, 'books-without-publishers.xlsx');
});

Route::get('download-duplicate-ibns', function () {
    return Excel::download(new DuplicateISBNExport, 'duplicate-isbns.xlsx');
});


Route::get('send-order-mail', function () {

    $order = Order::find(305316);

    Mail::to(trim('zoltan.papp@weborigo.eu'))
    ->send(new OrderMail($order));

    return 'yeeee';
});
