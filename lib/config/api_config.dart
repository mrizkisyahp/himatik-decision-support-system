import 'dart:io';
import 'package:flutter/foundation.dart';

class ApiConfig {
  ApiConfig._();

  static String get baseUrl {
    if (kIsWeb) {
      return 'http://localhost:8000/api';
    }
    if (Platform.isAndroid) {
      // 192.168.240.1 is the default gateway/host IP for Waydroid.
      // 10.0.2.2 is the default for standard Android SDK Emulator.
      // We prioritize Waydroid subnet since the current target device is Waydroid.
      return 'http://192.168.240.1:8000/api';
    }
    return 'http://localhost:8000/api';
  }

  static const String login = '/login';
  static const String register = '/register';
  static const String verifyOtp = '/email/verify-otp';
  static const String resendOtp = '/email/resend-otp';
  static const String logout = '/logout';
  static const String me = '/me';
}
