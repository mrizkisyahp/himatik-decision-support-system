import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'screens/carousel_screen.dart';
import 'screens/portal_screen.dart';
import 'screens/registration_account_screen.dart';
import 'screens/verification_screen.dart';
import 'services/auth_service.dart';
import 'theme/app_colors.dart';

void main() {
  runApp(const MainApp());
}

class MainApp extends StatelessWidget {
  const MainApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'HIMATIK DSS Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        primaryColor: AppColors.primary,
        scaffoldBackgroundColor: AppColors.tertiary,
        appBarTheme: const AppBarTheme(
          backgroundColor: AppColors.tertiary,
          elevation: 0,
          scrolledUnderElevation: 0,
          iconTheme: IconThemeData(color: AppColors.primary1),
          shape: Border(
            bottom: BorderSide(
              color: AppColors.primary8,
              width: 1.0,
            ),
          ),
        ),
        textTheme: GoogleFonts.dmSansTextTheme(
          Theme.of(context).textTheme,
        ),
        inputDecorationTheme: InputDecorationTheme(
          filled: true,
          fillColor: Colors.white,
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: AppColors.tertiary6),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: AppColors.tertiary6),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: AppColors.primary, width: 1.5),
          ),
          contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        ),
      ),
      initialRoute: '/carousel',
      routes: {
        '/carousel': (context) => const CarouselScreen(),
        '/login': (context) => const PortalScreen(),
        '/registrationAccount': (context) => const RegistrationAccount(),
        '/verification': (context) => const VerificationScreen(),
        '/dashboard': (context) => const DashboardPlaceholderScreen(),
      },
    );
  }
}

// Temporary placeholder for dashboard after successful flow
class DashboardPlaceholderScreen extends StatelessWidget {
  const DashboardPlaceholderScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              Icons.dashboard_outlined,
              size: 64,
              color: AppColors.primary,
            ),
            const SizedBox(height: 16),
            const Text(
              'Dashboard Calon Anggota',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: AppColors.primary1,
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              'Akun Anda berhasil diverifikasi!',
              style: TextStyle(
                fontSize: 14,
                color: AppColors.tertiary5,
              ),
            ),
            const SizedBox(height: 32),
            ElevatedButton(
              onPressed: () async {
                // Logout action
                final response = await AuthService().logout();
                if (context.mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text(response['message'] as String)),
                  );
                  Navigator.pushReplacementNamed(context, '/login');
                }
              },
              child: const Text('Keluar'),
            ),
          ],
        ),
      ),
    );
  }
}
