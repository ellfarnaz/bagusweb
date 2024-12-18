import 'package:flutter/material.dart';
import '../models/transaksi_model.dart';
import '../services/transaksi_service.dart';
import 'package:logging/logging.dart';

class TransaksiProvider extends ChangeNotifier {
  final TransaksiService _transaksiService = TransaksiService();
  List<Transaksi> _transaksi = [];
  Transaksi? _selectedTransaksi;
  bool _isLoading = false;
  final _logger = Logger('TransaksiProvider');

  List<Transaksi> get transaksi => _transaksi;
  Transaksi? get selectedTransaksi => _selectedTransaksi;
  bool get isLoading => _isLoading;

  Future<void> fetchTransaksi(String token) async {
    _isLoading = true;
    notifyListeners();

    try {
      _transaksi = await _transaksiService.getTransaksi(token);
    } catch (e) {
      _logger.warning('Error fetching transaksi: ${e.toString()}');
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<void> fetchTransaksiDetail(String token, int id) async {
    _isLoading = true;
    notifyListeners();

    try {
      _selectedTransaksi =
          await _transaksiService.getTransaksiDetail(token, id);
    } catch (e) {
      _logger.severe('Error fetching transaksi detail: ${e.toString()}');
    }

    _isLoading = false;
    notifyListeners();
  }
}
