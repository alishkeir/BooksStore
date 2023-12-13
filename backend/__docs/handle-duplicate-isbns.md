## Pt 1. only ebooks

-   run `php artisan migrate`

    -   this will create a new table, where the modifications of the `product_movements_items` table will be logged
    -   newly created table name `product_movements_items_isbn_change`

-   run tinker
    -   type the following:
    -   `(new Alomgyar\Products\Services\IsbnService)->handleDuplicationsWithSameIsbn();`
    -   press `ENTER`
    -   🔥🚀
