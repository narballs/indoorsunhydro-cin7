<div>
 <div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">             
                <input type="text"  class="form-control" placeholder="Search" wire:model="searchTerm" />
                <table class="table table-bordered" style="margin: 10px 0 10px 0;">
                    <tr>
                        <th>Name</th>
                        <th>Emails</th>
                    </tr>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            {{ $product->name }}
                        </td>
                    </tr>
                    @endforeach
                </table>
                <div>
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
