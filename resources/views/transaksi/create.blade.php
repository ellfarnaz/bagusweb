@extends('layout')
@section('page','Transaksi')
@section('content')

<div class="container mt-5">
    <div class="card">
        <div class="card-header" style="background-color: #FF6F06;">
            <h1 class="text-white text-center">Create Transaksi</h1>
        </div>
        <div class="card-body">
            <form id="transaksiForm" action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="customer_name">Customer Name:</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" required>
                </div>
                <div class="form-group">
                    <label for="customer_alamat">Customer Alamat:</label>
                    <input type="text" class="form-control" name="customer_alamat" id="customer_alamat" required>
                </div>
                <div class="form-group">
                    <label for="customer_no_hp">Customer No HP:</label>
                    <input type="text" class="form-control" name="customer_no_hp" id="customer_no_hp" required>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="food_item_makanan">Makanan:</label>
                            <select class="form-control" id="food_item_makanan">
                                <option value="">Select Makanan</option>
                                @foreach($makanan as $item)
                                    <option value="{{ $item->id }}" data-harga="{{ $item->harga }}">{{ $item->nama }}</option>
                                @endforeach
                                <option value="">Kosong</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="food_item_minuman">Minuman:</label>
                            <select class="form-control" id="food_item_minuman">
                                <option value="">Select Minuman</option>
                                @foreach($minuman as $item)
                                    <option value="{{ $item->id }}" data-harga="{{ $item->harga }}">{{ $item->nama }}</option>
                                @endforeach
                                <option value="">Kosong</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <button id="btnSave" type="button" class="btn btn-success">Simpan</button>
                </div>
                <div class="form-group mt-3">
                    <label for="item_list">Item yang Di Pilih:</label>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">Nomor</th>
                                <th>Makanan</th>
                                <th>Minuman</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="item_list">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td id="total_harga" class="text-center">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="form-group">
                    <label for="customer_catatan">Customer Catatan:</label>
                    <input type="text" class="form-control" name="customer_catatan" id="customer_catatan" required>
                </div>
                <input type="hidden" name="items" id="items_input">
                <input type="hidden" name="total_harga" id="total_harga_input">
                <button type="submit" class="btn btn-primary">Buat</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var makananSelect = document.getElementById('food_item_makanan');
        var minumanSelect = document.getElementById('food_item_minuman');
        var itemList = document.getElementById('item_list');
        var totalHargaElement = document.getElementById('total_harga');
        var totalHargaInput = document.getElementById('total_harga_input');
        var btnSave = document.getElementById('btnSave');
        var savedItems = [];
        var totalHarga = 0;

        btnSave.addEventListener('click', function(event) {
            event.preventDefault();
            var makananId = makananSelect.value;
            var minumanId = minumanSelect.value;
            var makananNama = makananSelect.options[makananSelect.selectedIndex].text;
            var minumanNama = minumanSelect.options[minumanSelect.selectedIndex].text;
            var hargaMakanan = parseFloat(makananSelect.options[makananSelect.selectedIndex].getAttribute('data-harga')) || 0;
            var hargaMinuman = parseFloat(minumanSelect.options[minumanSelect.selectedIndex].getAttribute('data-harga')) || 0;

            if (makananId || minumanId) {
                var hargaTotalItem = hargaMakanan + hargaMinuman;
                savedItems.push({ makanan_id: makananId || null, minuman_id: minumanId || null, makanan: makananNama, minuman: minumanNama, harga: hargaTotalItem });
                totalHarga += hargaTotalItem;

                var row = document.createElement('tr');

                var numberCell = document.createElement('td');
                numberCell.textContent = savedItems.length;
                numberCell.className = 'text-center';

                var makananCell = document.createElement('td');
                makananCell.textContent = makananNama;
                makananCell.className = 'text-center';

                var minumanCell = document.createElement('td');
                minumanCell.textContent = minumanNama;
                minumanCell.className = 'text-center';

                var hargaCell = document.createElement('td');
                hargaCell.textContent = formatRupiah(hargaTotalItem);
                hargaCell.className = 'text-center';

                var aksiCell = document.createElement('td');
                aksiCell.className = 'text-center';
                var deleteButton = document.createElement('button');
                deleteButton.className = 'btn btn-danger btn-sm';
                deleteButton.textContent = 'Batal';
                deleteButton.addEventListener('click', function() {
                    var rowIndex = row.rowIndex - 1; // Adjusting for table header
                    totalHarga -= savedItems[rowIndex].harga;
                    savedItems.splice(rowIndex, 1);
                    row.remove();
                    updateTable();
                });
                aksiCell.appendChild(deleteButton);

                row.appendChild(numberCell);
                row.appendChild(makananCell);
                row.appendChild(minumanCell);
                row.appendChild(hargaCell);
                row.appendChild(aksiCell);

                itemList.appendChild(row);
                totalHargaElement.textContent = formatRupiah(totalHarga);
                totalHargaInput.value = totalHarga; // Update hidden input value
            }
        });

        function updateTable() {
            var rows = itemList.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                rows[i].getElementsByTagName('td')[0].textContent = i + 1;
            }
            totalHargaElement.textContent = formatRupiah(totalHarga);
            totalHargaInput.value = totalHarga; // Update hidden input value
        }

        function formatRupiah(number) {
            return 'Rp ' + number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
        document.getElementById('transaksiForm').addEventListener('submit', function(event) {
            document.getElementById('items_input').value = JSON.stringify(savedItems);
        });
    });
</script>

@endsection
