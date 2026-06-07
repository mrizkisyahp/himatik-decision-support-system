import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/auth_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';
import '../widgets/app_loading.dart';

class PortalScreen extends StatefulWidget {
  const PortalScreen({super.key});

  @override
  State<PortalScreen> createState() => _PortalScreenState();
}

class _PortalScreenState extends State<PortalScreen> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final AuthService _authService = AuthService();

  bool _isLoading = false;
  String? _emailError;
  String? _passwordError;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _validateAndSubmit() async {
    setState(() {
      _emailError = null;
      _passwordError = null;
    });

    final String email = _emailController.text.trim();
    final String password = _passwordController.text.trim();

    bool hasError = false;

    if (email.isEmpty) {
      setState(() {
        _emailError = 'Email wajib diisi';
      });
      hasError = true;
    } else if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(email)) {
      setState(() {
        _emailError = 'Format email tidak valid';
      });
      hasError = true;
    }

    if (password.isEmpty) {
      setState(() {
        _passwordError = 'Password wajib diisi';
      });
      hasError = true;
    }

    if (hasError) return;

    setState(() {
      _isLoading = true;
    });

    final response = await _authService.login(email, password);

    setState(() {
      _isLoading = false;
    });

    if (response['success'] == true) {
      if (!mounted) return;
      
      final String nextStep = response['next_step'] as String;
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: Colors.green,
        ),
      );

      // Handle redirect based on next_step
      if (nextStep == 'verify_email') {
        Navigator.pushReplacementNamed(context, '/verification');
      } else {
        // Fallback for other steps or dashboard
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
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 48.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const SizedBox(height: 24),
                Image.asset(
                  'assets/img/logo.png',
                  height: 80,
                  width: 80,
                ),
                const SizedBox(height: 24),
                const Text(
                  'Masuk Akun HIMATIK PNJ',
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary1,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Silakan masuk menggunakan akun pendaftaran Anda',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 14,
                    color: AppColors.tertiary4,
                  ),
                ),
                const SizedBox(height: 40),
                AppTextField(
                  label: 'Email',
                  placeholder: 'Masukkan email Anda',
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  prefixIcon: LucideIcons.mail,
                  errorText: _emailError,
                ),
                const SizedBox(height: 18),
                AppTextField(
                  label: 'Password',
                  placeholder: 'Masukkan password Anda',
                  controller: _passwordController,
                  isPassword: true,
                  prefixIcon: LucideIcons.lock,
                  errorText: _passwordError,
                ),
                const SizedBox(height: 40),
                AppPrimaryButton(
                  text: 'Masuk ke Aplikasi',
                  onPressed: _validateAndSubmit,
                ),
                const SizedBox(height: 24),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      'Ingin mendaftar menjadi anggota? ',
                      style: TextStyle(
                        fontSize: 14,
                        color: AppColors.tertiary4,
                      ),
                    ),
                    AppTextButton(
                      text: 'Daftar Sekarang',
                      onPressed: () {
                        Navigator.pushNamed(context, '/registrationAccount');
                      },
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
