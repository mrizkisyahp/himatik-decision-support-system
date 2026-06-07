import 'dart:async';
import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/auth_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';
import '../widgets/app_loading.dart';

class VerificationScreen extends StatefulWidget {
  const VerificationScreen({super.key});

  @override
  State<VerificationScreen> createState() => _VerificationScreenState();
}

class _VerificationScreenState extends State<VerificationScreen> {
  final TextEditingController _otpController = TextEditingController();
  final AuthService _authService = AuthService();

  bool _isLoading = false;
  String? _otpError;
  int _cooldownSeconds = 60;
  Timer? _timer;

  @override
  void initState() {
    super.initState();
    _startCooldown();
  }

  @override
  void dispose() {
    _timer?.cancel();
    _otpController.dispose();
    super.dispose();
  }

  void _startCooldown() {
    setState(() {
      _cooldownSeconds = 60;
    });
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_cooldownSeconds > 0) {
        setState(() {
          _cooldownSeconds--;
        });
      } else {
        _timer?.cancel();
      }
    });
  }

  void _resendCode() async {
    if (_cooldownSeconds > 0) return;

    setState(() {
      _isLoading = true;
    });

    final response = await _authService.resendOtp();

    setState(() {
      _isLoading = false;
    });

    if (response['success'] == true) {
      _startCooldown();
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: Colors.green,
        ),
      );
    } else {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: AppColors.red,
        ),
      );
    }
  }

  void _verifyOtp() async {
    setState(() {
      _otpError = null;
    });

    final String otp = _otpController.text.trim();

    if (otp.isEmpty) {
      setState(() {
        _otpError = 'OTP wajib diisi';
      });
      return;
    } else if (otp.length != 6 || int.tryParse(otp) == null) {
      setState(() {
        _otpError = 'Kode OTP harus berupa 6 digit angka';
      });
      return;
    }

    setState(() {
      _isLoading = true;
    });

    final response = await _authService.verifyOtp(otp);

    setState(() {
      _isLoading = false;
    });

    if (response['success'] == true) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: Colors.green,
        ),
      );
      // On success, redirect to dashboard or registration details
      final String nextStep = response['next_step'] as String;
      if (nextStep == 'candidate_registration') {
        Navigator.pushReplacementNamed(context, '/dashboard');
      } else {
        Navigator.pushReplacementNamed(context, '/dashboard');
      }
    } else {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: AppColors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return AppLoadingOverlay(
      isLoading: _isLoading,
      child: Scaffold(
        backgroundColor: AppColors.tertiary,
        appBar: AppBar(
          title: const Text(
            'Verifikasi Email',
            style: TextStyle(
              color: AppColors.primary1,
              fontWeight: FontWeight.bold,
              fontSize: 18,
            ),
          ),
          leading: IconButton(
            icon: const Icon(LucideIcons.arrowLeft),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 32.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Verifikasi Email Anda',
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary1,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Kami telah mengirimkan kode verifikasi 6 digit ke email pendaftaran Anda. Silakan masukkan kode tersebut di bawah ini.',
                  style: TextStyle(
                    fontSize: 14,
                    color: AppColors.tertiary4,
                  ),
                ),
                const SizedBox(height: 40),
                AppTextField(
                  label: 'Kode OTP',
                  placeholder: 'Masukkan 6 digit OTP',
                  controller: _otpController,
                  keyboardType: TextInputType.number,
                  prefixIcon: LucideIcons.check,
                  errorText: _otpError,
                ),
                const SizedBox(height: 24),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      _cooldownSeconds > 0
                          ? 'Kirim ulang kode dalam ($_cooldownSeconds) detik'
                          : 'Tidak menerima kode? ',
                      style: const TextStyle(
                        fontSize: 14,
                        color: AppColors.tertiary4,
                      ),
                    ),
                    if (_cooldownSeconds == 0)
                      AppTextButton(
                        text: 'Kirim Ulang',
                        onPressed: _resendCode,
                      ),
                  ],
                ),
                const SizedBox(height: 40),
                AppPrimaryButton(
                  text: 'Verifikasi ✓',
                  onPressed: _verifyOtp,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
