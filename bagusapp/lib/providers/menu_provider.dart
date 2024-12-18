import 'package:flutter/material.dart';
import 'package:logging/logging.dart';
import '../models/makanan_model.dart';
import '../models/minuman_model.dart';
import '../services/menu_service.dart';
import '../providers/auth_provider.dart';
import 'package:provider/provider.dart';

class MenuProvider extends ChangeNotifier {
  final MenuService _menuService = MenuService();
  final _logger = Logger('MenuProvider');

  List<Makanan> _makanan = [];
  List<Minuman> _minuman = [];
  bool _isLoading = false;
  String? _error;

  List<Makanan> get makanan => _makanan;
  List<Minuman> get minuman => _minuman;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchMakanan(BuildContext context) async {
    try {
      final token =
          Provider.of<AuthProvider>(context, listen: false).user?.token;
      if (token == null) {
        throw Exception('Token tidak ditemukan');
      }

      _isLoading = true;
      _error = null;
      notifyListeners();

      _makanan = await _menuService.getMakanan(token);
      _logger.info('Berhasil mengambil ${_makanan.length} makanan');
    } catch (e) {
      _error = e.toString();
      _logger.severe('Error fetching makanan: $e');
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchMinuman(BuildContext context) async {
    try {
      final token =
          Provider.of<AuthProvider>(context, listen: false).user?.token;
      if (token == null) {
        throw Exception('Token tidak ditemukan. Silakan login kembali.');
      }

      _isLoading = true;
      _error = null;
      notifyListeners();

      _minuman = await _menuService.getMinuman(token);
      _logger.info('Berhasil mengambil ${_minuman.length} minuman');
    } catch (e) {
      _error = e.toString();
      _logger.severe('Error fetching minuman: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
