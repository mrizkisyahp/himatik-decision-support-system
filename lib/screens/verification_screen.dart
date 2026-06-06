import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';
import '../theme/app_state.dart';

class VerificationScreen extends StatefulWidget {
  const VerificationScreen({super.key});

  @override
  State<VerificationScreen> createState() => _VerificationScreenState();
}

class _VerificationScreenState extends State<VerificationScreen> {
  final _formKey = GlobalKey<FormState>();
  final _otpController = TextEditingController();

  @override
  void dispose() {
    _otpController.dispose();
    super.dispose();
  }

  void _verifyPressed() {
    if (_formKey.currentState!.validate()) {
      // Set user role to candidate and log in
      AppState.instance.login(AppState.instance.email.isNotEmpty ? AppState.instance.email : 'nizar@stu.pnj.ac.id');
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'Email berhasil diverifikasi!',
            style: GoogleFonts.dmSans(color: Colors.white, fontSize: 12),
          ),
          backgroundColor: Colors.green,
          duration: const Duration(seconds: 2),
        ),
      );

      // Navigate straight to Candidate Profile setup (CandidateForm1)
      Navigator.pushReplacementNamed(context, '/candidate/form-1');
    }
  }

  void _resendOtp() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          'Kode OTP baru telah dikirim ke email Anda.',
          style: GoogleFonts.dmSans(color: Colors.white, fontSize: 12),
        ),
        backgroundColor: AppColors.primary,
        duration: const Duration(seconds: 2),
      ),
    );
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
                    'Verifikasi Email',
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
                    'Masukkan kode OTP yang telah diberikan ke email yang telah kamu masukkan',
                    style: GoogleFonts.dmSans(
                      fontSize: 12,
                      color: AppColors.tertiary4,
                      height: 1.4,
                    ),
                  ),
                  const SizedBox(height: 18), // Normal gap

                  // OTP Input
                  Text(
                    'Kode OTP',
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: AppColors.primary2,
                    ),
                  ),
                  const SizedBox(height: 6), // Label to input gap
                  TextFormField(
                    controller: _otpController,
                    keyboardType: TextInputType.number,
                    style: GoogleFonts.dmSans(fontSize: 16, letterSpacing: 4),
                    textAlign: TextAlign.center,
                    decoration: const InputDecoration(
                      hintText: 'Masukkan Kode OTP',
                      hintStyle: TextStyle(letterSpacing: 0),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Kode OTP tidak boleh kosong';
                      }
                      if (value.length < 4) {
                        return 'Kode OTP minimal 4 digit';
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 12), // Intermediate gap

                  // Resend Link
                  Center(
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          'Tidak terkirim? ',
                          style: GoogleFonts.dmSans(
                            fontSize: 12,
                            color: AppColors.tertiary4,
                          ),
                        ),
                        GestureDetector(
                          onTap: _resendOtp,
                          child: Text(
                            'Kirim lagi',
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
                  ),
                  const SizedBox(height: 32), // Custom spacing to separate form from button

                  // Verify Button
                  ElevatedButton(
                    onPressed: _verifyPressed,
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
                          'Verifikasi',
                          style: GoogleFonts.dmSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(width: 8),
                        const Icon(LucideIcons.checkCircle, size: 18),
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
