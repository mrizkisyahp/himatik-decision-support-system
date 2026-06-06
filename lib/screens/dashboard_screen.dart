import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';
import '../theme/app_state.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;
    final bool isReviewer = state.role == UserRole.reviewer;

    return Scaffold(
      key: _scaffoldKey,
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.menu, color: AppColors.primary1),
          onPressed: () => _scaffoldKey.currentState!.openDrawer(),
        ),
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 18.0),
            child: Image.asset(
              'assets/img/logo.png',
              width: 32,
              height: 32,
            ),
          )
        ],
      ),
      drawer: _buildDrawer(context),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(18.0), // Normal gap
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Date Text
              Text(
                'Jum\'at, 12 Juni 2026',
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: AppColors.tertiary4,
                  fontWeight: FontWeight.w500,
                ),
              ),
              const SizedBox(height: 6), // label to sublabel/greetings

              // Greeting (Max size 32)
              Text(
                'Halo, ${state.name}!',
                style: GoogleFonts.dmSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                  height: 1.1,
                ),
              ),
              const SizedBox(height: 18), // Normal gap

              if (isReviewer)
                _buildReviewerLayout(context)
              else if (!state.hasSubmittedRecruitment)
                _buildCandidatePreLayout(context)
              else
                _buildCandidatePostLayout(context),
            ],
          ),
        ),
      ),
    );
  }

  // Draw Menu Drawer
  Widget _buildDrawer(BuildContext context) {
    final state = AppState.instance;
    return Drawer(
      backgroundColor: AppColors.tertiary,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Drawer Header
          DrawerHeader(
            decoration: const BoxDecoration(
              color: AppColors.primary2,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                CircleAvatar(
                  backgroundColor: AppColors.primary8,
                  radius: 28,
                  child: Text(
                    state.name.isNotEmpty ? state.name[0].toUpperCase() : 'U',
                    style: GoogleFonts.dmSans(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: AppColors.primary1,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  state.namaLengkap.isNotEmpty && state.role == UserRole.candidate
                      ? state.namaLengkap
                      : state.name,
                  style: GoogleFonts.dmSans(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
                Text(
                  state.email.isNotEmpty ? state.email : 'user@pnj.ac.id',
                  style: GoogleFonts.dmSans(
                    fontSize: 12,
                    color: Colors.white70,
                  ),
                ),
              ],
            ),
          ),
          
          // Drawer Items
          ListTile(
            leading: const Icon(LucideIcons.layoutDashboard, color: AppColors.primary1),
            title: Text(
              'Dashboard',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.w500),
            ),
            onTap: () {
              Navigator.pop(context);
            },
          ),
          
          if (state.role == UserRole.candidate && state.hasSubmittedRecruitment) ...[
            ListTile(
              leading: const Icon(LucideIcons.calendar, color: AppColors.primary1),
              title: Text(
                'Pilih/Ubah Jadwal',
                style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.w500),
              ),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/interviewSelection').then((_) => setState(() {}));
              },
            ),
            ListTile(
              leading: const Icon(LucideIcons.fileText, color: AppColors.primary1),
              title: Text(
                'Formulir Pendaftaran',
                style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.w500),
              ),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/candidate/detail');
              },
            ),
            ListTile(
              leading: const Icon(LucideIcons.paperclip, color: AppColors.primary1),
              title: Text(
                'Lampiran Berkas',
                style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.w500),
              ),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/candidate/attachments');
              },
            ),
          ],
          
          const Spacer(),
          const Divider(color: AppColors.primary8),
          
          ListTile(
            leading: const Icon(LucideIcons.logOut, color: AppColors.red),
            title: Text(
              'Keluar',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.red),
            ),
            onTap: () {
              Navigator.pushReplacementNamed(context, '/login');
            },
          ),
          const SizedBox(height: 12),
        ],
      ),
    );
  }

  // --- Candidate Layout: Before submitting recruitment form ---
  Widget _buildCandidatePreLayout(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        // Jadwal Wawancara Header
        _buildSectionHeader('Jadwal Wawancara'),
        const SizedBox(height: 12), // Intermediate gap

        // Empty state card
        Container(
          padding: const EdgeInsets.symmetric(vertical: 32, horizontal: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.tertiary8),
          ),
          child: Column(
            children: [
              const Icon(
                LucideIcons.moreHorizontal,
                size: 36,
                color: AppColors.tertiary6,
              ),
              const SizedBox(height: 12), // Intermediate gap
              Text(
                'Belum Ada',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: AppColors.tertiary5,
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 18), // Normal gap

        // Daftar Rekrutmen
        _buildSectionHeader('Daftar Rekrutmen'),
        const SizedBox(height: 12), // Intermediate gap

        // Recruitment card
        Container(
          padding: const EdgeInsets.all(18),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.04),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
            border: Border.all(color: AppColors.primary8),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Open Recruitment Staff HIMATIK PNJ',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                ),
              ),
              const SizedBox(height: 12), // Intermediate gap
              Row(
                children: [
                  const Icon(LucideIcons.calendarDays, size: 16, color: AppColors.primary3),
                  const SizedBox(width: 8),
                  Text(
                    '12 Juni 2026 s.d. 14 Juni 2026',
                    style: GoogleFonts.dmSans(
                      fontSize: 12,
                      color: AppColors.tertiary4,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 18), // Normal gap
              
              // Register Button
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () {
                    // Navigate to form step 0 (CandidateForm2)
                    Navigator.pushNamed(context, '/candidate/form-2').then((_) => setState(() {}));
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    elevation: 0,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        'Daftar',
                        style: GoogleFonts.dmSans(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(width: 8),
                      const Icon(LucideIcons.clipboardList, size: 16),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // --- Candidate Layout: After submitting recruitment form ---
  Widget _buildCandidatePostLayout(BuildContext context) {
    final state = AppState.instance;
    final slot = state.getSelectedSlotDetails();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        // Jadwal Wawancara Header
        _buildSectionHeader('Jadwal Wawancara'),
        const SizedBox(height: 12),

        // Dynamic interview status card
        Container(
          padding: const EdgeInsets.all(18),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.tertiary8),
          ),
          child: slot == null
              ? Column(
                  children: [
                    Text(
                      'Silakan Pilih Jadwal Wawancara Anda',
                      style: GoogleFonts.dmSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary2,
                      ),
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.pushNamed(context, '/interviewSelection').then((_) => setState(() {}));
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColors.primary,
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                        child: Text(
                          'Pilih Jadwal',
                          style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16),
                        ),
                      ),
                    ),
                  ],
                )
              : Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Wawancara Staff HIMATIK PNJ',
                      style: GoogleFonts.dmSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary1,
                      ),
                    ),
                    const SizedBox(height: 12),
                    _buildIconLabelRow(LucideIcons.calendar, '${slot['day']}, ${slot['date']}'),
                    const SizedBox(height: 6),
                    _buildIconLabelRow(LucideIcons.clock, slot['time']),
                    const SizedBox(height: 6),
                    _buildIconLabelRow(LucideIcons.mapPin, slot['room']),
                    const SizedBox(height: 18),
                    
                    // Selengkapnya Button
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.pushNamed(context, '/candidate/interview-detail');
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColors.primary,
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              'Selengkapnya',
                              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(width: 8),
                            const Icon(LucideIcons.chevronRight, size: 16),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
        ),
        const SizedBox(height: 18),

        // Rekrutmen Status
        _buildSectionHeader('Rekrutmen'),
        const SizedBox(height: 12),

        Container(
          padding: const EdgeInsets.all(18),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppColors.tertiary8),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Pendaftaran Berhasil Dikirim',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Colors.green.shade700,
                ),
              ),
              const SizedBox(height: 6),
              Text(
                'Menunggu hasil penilaian administrasi dan wawancara.',
                style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4),
              ),
              const SizedBox(height: 18),
              
              // View application form
              SizedBox(
                width: double.infinity,
                child: OutlinedButton(
                  onPressed: () {
                    Navigator.pushNamed(context, '/candidate/detail');
                  },
                  style: OutlinedButton.styleFrom(
                    foregroundColor: AppColors.primary,
                    side: const BorderSide(color: AppColors.primary8),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: Text(
                    'Lihat Formulir Pendaftaran',
                    style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16),
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // --- Reviewer Layout: Panel for reviewers ---
  Widget _buildReviewerLayout(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        // Header Daftar Wawancara Hari Ini
        _buildSectionHeader('Daftar Wawancara Hari Ini'),
        const SizedBox(height: 12),

        // Interview 1 Card
        _buildReviewerInterviewCard(
          context,
          'Nizar Rizki Ardiansyah',
          'Sesi 3 (13:00 s.d. 14:30)',
          'Ruang AA.302',
          '1',
          Colors.blue.shade900,
          Colors.blue.shade100,
        ),
        const SizedBox(height: 12),

        // Interview 2 Card
        _buildReviewerInterviewCard(
          context,
          'Satrio Eko Saputra',
          'Sesi 4 (15:00 s.d. 16:30)',
          'Ruang AA.303',
          '2',
          Colors.orange.shade900,
          Colors.orange.shade100,
        ),
        const SizedBox(height: 12),
        
        Center(
          child: TextButton(
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text('Menampilkan semua jadwal...', style: GoogleFonts.dmSans(fontSize: 12)),
                  backgroundColor: AppColors.primary,
                ),
              );
            },
            child: Text(
              'Lihat Selengkapnya',
              style: GoogleFonts.dmSans(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
          ),
        ),
        const SizedBox(height: 12),

        // Top 3 Kandidat Section
        _buildSectionHeader('Top 3 Kandidat (DSS Rank)'),
        const SizedBox(height: 12),

        // Rank 1 (Blue background)
        _buildDssRankCard(1, 'Nizar Rizki Ardiansyah', '0.89', true),
        const SizedBox(height: 8),

        // Rank 2 (White background)
        _buildDssRankCard(2, 'Satrio Eko Saputra', '0.76', false),
        const SizedBox(height: 8),

        // Rank 3 (White background)
        _buildDssRankCard(3, 'Muhammad Farel', '0.72', false),
        const SizedBox(height: 12),
        
        Center(
          child: TextButton(
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text('Membuka peringkat DSS lengkap...', style: GoogleFonts.dmSans(fontSize: 12)),
                  backgroundColor: AppColors.primary,
                ),
              );
            },
            child: Text(
              'Lihat Selengkapnya',
              style: GoogleFonts.dmSans(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.dmSans(
        fontSize: 16,
        fontWeight: FontWeight.bold,
        color: AppColors.primary1,
      ),
    );
  }

  Widget _buildIconLabelRow(IconData icon, String label) {
    return Row(
      children: [
        Icon(icon, size: 16, color: AppColors.tertiary5),
        const SizedBox(width: 8),
        Text(
          label,
          style: GoogleFonts.dmSans(
            fontSize: 12,
            color: AppColors.tertiary4,
            fontWeight: FontWeight.w500,
          ),
        ),
      ],
    );
  }

  Widget _buildReviewerInterviewCard(
    BuildContext context,
    String name,
    String time,
    String room,
    String order,
    Color orderTextColor,
    Color orderBgColor,
  ) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.tertiary8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  name,
                  style: GoogleFonts.dmSans(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary1,
                  ),
                ),
              ),
              Container(
                width: 24,
                height: 24,
                decoration: BoxDecoration(
                  color: orderBgColor,
                  shape: BoxShape.circle,
                ),
                alignment: Alignment.center,
                child: Text(
                  order,
                  style: GoogleFonts.dmSans(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: orderTextColor,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          _buildIconLabelRow(LucideIcons.clock, time),
          const SizedBox(height: 6),
          _buildIconLabelRow(LucideIcons.mapPin, room),
          const SizedBox(height: 12),
          
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: () {
                // In a real app this opens a review form. For the demo, show details
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Text('Membuka peninjauan untuk $name...', style: GoogleFonts.dmSans(fontSize: 12)),
                    backgroundColor: AppColors.primary,
                  ),
                );
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                elevation: 0,
                padding: const EdgeInsets.symmetric(vertical: 10),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Selengkapnya', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 12)),
                  const SizedBox(width: 4),
                  const Icon(LucideIcons.chevronRight, size: 14),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDssRankCard(int rank, String name, String score, bool isHighlighted) {
    Color cardBg = isHighlighted ? AppColors.primary : Colors.white;
    Color borderCol = isHighlighted ? Colors.transparent : AppColors.tertiary8;
    Color nameColor = isHighlighted ? Colors.white : AppColors.primary1;
    Color scoreColor = isHighlighted ? Colors.white : AppColors.primary2;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      decoration: BoxDecoration(
        color: cardBg,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: borderCol),
        boxShadow: [
          if (isHighlighted)
            BoxShadow(
              color: AppColors.primary.withOpacity(0.15),
              blurRadius: 8,
              offset: const Offset(0, 4),
            ),
        ],
      ),
      child: Row(
        children: [
          // Rank Badge
          Container(
            width: 28,
            height: 28,
            decoration: BoxDecoration(
              color: rank == 1 ? AppColors.yellow : AppColors.primary,
              borderRadius: BorderRadius.circular(6),
            ),
            alignment: Alignment.center,
            child: Text(
              '$rank',
              style: GoogleFonts.dmSans(
                color: Colors.white,
                fontWeight: FontWeight.bold,
                fontSize: 14,
              ),
            ),
          ),
          const SizedBox(width: 12),
          
          // Name
          Expanded(
            child: Text(
              name,
              style: GoogleFonts.dmSans(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: nameColor,
              ),
            ),
          ),

          // DSS Score
          Text(
            score,
            style: GoogleFonts.dmSans(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: scoreColor,
            ),
          ),
        ],
      ),
    );
  }
}
