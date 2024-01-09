<table>
    <thead>
        <tr>
            <th>Account Code</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Price</th>
            <th>Location</th>
            <th>Branch</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->Coas->coa_code }} - {{ $item->Coas->coa_name }}</td>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->Uoms->name }}</td>
            <td>{{ $item->purchase_price }}</td>
            <td>{{ $item->Warehouses->prefix }}</td>
            <td>{{ $item->Warehouses->Branches->prefix }}</td>
        </tr>
        @endforeach
    </tbody>
</table>