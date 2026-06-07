import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'config/api_config.dart';
import 'screens/carousel_screen.dart';
import 'screens/dashboard_screen.dart';
import 'screens/portal_screen.dart';
import 'screens/registration_account_screen.dart';
import 'screens/verification_screen.dart';
import 'screens/candidate_register_profile_screen.dart';
import 'screens/candidate_select_schedule_screen.dart';
import 'screens/interviewer_grade_screen.dart';
import 'screens/admin_decide_screen.dart';
import 'theme/app_colors.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await ApiConfig.detectActiveBaseUrl();
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
        '/dashboard': (context) => const DashboardScreen(),
        '/candidate/register-profile': (context) => const CandidateRegisterProfileScreen(),
        '/candidate/select-schedule': (context) => const CandidateSelectScheduleScreen(),
        '/interviewer/grade': (context) => const InterviewerGradeScreen(),
        '/admin/decide': (context) => const AdminDecideScreen(),
      },
    );
  }
}

