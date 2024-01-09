<p>Stock Card Produk {{ $source->Products->name }}</p>
<table style="height: 73px; border-color: #000000;" border="1" width="265">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tgl Transaksi</th>
                                <th>Tipe</th>
                                <th>No Referensi</th>
                                <th>Barang Masuk</th>
                                <th>Barang Keluar</th>
                                <th>Sisa Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key=>$val)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                                <td>
                                    @if($val->type == 1)
                                    Adjustment
                                    @elseif($val->type == 2)
                                    Penjualan
                                    @elseif($val->type == 3)
                                    Purchase
                                    @elseif($val->type == 4)
                                    Usage
                                    @elseif($val->type == 5)
                                    Pengiriman
                                    @elseif($val->type == 6)
                                    Penerimaan Brg
                                    @elseif($val->type == 7)
                                    Manufaktur
                                    @endif
                                </td>
                                <td>{{ $val->reference_id}}</td>
                                <td>{{ $val->incoming}}</td>
                                <td>{{ $val->outgoing }}</td>
                                <td>{{ $val->remaining }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>