import 'package:dio/dio.dart';
import '../constants/api_endpoints.dart';
import '../models/makanan_model.dart';
import '../models/minuman_model.dart';
import 'dart:async';
import 'package:logging/logging.dart';

class MenuService {
  final Dio _dio = Dio()
    ..options = BaseOptions(
      baseUrl: ApiEndpoints.baseUrl,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      validateStatus: (status) => true,
    );
  final _logger = Logger('MenuService');

  Future<List<Makanan>> getMakanan(String token) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer $token';
      _logger.info('Fetching makanan with token: $token');

      final response = await _dio.get(ApiEndpoints.getMakanan);
      _logger.info('Makanan response: ${response.data}');

      if (response.statusCode == 200) {
        if (response.data is Map<String, dynamic>) {
          List<dynamic> data = response.data['data'];
          _logger.info('Makanan data: $data');
          return data.map((json) => Makanan.fromJson(json)).toList();
        } else {
          throw Exception('Format response tidak valid');
        }
      } else if (response.statusCode == 401) {
        throw Exception('Sesi telah berakhir. Silakan login kembali.');
      } else {
        throw Exception(
            'Gagal mengambil data makanan: ${response.statusMessage}');
      }
    } catch (e) {
      _logger.severe('Error fetching makanan: $e');
      throw Exception('Gagal mengambil data: $e');
    }
  }

  Future<List<Minuman>> getMinuman(String token) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer $token';
      _logger.info('Fetching minuman with token: $token');

      final response = await _dio.get(ApiEndpoints.getMinuman);
      _logger.info('Minuman response: ${response.data}');

      if (response.statusCode == 200) {
        if (response.data is Map<String, dynamic>) {
          List<dynamic> data = response.data['data'];
          _logger.info('Minuman data: $data');
          return data.map((json) => Minuman.fromJson(json)).toList();
        } else {
          throw Exception('Format response tidak valid');
        }
      } else if (response.statusCode == 401) {
        throw Exception('Sesi telah berakhir. Silakan login kembali.');
      } else {
        throw Exception(
            'Gagal mengambil data minuman: ${response.statusMessage}');
      }
    } catch (e) {
      _logger.severe('Error fetching minuman: $e');
      throw Exception('Gagal mengambil data: $e');
    }
  }
}
