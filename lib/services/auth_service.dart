import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';
import '../models/user_model.dart';
import 'api_service.dart';

class AuthService {
  static const String _firstTimeKey = 'is_first_time';
  final ApiService _apiService = ApiService();

  // Singleton instance
  static final AuthService _instance = AuthService._internal();
  factory AuthService() => _instance;
  AuthService._internal();

  Future<bool> isFirstTime() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getBool(_firstTimeKey) ?? true;
  }

  Future<void> setFirstTimeDone() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool(_firstTimeKey, false);
  }

  Future<bool> isLoggedIn() async {
    final token = await _apiService.getToken();
    return token != null;
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _apiService.post(
        ApiConfig.login,
        {
          'email': email,
          'password': password,
        },
      );

      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        final token = data['token'] as String;
        await _apiService.saveToken(token);
        return {
          'success': true,
          'message': data['message'] ?? 'Login berhasil',
          'user': UserModel.fromJson(data['user'] as Map<String, dynamic>),
          'next_step': data['next_step'] as String? ?? 'dashboard',
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal masuk akun',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final response = await _apiService.post(
        ApiConfig.register,
        {
          'nama': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        },
      );

      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if ((response.statusCode == 200 || response.statusCode == 201) && data['success'] == true) {
        final token = data['token'] as String;
        await _apiService.saveToken(token);
        return {
          'success': true,
          'message': data['message'] ?? 'Pendaftaran berhasil',
          'user': UserModel.fromJson(data['user'] as Map<String, dynamic>),
          'next_step': data['next_step'] as String? ?? 'verify_email',
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Pendaftaran gagal',
          'errors': data['errors'] as Map<String, dynamic>?,
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> verifyOtp(String otp) async {
    try {
      final response = await _apiService.post(
        ApiConfig.verifyOtp,
        {
          'otp': otp,
        },
      );

      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'] ?? 'Email berhasil diverifikasi',
          'next_step': data['next_step'] as String? ?? 'candidate_registration',
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Kode verifikasi salah',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> resendOtp() async {
    try {
      final response = await _apiService.post(ApiConfig.resendOtp, {});
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'] ?? 'OTP berhasil dikirim ulang',
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengirim ulang OTP',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> logout() async {
    try {
      final response = await _apiService.post(ApiConfig.logout, {});
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        await _apiService.clearToken();
        return {
          'success': true,
          'message': 'Berhasil keluar',
        };
      } else {
        // Clear token locally anyway on failure/expiry
        await _apiService.clearToken();
        return {
          'success': true,
          'message': 'Keluar secara lokal',
        };
      }
    } catch (e) {
      await _apiService.clearToken();
      return {
        'success': true,
        'message': 'Keluar secara lokal',
      };
    }
  }

  Future<UserModel?> getMe() async {
    try {
      final response = await _apiService.get(ApiConfig.me);
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as Map<String, dynamic>;
        if (data['success'] == true && data['data'] != null) {
          return UserModel.fromJson(data['data'] as Map<String, dynamic>);
        }
      }
      return null;
    } catch (e) {
      return null;
    }
  }
}
