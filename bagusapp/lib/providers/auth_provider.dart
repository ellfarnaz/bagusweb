import 'dart:io';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:logging/logging.dart';
import '../models/user_model.dart';
import '../services/auth_service.dart';

class AuthProvider extends ChangeNotifier {
  User? _user;
  final AuthService _authService = AuthService();
  final _logger = Logger('AuthProvider');

  User? get user => _user;

  Future<void> register({
    required String nama,
    required String email,
    required String password,
  }) async {
    try {
      _user = await _authService.register(
        nama: nama,
        email: email,
        password: password,
      );
      notifyListeners();
    } catch (e) {
      throw Exception(e.toString());
    }
  }

  Future<void> login({
    required String email,
    required String password,
  }) async {
    try {
      _logger.info('Starting login process for email: $email');

      final user = await _authService.login(email: email, password: password);

      if (user.token == null) {
        throw Exception('Token tidak ditemukan dalam response');
      }

      _user = user;
      await saveUserData(user);
      _logger.info('Login successful for user: ${user.email}');

      notifyListeners();
    } catch (e) {
      _logger.severe('Login error: $e');
      throw Exception(e.toString());
    }
  }

  Future<void> checkLoginStatus() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    if (token != null) {
      _user = User(
        id: prefs.getInt('user_id')!,
        nama: prefs.getString('user_nama')!,
        email: prefs.getString('user_email')!,
        role: prefs.getString('user_role')!,
        token: token,
      );
      notifyListeners();
    }
  }

  Future<void> saveUserData(User user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt('user_id', user.id);
    await prefs.setString('user_nama', user.nama);
    await prefs.setString('user_email', user.email);
    await prefs.setString('user_role', user.role);
    await prefs.setString('token', user.token!);

    _logger.info('User data saved: ${user.email}');
    _logger.info('Token saved: ${user.token}');
  }

  Future<void> logout() async {
    try {
      await _authService.logout(_user!.token!);
    } catch (e) {
      _logger.log(Level.SEVERE, 'Error during logout: ${e.toString()}');
    }

    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
    _user = null;
    notifyListeners();
  }

  Future<void> updateProfile({
    required User user,
    File? imageFile,
  }) async {
    try {
      if (_user?.token == null) {
        throw Exception('Token tidak ditemukan');
      }

      _user = await _authService.updateProfile(
        token: _user!.token!,
        user: user,
        imageFile: imageFile,
      );

      // Update data di SharedPreferences
      await saveUserData(_user!);

      notifyListeners();
    } catch (e) {
      _logger.severe('Error updating profile: $e');
      throw Exception(e.toString());
    }
  }
}
