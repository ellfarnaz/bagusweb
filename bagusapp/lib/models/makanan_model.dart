class Makanan {
  final int id;
  final String nama;
  final double harga;
  final String deskripsi;
  final String gambar;
  final String kategori;

  Makanan({
    required this.id,
    required this.nama,
    required this.harga,
    required this.deskripsi,
    required this.gambar,
    required this.kategori,
  });

  factory Makanan.fromJson(Map<String, dynamic> json) {
    return Makanan(
      id: json['id'] as int,
      nama: json['nama'] as String,
      harga: double.parse(json['harga'].toString()),
      deskripsi: json['deskripsi'] as String,
      gambar: json['foto'] != null
          ? 'http://10.0.2.2:8000/images/${json['foto']}'
          : 'https://via.placeholder.com/150',
      kategori: json['kategori'] ?? 'makanan',
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
