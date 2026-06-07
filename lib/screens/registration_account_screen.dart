import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/auth_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';
import '../widgets/app_loading.dart';

class RegistrationAccount extends StatefulWidget {
  const RegistrationAccount({super.key});

  @override
  State<RegistrationAccount> createState() => _RegistrationAccountState();
}

class _RegistrationAccountState extends State<RegistrationAccount> {
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _passwordConfirmController = TextEditingController();
  final AuthService _authService = AuthService();

  bool _isLoading = false;
  String? _nameError;
  String? _emailError;
  String? _passwordError;
  String? _passwordConfirmError;

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _passwordConfirmController.dispose();
    super.dispose();
  }

  void _validateAndSubmit() async {
    setState(() {
      _nameError = null;
      _emailError = null;
      _passwordError = null;
      _passwordConfirmError = null;
    });

    final String name = _nameController.text.trim();
    final String email = _emailController.text.trim();
    final String password = _passwordController.text;
    final String passwordConfirm = _passwordConfirmController.text;

    bool hasError = false;

    if (name.isEmpty) {
      setState(() {
        _nameError = 'Nama lengkap wajib diisi';
      });
      hasError = true;
    }

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
    } else if (password.length < 8) {
      setState(() {
        _passwordError = 'Password minimal terdiri dari 8 karakter';
      });
      hasError = true;
    }

    if (passwordConfirm.isEmpty) {
      setState(() {
        _passwordConfirmError = 'Konfirmasi password wajib diisi';
      });
      hasError = true;
    } else if (password != passwordConfirm) {
      setState(() {
        _passwordConfirmError = 'Konfirmasi password tidak cocok';
      });
      hasError = true;
    }

    if (hasError) return;

    setState(() {
      _isLoading = true;
    });

    final response = await _authService.register(
      name: name,
      email: email,
      password: password,
      passwordConfirmation: passwordConfirm,
    );

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
      Navigator.pushReplacementNamed(context, '/verification');
    } else {
      if (!mounted) return;
      
      // Parse backend validation errors if any
      final errors = response['errors'] as Map<String, dynamic>?;
      if (errors != null) {
        setState(() {
          if (errors.containsKey('email')) {
            _emailError = (errors['email'] as List<dynamic>).first.toString();
          }
          if (errors.containsKey('nama')) {
            _nameError = (errors['nama'] as List<dynamic>).first.toString();
          }
          if (errors.containsKey('password')) {
            _passwordError = (errors['password'] as List<dynamic>).first.toString();
          }
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response['message'] as String),
            backgroundColor: AppColors.red,
          ),
        );
      }
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
            'Daftar Akun',
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
            padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 24.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Daftar Akun HIMATIK PNJ',
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary1,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Lengkapi form berikut untuk mendaftarkan akun calon anggota Anda',
                  style: TextStyle(
                    fontSize: 14,
                    color: AppColors.tertiary4,
                  ),
                ),
                const SizedBox(height: 32),
                AppTextField(
                  label: 'Nama Lengkap',
                  placeholder: 'Masukkan nama lengkap sesuai KTP',
                  controller: _nameController,
                  prefixIcon: LucideIcons.user,
                  errorText: _nameError,
                ),
                const SizedBox(height: 18),
                AppTextField(
                  label: 'Email',
                  placeholder: 'Masukkan email aktif Anda',
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  prefixIcon: LucideIcons.mail,
                  errorText: _emailError,
                ),
                const SizedBox(height: 18),
                AppTextField(
                  label: 'Password',
                  placeholder: 'Buat password minimal 8 karakter',
                  controller: _passwordController,
                  isPassword: true,
                  prefixIcon: LucideIcons.lock,
                  errorText: _passwordError,
                ),
                const SizedBox(height: 18),
                AppTextField(
                  label: 'Konfirmasi Password',
                  placeholder: 'Masukkan kembali password Anda',
                  controller: _passwordConfirmController,
                  isPassword: true,
                  prefixIcon: LucideIcons.lock,
                  errorText: _passwordConfirmError,
                ),
                const SizedBox(height: 40),
                AppPrimaryButton(
                  text: 'Berikutnya →',
                  onPressed: _validateAndSubmit,
                ),
                const SizedBox(height: 24),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
