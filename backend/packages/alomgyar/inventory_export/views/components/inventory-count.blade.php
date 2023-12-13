<form wire:submit.prevent="count">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-body border-top-1 border-top-success">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="flex-grow-1">
                        <div class="form-group">
                            <label for="isbn" class="col-form-label font-weight-bold">ISBN</label>
                            <input type="text" class="form-control" wire:model="productISBN">
                        </div>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-outline-success legitRipple my-2">Beküldés</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body border-top-1 border-top-success">
                
                    <h3>Az utolsó 10 beillesztett könyv</h3>
                
                <table class="table table-striped">
                    <thead>
                        <th>ISBN</th>
                        <th>CÍM</th>
                    </thead>
                    <tbody>
                        @foreach ($lastAddedBooks->reverse() as $item)
                            <tr>
                                <td>{{$item['isbn']}}</td>
                                <td>{{$item['title']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
