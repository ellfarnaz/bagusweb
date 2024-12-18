import 'package:dio/dio.dart';
import 'package:logging/logging.dart';
import '../constants/api_endpoints.dart';
import '../models/user_model.dart';
import 'dart:io';

class AuthService {
  final Dio _dio = Dio();
  final _logger = Logger('AuthService');

  Future<User> register({
    required String nama,
    required String email,
    required String password,
  }) async {
    try {
      final response = await _dio.post(
        '${ApiEndpoints.baseUrl}${ApiEndpoints.register}',
        data: {
          'nama': nama,
          'email': email,
          'password': password,
        },
      );

      if (response.statusCode == 201) {
        final userData = response.data['data'] as Map<String, dynamic>;
        if (response.data['token'] != null) {
          userData['token'] = response.data['token'];
        }
        return User.fromJson(userData);
      } else {
        throw Exception('Registrasi gagal');
      }
    } catch (e) {
      throw Exception(e.toString());
    }
  }

  Future<User> login({
    required String email,
    required String password,
  }) async {
    try {
      _logger.info('Attempting login for email: $email');

      final response = await _dio.post(
        '${ApiEndpoints.baseUrl}${ApiEndpoints.login}',
        data: {
          'email': email,
          'password': password,
        },
      );

      _logger.info('Login response: ${response.data}');

      if (response.statusCode == 200) {
        if (response.data['success'] == true) {
          final userData = response.data['data'] as Map<String, dynamic>;
          userData['token'] = response.data['token'];

          _logger.info('Parsed user data with token: $userData');
          return User.fromJson(userData);
        } else {
          throw Exception(response.data['message'] ?? 'Login gagal');
        }
      } else {
        throw Exception('Login gagal: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      _logger.severe('Dio error during login: ${e.message}');
      _logger.severe('Response data: ${e.response?.data}');

      if (e.response?.data != null && e.response?.data['message'] != null) {
        throw Exception(e.response?.data['message']);
      }
      throw Exception('Gagal terhubung ke server');
    } catch (e) {
      _logger.severe('Unexpected error during login: $e');
      throw Exception('Terjadi kesalahan: $e');
    }
  }

  Future<void> logout(String token) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer $token';

      final response = await _dio.post(
        '${ApiEndpoints.baseUrl}${ApiEndpoints.logout}',
      );

      if (response.statusCode != 200) {
        throw Exception('Gagal logout');
      }
    } catch (e) {
      _logger.severe('Logout error: $e');
      throw Exception('Gagal logout: ${e.toString()}');
    }
  }

  Future<User> updateProfile({
    required String token,
    required User user,
    File? imageFile,
  }) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer $token';

      final formData = FormData.fromMap({
        'nama': user.nama,
        'alamat': user.alamat ?? '',
        'no_hp': user.noHp ?? '',
        'patokan': user.patokan ?? '',
      });

      if (imageFile != null) {
        String fileName = imageFile.path.split('/').last;
        formData.files.add(
          MapEntry(
            'image',
            await MultipartFile.fromFile(
              imageFile.path,
              filename: fileName,
            ),
          ),
        );
      }

      _logger.info('Updating profile with data: ${formData.fields}');

      final response = await _dio.post(
        '${ApiEndpoints.baseUrl}${ApiEndpoints.updateProfile}',
        data: formData,
      );

      _logger.info('Profile update response: ${response.data}');

      if (response.statusCode == 200 && response.data['success'] == true) {
        final userData = response.data['data'] as Map<String, dynamic>;

        // Buat user baru dengan data yang diupdate
        return User(
          id: userData['id'],
          nama: userData['nama'],
          email: userData['email'],
          role: userData['role'],
          token: user.token, // Gunakan token yang ada
          alamat: userData['alamat'],
          noHp: userData['no_hp'],
          patokan: userData['patokan'],
          imagePath: userData['image_path'],
        );
      } else {
        throw Exception(response.data['message'] ?? 'Gagal mengupdate profil');
      }
    } on DioException catch (e) {
      _logger.severe('Dio error during profile update: ${e.message}');
      _logger.severe('Response data: ${e.response?.data}');

      if (e.response?.data != null && e.response?.data['message'] != null) {
        throw Exception(e.response?.data['message']);
      }
      throw Exception('Gagal terhubung ke server');
    } catch (e) {
      _logger.severe('Unexpected error during profile update: $e');
      throw Exception('Terjadi kesalahan: $e');
    }
  }
}
