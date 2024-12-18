class User {
  final int id;
  String nama;
  final String email;
  final String role;
  final String? token;
  String? alamat;
  String? noHp;
  String? patokan;
  String? imagePath;

  User({
    required this.id,
    required this.nama,
    required this.email,
    required this.role,
    this.token,
    this.alamat,
    this.noHp,
    this.patokan,
    this.imagePath,
  });

  User copyWith({
    String? nama,
    String? alamat,
    String? noHp,
    String? patokan,
    String? imagePath,
  }) {
    return User(
      id: id,
      nama: nama ?? this.nama,
      email: email,
      role: role,
      token: token,
      alamat: alamat ?? this.alamat,
      noHp: noHp ?? this.noHp,
      patokan: patokan ?? this.patokan,
      imagePath: imagePath ?? this.imagePath,
    );
  }

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      nama: json['nama'] as String,
      email: json['email'] as String,
      role: json['role'] as String,
      token: json['token'] as String?,
      alamat: json['alamat'] as String?,
      noHp: json['no_hp'] as String?,
      patokan: json['patokan'] as String?,
      imagePath: json['image_path'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
      'email': email,
      'role': role,
      'token': token,
      'alamat': alamat,
      'no_hp': noHp,
      'patokan': patokan,
      'image_path': imagePath,
    };
  }
}
