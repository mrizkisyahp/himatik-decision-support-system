import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';

class CarouselScreen extends StatefulWidget {
  const CarouselScreen({super.key});

  @override
  State<CarouselScreen> createState() => _CarouselScreenState();
}

class _CarouselScreenState extends State<CarouselScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final int _numPages = 3;

  void _onPageChanged(int page) {
    setState(() {
      _currentPage = page;
    });
  }

  void _skip() {
    Navigator.pushReplacementNamed(context, '/login');
  }

  void _nextPage() {
    if (_currentPage < _numPages - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeIn,
      );
    } else {
      _skip();
    }
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          // Background Blur Image
          Positioned.fill(
            child: Image.asset(
              'assets/img/bg_blur.png',
              fit: BoxFit.cover,
            ),
          ),
          
          // Gradient Overlay (Primary to Transparent, Bottom to Top)
          Positioned.fill(
            child: Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.bottomCenter,
                  end: Alignment.topCenter,
                  colors: [
                    AppColors.primary.withOpacity(0.85),
                    Colors.transparent,
                  ],
                ),
              ),
            ),
          ),

          // Sliding Pages
          PageView(
            controller: _pageController,
            onPageChanged: _onPageChanged,
            children: [
              _buildWelcomePage(),
              _buildAboutPage(),
              _buildVisionMissionPage(),
            ],
          ),

          // Bottom Navigation Row & Indicators
          Positioned(
            left: 24,
            right: 24,
            bottom: 40,
            child: Column(
              children: [
                // Navigation buttons
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    // Skip Button
                    GestureDetector(
                      onTap: _skip,
                      child: Row(
                        children: [
                          Text(
                            'Lewat',
                            style: GoogleFonts.dmSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Colors.white,
                            ),
                          ),
                          const SizedBox(width: 6),
                          const Icon(
                            LucideIcons.chevronsRight,
                            color: Colors.white,
                            size: 16,
                          ),
                        ],
                      ),
                    ),

                    // Next/Done Button
                    GestureDetector(
                      onTap: _nextPage,
                      child: Row(
                        children: [
                          Text(
                            _currentPage == _numPages - 1 ? 'Ke Portal Login' : 'Lanjut',
                            style: GoogleFonts.dmSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Colors.white,
                            ),
                          ),
                          const SizedBox(width: 6),
                          const Icon(
                            LucideIcons.arrowRight,
                            color: Colors.white,
                            size: 16,
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 18), // Normal gap

                // Indicators (Horizontal segments)
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: List.generate(_numPages, (index) => _buildIndicator(index)),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildIndicator(int index) {
    final bool isActive = index == _currentPage;
    return Expanded(
      child: Container(
        height: 4,
        margin: const EdgeInsets.symmetric(horizontal: 4),
        decoration: BoxDecoration(
          color: isActive ? Colors.white : Colors.white.withOpacity(0.4),
          borderRadius: BorderRadius.circular(2),
        ),
      ),
    );
  }

  // Slide 1: Welcome Page
  Widget _buildWelcomePage() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Logo
          Image.asset(
            'assets/img/logo.png',
            width: 140,
            height: 140,
          ),
          const SizedBox(height: 18), // Normal gap

          // Welcome Title (Maximum size 32)
          Text(
            'Selamat Datang di Aplikasi HIMATIK PNJ',
            textAlign: TextAlign.center,
            style: GoogleFonts.dmSans(
              fontSize: 32,
              fontWeight: FontWeight.bold,
              color: Colors.white,
              height: 1.2,
            ),
          ),
          const SizedBox(height: 6), // Label to sublabel gap

          // Subtitle
          Text(
            'Aplikasi manajemen',
            textAlign: TextAlign.center,
            style: GoogleFonts.dmSans(
              fontSize: 16,
              color: Colors.white70,
            ),
          ),
        ],
      ),
    );
  }

  // Slide 2: Tentang Kami Page
  Widget _buildAboutPage() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Title
          Text(
            'Tentang Kami',
            style: GoogleFonts.dmSans(
              fontSize: 32,
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 12), // Intermediate gap

          // Description
          Text(
            'HIMATIK PNJ adalah lembaga kemahasiswaan formal di Jurusan Teknik Informatika dan Komputer Politeknik Negeri Jakarta. Organisasi ini bergerak di bidang keilmuan serta menjadi penggerak mahasiswa dalam meningkatkan kreativitas dan prestasi.',
            style: GoogleFonts.dmSans(
              fontSize: 16,
              color: Colors.white.withOpacity(0.9),
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }

  // Slide 3: Visi Misi Page
  Widget _buildVisionMissionPage() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24.0),
      child: Center(
        child: SingleChildScrollView(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Slide Title
              Text(
                'Visi & Misi',
                style: GoogleFonts.dmSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              const SizedBox(height: 18), // Normal gap

              // Visi Section
              Text(
                'Visi',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              const SizedBox(height: 6), // Label to sublabel gap
              Text(
                'Mewujudkan sinergi mahasiswa TIK dalam membangun HIMATIK yang berdaya, transparan, dan berpola pikir luas guna memberikan dampak nyata dan kebermanfaatan.',
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: Colors.white.withOpacity(0.9),
                  height: 1.4,
                ),
              ),
              const SizedBox(height: 12), // Intermediate gap

              // Misi Section
              Text(
                'Misi',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              const SizedBox(height: 6), // Label to sublabel gap
              _buildMisiItem('1', 'Menjadi wadah pengembangan kompetensi dan karakter mahasiswa TIK dengan menyelenggarakan program-program unggul di bidang akademik, teknologi, dan kreativitas.'),
              const SizedBox(height: 6),
              _buildMisiItem('2', 'Menjaga dan memperkuat budaya solidaritas dalam semua kegiatan HIMATIK.'),
              const SizedBox(height: 6),
              _buildMisiItem('3', 'Mendorong profesionalisme dalam kerja HIMATIK melalui transparansi dan publikasi kegiatan secara berkala.'),
              const SizedBox(height: 6),
              _buildMisiItem('4', 'Menanamkan nilai integritas dan tanggung jawab sosial sebagai dasar dalam setiap tindakan dan program HIMATIK.'),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMisiItem(String num, String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          '$num. ',
          style: GoogleFonts.dmSans(
            fontSize: 12,
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
        Expanded(
          child: Text(
            text,
            style: GoogleFonts.dmSans(
              fontSize: 12,
              color: Colors.white.withOpacity(0.9),
              height: 1.4,
            ),
          ),
        ),
      ],
    );
  }
}
