import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import '../theme/app_colors.dart';
import '../widgets/page_indicator.dart';

class CarouselScreen extends StatefulWidget {
  const CarouselScreen({super.key});

  @override
  State<CarouselScreen> createState() => _CarouselScreenState();
}

class _CarouselScreenState extends State<CarouselScreen> {
  final PageController _pageController = PageController();
  final AuthService _authService = AuthService();
  int _currentIndex = 0;

  final List<Map<String, String>> _slides = [
    {
      'title': 'Selamat Datang di Aplikasi HIMATIK PNJ',
      'body': 'Decision Support System untuk pemilihan anggota dan kepengurusan Himpunan Mahasiswa Teknik Informatika dan Komputer PNJ.',
      'type': 'welcome',
    },
    {
      'title': 'Tentang Kami',
      'body': 'Himpunan Mahasiswa Teknik Informatika dan Komputer (HIMATIK) PNJ adalah organisasi kemahasiswaan tingkat program studi yang berfungsi sebagai wadah aspirasi dan pengembangan potensi mahasiswa.',
      'type': 'about',
    },
    {
      'title': 'Visi & Misi',
      'body': 'Visi:\nMenjadikan HIMATIK PNJ sebagai organisasi yang profesional, inovatif, dan kontributif secara internal maupun eksternal.\n\nMisi:\n1. Membangun kebersamaan dan kekeluargaan.\n2. Mengoptimalkan minat bakat dan akademik.\n3. Mewujudkan tata kelola organisasi yang transparan.\n4. Menjalin kolaborasi strategis dengan berbagai pihak.',
      'type': 'vision',
    },
  ];

  @override
  void initState() {
    super.initState();
    _checkFirstTime();
  }

  Future<void> _checkFirstTime() async {
    final bool firstTime = await _authService.isFirstTime();
    if (!firstTime && mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  }

  Future<void> _finishOnboarding() async {
    await _authService.setFirstTimeDone();
    if (mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          // Background Image with dark overlay
          Positioned.fill(
            child: Image.asset(
              'assets/img/bg_blur.png',
              fit: BoxFit.cover,
            ),
          ),
          Positioned.fill(
            child: Container(
              color: AppColors.primary.withOpacity(0.85),
            ),
          ),
          // Page Content
          SafeArea(
            child: Column(
              children: [
                Expanded(
                  child: PageView.builder(
                    controller: _pageController,
                    onPageChanged: (index) {
                      setState(() {
                        _currentIndex = index;
                      });
                    },
                    itemCount: _slides.length,
                    itemBuilder: (context, index) {
                      final slide = _slides[index];
                      return Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 32.0),
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            if (slide['type'] == 'welcome') ...[
                              Image.asset(
                                'assets/img/logo.png',
                                height: 120,
                              ),
                              const SizedBox(height: 40),
                            ],
                            Text(
                              slide['title']!,
                              textAlign: TextAlign.center,
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 24,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 24),
                            Text(
                              slide['body']!,
                              textAlign: slide['type'] == 'vision' ? TextAlign.left : TextAlign.center,
                              style: TextStyle(
                                color: Colors.white.withOpacity(0.9),
                                fontSize: 16,
                                height: 1.6,
                              ),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
                ),
                // Page Indicator
                PageIndicator(
                  count: _slides.length,
                  currentIndex: _currentIndex,
                ),
                const SizedBox(height: 48),
                // Bottom controls
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 24.0),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      // Skip button (Only show if not on last page)
                      _currentIndex < _slides.length - 1
                          ? TextButton(
                              onPressed: _finishOnboarding,
                              child: const Text(
                                'Lewat >|',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 16,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            )
                          : const SizedBox(width: 80),
                      // Next/Login button
                      TextButton(
                        onPressed: () {
                          if (_currentIndex < _slides.length - 1) {
                            _pageController.nextPage(
                              duration: const Duration(milliseconds: 300),
                              curve: Curves.easeInOut,
                            );
                          } else {
                            _finishOnboarding();
                          }
                        },
                        child: Text(
                          _currentIndex < _slides.length - 1 ? 'Lanjut →' : 'Ke Portal Login →',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
