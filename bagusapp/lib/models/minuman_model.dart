import '../constants/api_endpoints.dart';

class Minuman {
  final int id;
  final String nama;
  final double harga;
  final String deskripsi;
  final String gambar;
  final String kategori;

  Minuman({
    required this.id,
    required this.nama,
    required this.harga,
    required this.deskripsi,
    required this.gambar,
    required this.kategori,
  });

  factory Minuman.fromJson(Map<String, dynamic> json) {
    final baseStorageUrl = ApiEndpoints.baseUrl.replaceAll('/api', '');

    return Minuman(
      id: json['id'] as int,
      nama: json['nama'] as String,
      harga: double.parse(json['harga'].toString()),
      deskripsi: json['deskripsi'] as String,
      gambar: json['foto'] != null
          ? '$baseStorageUrl/images/${json['foto']}'
          : 'https://via.placeholder.com/150',
      kategori: json['kategori'] ?? 'minuman',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
      'harga': harga,
      'deskripsi': deskripsi,
      'gambar': gambar,
      'kategori': kategori,
    };
  }
}
