import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';
import '../theme/app_state.dart';

class RegistrationAccount extends StatefulWidget {
  const RegistrationAccount({super.key});

  @override
  State<RegistrationAccount> createState() => _RegistrationAccountState();
}

class _RegistrationAccountState extends State<RegistrationAccount> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _obscurePassword = true;
  bool _obscureConfirmPassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _nextPressed() {
    if (_formKey.currentState!.validate()) {
      // Temporarily store register email in appstate to verify it
      AppState.instance.email = _emailController.text.trim();
      Navigator.pushNamed(context, '/verification');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: Row(
          children: [
            const SizedBox(width: 8),
            IconButton(
              icon: const Icon(LucideIcons.arrowLeft, color: AppColors.primary1),
              onPressed: () => Navigator.pop(context),
            ),
          ],
        ),
        leadingWidth: 56,
        titleSpacing: 0,
        title: GestureDetector(
          onTap: () => Navigator.pop(context),
          child: Text(
            'Kembali',
            style: GoogleFonts.dmSans(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: AppColors.primary1,
            ),
          ),
        ),
      ),
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24.0),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  // Title (Max size 32)
                  Text(
                    'Daftar Akun HIMATIK PNJ',
                    style: GoogleFonts.dmSans(
                      fontSize: 32,
                      fontWeight: FontWeight.bold,
                      color: AppColors.primary1,
                      height: 1.2,
                    ),
                  ),
                  const SizedBox(height: 6), // Label to sublabel gap

                  // Subtitle
                  Text(
                    'Buat akun HIMATIK PNJ untuk melanjutkan mendaftarkan diri sebagai calon anggota HIMATIK PNJ!',
                    style: GoogleFonts.dmSans(
                      fontSize: 12,
                      color: AppColors.tertiary4,
                      height: 1.4,
                    ),
                  ),
                  const SizedBox(height: 18), // Normal gap

                  // Email Input
                  Text(
                    'Email',
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: AppColors.primary2,
                    ),
                  ),
                  const SizedBox(height: 6), // Label to input gap
                  TextFormField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    textInputAction: TextInputAction.next,
                    style: GoogleFonts.dmSans(fontSize: 16),
                    decoration: const InputDecoration(
                      hintText: 'Masukkan Email',
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Email tidak boleh kosong';
                      }
                      if (!value.contains('@')) {
                        return 'Format email tidak valid';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 12), // Intermediate gap

                  // Password Input
                  Text(
                    'Kata Sandi',
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: AppColors.primary2,
                    ),
                  ),
                  const SizedBox(height: 6), // Label to input gap
                  TextFormField(
                    controller: _passwordController,
                    obscureText: _obscurePassword,
                    textInputAction: TextInputAction.next,
                    style: GoogleFonts.dmSans(fontSize: 16),
                    decoration: InputDecoration(
                      hintText: 'Masukkan Kata Sandi',
                      suffixIcon: IconButton(
                        icon: Icon(
                          _obscurePassword ? LucideIcons.eyeOff : LucideIcons.eye,
                          size: 20,
                          color: AppColors.tertiary5,
                        ),
                        onPressed: () {
                          setState(() {
                            _obscurePassword = !_obscurePassword;
                          });
                        },
                      ),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Kata sandi tidak boleh kosong';
                      }
                      if (value.length < 6) {
                        return 'Kata sandi minimal 6 karakter';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 12), // Intermediate gap

                  // Confirm Password Input
                  Text(
                    'Konfirmasi Kata Sandi',
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: AppColors.primary2,
                    ),
                  ),
                  const SizedBox(height: 6), // Label to input gap
                  TextFormField(
                    controller: _confirmPasswordController,
                    obscureText: _obscureConfirmPassword,
                    textInputAction: TextInputAction.done,
                    style: GoogleFonts.dmSans(fontSize: 16),
                    onFieldSubmitted: (_) => _nextPressed(),
                    decoration: InputDecoration(
                      hintText: 'Masukkan Kata Sandi',
                      suffixIcon: IconButton(
                        icon: Icon(
                          _obscureConfirmPassword ? LucideIcons.eyeOff : LucideIcons.eye,
                          size: 20,
                          color: AppColors.tertiary5,
                        ),
                        onPressed: () {
                          setState(() {
                            _obscureConfirmPassword = !_obscureConfirmPassword;
                          });
                        },
                      ),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Konfirmasi kata sandi tidak boleh kosong';
                      }
                      if (value != _passwordController.text) {
                        return 'Kata sandi tidak sama';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 32), // Custom spacing to separate form from button

                  // Next Button
                  ElevatedButton(
                    onPressed: _nextPressed,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      elevation: 0,
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          'Berikutnya',
                          style: GoogleFonts.dmSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(width: 8),
                        const Icon(LucideIcons.arrowRight, size: 18),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
