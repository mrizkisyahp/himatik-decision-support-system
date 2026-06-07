import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

class ApiService {
  static const String _tokenKey = 'auth_token';
  
  // Singleton instance
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  Future<Map<String, String>> _getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(_tokenKey);
    
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }

    return headers;
  }

  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
  }

  Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  Future<http.Response> get(String endpoint) async {
    final headers = await _getHeaders();
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    return await http.get(url, headers: headers);
  }

  Future<http.Response> post(String endpoint, Map<String, dynamic> body) async {
    final headers = await _getHeaders();
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    return await http.post(
      url,
      headers: headers,
      body: jsonEncode(body),
    );
  }

  Future<http.Response> postMultipart(
    String endpoint,
    Map<String, String> fields,
    Map<String, List<int>> fileBytes,
    Map<String, String> fileNames,
  ) async {
    final headers = await _getHeaders();
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    final request = http.MultipartRequest('POST', url);

    headers.forEach((key, value) {
      if (key.toLowerCase() != 'content-type') {
        request.headers[key] = value;
      }
    });

    request.fields.addAll(fields);

    fileBytes.forEach((key, bytes) {
      final fileName = fileNames[key] ?? '$key.png';
      request.files.add(
        http.MultipartFile.fromBytes(
          key,
          bytes,
          filename: fileName,
        ),
      );
    });

    final streamedResponse = await request.send();
    return await http.Response.fromStream(streamedResponse);
  }
}

