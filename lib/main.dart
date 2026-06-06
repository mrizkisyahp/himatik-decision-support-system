import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'theme/app_colors.dart';

// Import Screens
import 'screens/portal_screen.dart';
import 'screens/dropdown_demo_screen.dart';
import 'screens/carousel_screen.dart';
import 'screens/registration_account_screen.dart';
import 'screens/verification_screen.dart';
import 'screens/interview_selection_screen.dart';
import 'screens/dashboard_screen.dart';
import 'screens/candidate_screens.dart';

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
        '/candidate/form-1': (context) => const CandidateForm1(),
        '/candidate/form-2': (context) => const CandidateForm2(),
        '/candidate/form-3': (context) => const CandidateForm3(),
        '/candidate/form-4': (context) => const CandidateForm4(),
        '/candidate/form-5': (context) => const CandidateForm5(),
        '/candidate/form-6': (context) => const CandidateForm6(),
        '/candidate/form-7': (context) => const CandidateForm7(),
        '/candidate/sent': (context) => const CandidateSent(),
        '/candidate/detail': (context) => const CandidateDetailScreen(),
        '/candidate/attachments': (context) => const CandidateAttachmentsScreen(),
        '/candidate/interview-detail': (context) => const CandidateInterviewDetailScreen(),
        '/interviewSelection': (context) => const InterviewSelectionScreen(),
        '/dashboard': (context) => const DashboardScreen(),
        '/demo': (context) => const DropdownDemoScreen(),
      },
    );
  }
}
