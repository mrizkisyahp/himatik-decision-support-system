import 'dart:convert';
import 'api_service.dart';

class AdminService {
  final ApiService _apiService = ApiService();

  // Singleton instance
  static final AdminService _instance = AdminService._internal();
  factory AdminService() => _instance;
  AdminService._internal();

  Future<Map<String, dynamic>> getStats() async {
    try {
      final response = await _apiService.get('/admin/stats');
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'stats': data['data']['stats'] as Map<String, dynamic>,
          'departments': data['data']['departments'] as List<dynamic>,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengambil statistik',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> publishAnnouncements(bool isPublished) async {
    try {
      final response = await _apiService.post(
        '/admin/publish',
        {'is_published': isPublished},
      );
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'] ?? 'Status publikasi berhasil diperbarui',
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal memperbarui status publikasi',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }
}
