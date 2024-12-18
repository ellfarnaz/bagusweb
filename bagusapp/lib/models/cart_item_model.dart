class CartItem {
  final int? makananId;
  final int? minumanId;
  final String nama;
  final double harga;
  final String gambar;
  int jumlah;

  CartItem({
    this.makananId,
    this.minumanId,
    required this.nama,
    required this.harga,
    required this.gambar,
    this.jumlah = 1,
  });

  double get totalHarga => harga * jumlah;

  Map<String, dynamic> toJson() {
    return {
      'makanan_id': makananId,
      'minuman_id': minumanId,
      'jumlah': jumlah,
      'harga': harga,
      'total_harga': totalHarga,
    };
  }
}
