import 'package:dio/dio.dart';
import 'package:logging/logging.dart';
import '../models/cart_item_model.dart';
import '../models/transaksi_model.dart';
import '../models/user_model.dart';
import '../constants/api_endpoints.dart';

class TransaksiService {
  final Dio _dio = Dio();
  final Logger _logger = Logger('TransaksiService');

  Future<List<Transaksi>> getTransaksi(String token) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer $token';
      _dio.options.baseUrl = ApiEndpoints.baseUrl;

      final response = await _dio.get(ApiEndpoints.getTransaksi);

      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'];
        return data.map((json) => Transaksi.fromJson(json)).toList();
      } else {
        throw Exception('Gagal mengambil daftar transaksi');
      }
    } catch (e) {
      _logger.severe('Error getting transaksi list: $e');
      throw Exception('Gagal mengambil daftar transaksi: $e');
    }
  }

  Future<Transaksi> createTransaksi({
    required String customerName,
    required String customerAlamat,
    required String customerNoHp,
    String? customerCatatan,
    required double totalHarga,
    required List<CartItem> items,
    required User user,
  }) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer ${user.token}';
      _dio.options.baseUrl = ApiEndpoints.baseUrl;

      final response = await _dio.post(
        ApiEndpoints.createTransaksi,
        data: {
          'user_id': user.id,
          'customer_name': customerName,
          'customer_alamat': customerAlamat,
          'customer_no_hp': customerNoHp,
          'customer_catatan': customerCatatan ?? '',
          'total_harga': totalHarga,
          'items': items
              .map((item) => {
                    'id': item.makananId ?? item.minumanId,
                    'type': item.makananId != null ? 'makanan' : 'minuman',
                    'jumlah': item.jumlah,
                    'harga': item.harga,
                    'total_harga': item.totalHarga,
                  })
              .toList(),
        },
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        if (response.data['success'] == true) {
          return Transaksi.fromJson(response.data['data']);
        } else {
          throw Exception(
              response.data['message'] ?? 'Gagal membuat transaksi');
        }
      } else {
        throw Exception('Gagal membuat transaksi: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      _logger.severe('DioError: ${e.message}\nResponse: ${e.response?.data}');
      throw Exception(
          e.response?.data?['message'] ?? 'Gagal membuat transaksi');
    } catch (e) {
      _logger.severe('Error: $e');
      throw Exception('Gagal membuat transaksi: $e');
    }
  }

  Future<Transaksi> getTransaksiDetail(String token, int id) async {
    try {
      _dio.options.headers['Authorization'] = 'Bearer $token';
      _dio.options.baseUrl = ApiEndpoints.baseUrl;

      final response = await _dio.get('${ApiEndpoints.getTransaksi}/$id');

      if (response.statusCode == 200) {
        return Transaksi.fromJson(response.data['data']);
      } else {
        throw Exception('Gagal mengambil detail transaksi');
      }
    } catch (e) {
      _logger.severe('Error getting transaksi detail: $e');
      throw Exception('Gagal mengambil detail transaksi: $e');
    }
  }
}
