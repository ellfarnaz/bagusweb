class Transaksi {
  final int id;
  final String customerName;
  final String customerAlamat;
  final String customerNoHp;
  final String? customerCatatan;
  final double totalHarga;
  final String status;
  final DateTime createdAt;

  Transaksi({
    required this.id,
    required this.customerName,
    required this.customerAlamat,
    required this.customerNoHp,
    this.customerCatatan,
    required this.totalHarga,
    required this.status,
    required this.createdAt,
  });

  factory Transaksi.fromJson(Map<String, dynamic> json) {
    return Transaksi(
      id: json['id'],
      customerName: json['customer_name'],
      customerAlamat: json['customer_alamat'],
      customerNoHp: json['customer_no_hp'],
      customerCatatan: json['customer_catatan'],
      totalHarga: double.parse(json['total_harga'].toString()),
      status: json['status'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
