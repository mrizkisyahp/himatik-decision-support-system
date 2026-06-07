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

  Future<Map<String, dynamic>> getGradingDetails(int candidateId, int departmentId) async {
    try {
      final response = await _apiService.get('/interviewer/grade/$candidateId/$departmentId');
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'candidate': data['candidate'],
          'department': data['department'],
          'criteria': data['criteria'] as List<dynamic>,
          'existing_scores': data['existing_scores'] as Map<String, dynamic>?,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal memuat kriteria penilaian',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> submitScores({
    required int candidateId,
    required int departmentId,
    required Map<String, int> scores,
    String? notes,
  }) async {
    try {
      // Convert scores key to String if not already, to match Laravel format
      final formattedScores = <String, int>{};
      scores.forEach((key, value) {
        formattedScores[key] = value;
      });

      final response = await _apiService.post(
        '/interviewer/grade/$candidateId/$departmentId',
        {
          'scores': formattedScores,
          'global_notes': notes,
        },
      );
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'] ?? 'Nilai berhasil disimpan!',
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal menyimpan nilai',
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

