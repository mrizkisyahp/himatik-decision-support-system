import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';
import '../theme/app_state.dart';

class PortalScreen extends StatefulWidget {
  const PortalScreen({super.key});

  @override
  State<PortalScreen> createState() => _PortalScreenState();
}

class _PortalScreenState extends State<PortalScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  void initState() {
    super.initState();
    // Reset state for new testing session when entering login
    AppState.instance.reset();
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _loginPressed() {
    if (_formKey.currentState!.validate()) {
      AppState.instance.login(_emailController.text);
      Navigator.pushReplacementNamed(context, '/dashboard');
    }
  }

  void _loginAsCandidate() {
    AppState.instance.login('nizar@stu.pnj.ac.id');
    Navigator.pushReplacementNamed(context, '/dashboard');
  }

  void _loginAsReviewer() {
    AppState.instance.login('fikri@pnj.ac.id');
    Navigator.pushReplacementNamed(context, '/dashboard');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 18.0),
            child: Form(
              key: _formKey,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  // Logo
                  Center(
                    child: Image.asset(
                      'assets/img/logo.png',
                      width: 120,
                      height: 120,
                    ),
                  ),
                  const SizedBox(height: 18), // Normal gap

                  // Title (Max size 32)
                  Text(
                    'Masuk Akun HIMATIK PNJ',
                    style: GoogleFonts.dmSans(
                      fontSize: 32,
                      fontWeight: FontWeight.bold,
                      color: AppColors.primary1,
                      height: 1.2,
                    ),
                  ),
                  const SizedBox(height: 18), // Normal gap

                  // Email Input
                  // Label & input gap: 6
                  Text(
                    'Email',
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: AppColors.primary2,
                    ),
                  ),
                  const SizedBox(height: 6),
                  TextFormField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    textInputAction: TextInputAction.next,
                    style: GoogleFonts.dmSans(fontSize: 16),
                    decoration: const InputDecoration(
                      prefixIcon: Icon(LucideIcons.mail, size: 20),
                      hintText: 'Masukkan Email',
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Email tidak boleh kosong';
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
                  const SizedBox(height: 6),
                  TextFormField(
                    controller: _passwordController,
                    obscureText: _obscurePassword,
                    textInputAction: TextInputAction.done,
                    style: GoogleFonts.dmSans(fontSize: 16),
                    onFieldSubmitted: (_) => _loginPressed(),
                    decoration: InputDecoration(
                      prefixIcon: const Icon(LucideIcons.lock, size: 20),
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
                      return null;
                    },
                  ),
                  const SizedBox(height: 18), // Normal gap

                  // Login Button
                  ElevatedButton(
                    onPressed: _loginPressed,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      elevation: 0,
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: Text(
                      'Masuk ke Aplikasi',
                      style: GoogleFonts.dmSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12), // Intermediate gap

                  // Registration helper link
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        'Ingin mendaftar menjadi anggota?',
                        style: GoogleFonts.dmSans(
                          fontSize: 12,
                          color: AppColors.tertiary4,
                        ),
                      ),
                      const SizedBox(width: 6), // label to sublabel/link
                      GestureDetector(
                        onTap: () {
                          Navigator.pushNamed(context, '/registrationAccount');
                        },
                        child: Text(
                          'Daftar Sekarang',
                          style: GoogleFonts.dmSans(
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary,
                            decoration: TextDecoration.underline,
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 18), // Normal gap
                  
                  // Divider for Shortcuts
                  Row(
                    children: [
                      const Expanded(child: Divider(color: AppColors.primary8)),
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 12.0),
                        child: Text(
                          'UJI ALUR (TEST FLOW)',
                          style: GoogleFonts.dmSans(
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary4,
                          ),
                        ),
                      ),
                      const Expanded(child: Divider(color: AppColors.primary8)),
                    ],
                  ),
                  const SizedBox(height: 12), // Intermediate gap

                  // Helper buttons for role testing
                  OutlinedButton.icon(
                    onPressed: _loginAsCandidate,
                    icon: const Icon(LucideIcons.user, size: 16),
                    label: Text(
                      'Masuk sebagai Kandidat (Nizar)',
                      style: GoogleFonts.dmSans(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: AppColors.primary,
                      side: const BorderSide(color: AppColors.primary8),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  OutlinedButton.icon(
                    onPressed: _loginAsReviewer,
                    icon: const Icon(LucideIcons.shieldAlert, size: 16),
                    label: Text(
                      'Masuk sebagai Reviewer (Fikri)',
                      style: GoogleFonts.dmSans(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: AppColors.secondary4,
                      side: const BorderSide(color: AppColors.secondary7),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
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
