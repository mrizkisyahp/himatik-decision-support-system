import 'dart:async';
import 'package:http/http.dart' as http;

class ApiConfig {
  ApiConfig._();

  // Default fallback URL using 127.0.0.1 instead of localhost to prevent IPv6 resolution latency
  static String activeBaseUrl = 'http://127.0.0.1:8000/api';

  static String get baseUrl => activeBaseUrl;

  static const String login = '/login';
  static const String register = '/register';
  static const String verifyOtp = '/email/verify-otp';
  static const String resendOtp = '/email/resend-otp';
  static const String logout = '/logout';
  static const String me = '/me';

  /// Auto-detects the reachable API host by checking candidates in parallel.
  /// Falls back to 127.0.0.1 if none respond.
  static Future<void> detectActiveBaseUrl() async {
    final List<String> candidates = [
      'http://127.0.0.1:8000/api',       // Localhost (Desktop / Emulator fallback)
      'http://10.0.2.2:8000/api',        // Android SDK Emulator
      'http://192.168.240.1:8000/api',   // Waydroid container gateway
      'http://192.168.2.109:8000/api',   // Physical device Wi-Fi host IP
    ];

    final StreamController<String> controller = StreamController<String>();
    
    // Start connection checks in parallel
    for (final url in candidates) {
      unawaited(() async {
        try {
          final uri = Uri.parse('$url/landing');
          final response = await http.get(uri).timeout(const Duration(milliseconds: 800));
          if (response.statusCode == 200) {
            controller.add(url);
          }
        } catch (_) {
          // Silent catch for unreachable candidates
        }
      }());
    }

    String? resolvedUrl;
    try {
      resolvedUrl = await controller.stream.first.timeout(const Duration(milliseconds: 1000));
    } catch (_) {
      // Timeout or no candidates responded
    } finally {
      await controller.close();
    }

    if (resolvedUrl != null) {
      activeBaseUrl = resolvedUrl;
    }
  }
}
