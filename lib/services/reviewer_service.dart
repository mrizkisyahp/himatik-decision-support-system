import 'dart:convert';
import 'api_service.dart';

class ReviewerService {
  final ApiService _apiService = ApiService();

  // Singleton instance
  static final ReviewerService _instance = ReviewerService._internal();
  factory ReviewerService() => _instance;
  ReviewerService._internal();

  Future<Map<String, dynamic>> getSchedules() async {
    try {
      final response = await _apiService.get('/interviewer/schedules');
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'data': data['data'] as List<dynamic>,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengambil jadwal wawancara',
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
