import 'dart:convert';
import 'api_service.dart';

class CandidateService {
  final ApiService _apiService = ApiService();

  // Singleton instance
  static final CandidateService _instance = CandidateService._internal();
  factory CandidateService() => _instance;
  CandidateService._internal();

  Future<Map<String, dynamic>> getDepartments() async {
    try {
      final response = await _apiService.get('/departments');
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'data': data['data'] as List<dynamic>,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal memuat departemen',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> getAvailableSchedules() async {
    try {
      final response = await _apiService.get('/schedules');
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'data': data['data'] as List<dynamic>,
          'current_booked_slot_id': data['current_booked_slot_id'] as int?,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal memuat jadwal wawancara',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> bookSchedule(int scheduleId) async {
    try {
      final response = await _apiService.post(
        '/schedules/book',
        {'schedule_id': scheduleId},
      );
      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if (response.statusCode == 200 && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'] ?? 'Jadwal berhasil dipesan',
          'booked_slot': data['booked_slot'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal memesan jadwal',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Koneksi ke server gagal: $e',
      };
    }
  }

  Future<Map<String, dynamic>> storeProfile({
    required Map<String, String> fields,
    required Map<String, List<int>> fileBytes,
    required Map<String, String> fileNames,
  }) async {
    try {
      final response = await _apiService.postMultipart(
        '/candidate/profile',
        fields,
        fileBytes,
        fileNames,
      );

      final data = jsonDecode(response.body) as Map<String, dynamic>;

      if ((response.statusCode == 200 || response.statusCode == 201) && data['success'] == true) {
        return {
          'success': true,
          'message': data['message'] ?? 'Profil berhasil dikirim!',
          'candidate': data['candidate'],
          'next_step': data['next_step'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengirim profil',
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
}
