import 'package:flutter/foundation.dart';
import '../models/cart_item_model.dart';
import '../models/makanan_model.dart';
import '../models/minuman_model.dart';

class CartProvider extends ChangeNotifier {
  final List<CartItem> _items = [];

  List<CartItem> get items => _items;

  double get totalHarga => _items.fold(0, (sum, item) => sum + item.totalHarga);

  int get itemCount => _items.length;

  void addMakanan(Makanan makanan) {
    final existingIndex =
        _items.indexWhere((item) => item.makananId == makanan.id);

    if (existingIndex >= 0) {
      _items[existingIndex].jumlah++;
    } else {
      _items.add(
        CartItem(
          makananId: makanan.id,
          nama: makanan.nama,
          harga: makanan.harga,
          gambar: makanan.gambar,
        ),
      );
    }
    notifyListeners();
  }

  void addMinuman(Minuman minuman) {
    final existingIndex =
        _items.indexWhere((item) => item.minumanId == minuman.id);

    if (existingIndex >= 0) {
      _items[existingIndex].jumlah++;
    } else {
      _items.add(
        CartItem(
          minumanId: minuman.id,
          nama: minuman.nama,
          harga: minuman.harga,
          gambar: minuman.gambar,
        ),
      );
    }
    notifyListeners();
  }

  void removeItem(CartItem item) {
    _items.remove(item);
    notifyListeners();
  }

  void incrementItem(CartItem item) {
    item.jumlah++;
    notifyListeners();
  }

  void decrementItem(CartItem item) {
    if (item.jumlah > 1) {
      item.jumlah--;
    } else {
      _items.remove(item);
    }
    notifyListeners();
  }

  void clearCart() {
    _items.clear();
    notifyListeners();
  }
}
