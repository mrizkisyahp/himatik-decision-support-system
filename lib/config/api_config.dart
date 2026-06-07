class ApiConfig {
  ApiConfig._();

  // For Android Emulator, 10.0.2.2 points to host's localhost (0.0.0.0)
  // For iOS emulator or web, use localhost (127.0.0.1)
  static const String baseUrl = 'http://10.0.2.2:8000/api';

  static const String login = '/login';
  static const String register = '/register';
  static const String verifyOtp = '/email/verify-otp';
  static const String resendOtp = '/email/resend-otp';
  static const String logout = '/logout';
  static const String me = '/me';
}
