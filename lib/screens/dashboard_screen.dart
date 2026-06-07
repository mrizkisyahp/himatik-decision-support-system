import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../models/user_model.dart';
import '../services/auth_service.dart';
import '../services/api_service.dart';
import '../services/admin_service.dart';
import '../services/reviewer_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_button.dart';


class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final AuthService _authService = AuthService();
  final AdminService _adminService = AdminService();
  final ReviewerService _reviewerService = ReviewerService();

  bool _isLoading = true;
  String? _errorMessage;
  
  // User profile loaded from /me
  UserModel? _user;
  Map<String, dynamic>? _candidateData;
  Map<String, dynamic>? _announcementData;

  // Admin specific data
  Map<String, dynamic>? _adminStats;
  List<dynamic>? _adminDepartments;
  bool _isAnnouncementsPublished = false;

  // Reviewer specific data
  List<dynamic>? _reviewerSchedules;

  @override
  void initState() {
    super.initState();
    _loadDashboardData();
  }

  Future<void> _loadDashboardData() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      // 1. Fetch user profile & candidate info
      final userProfile = await _authService.getMe();
      if (userProfile == null) {
        setState(() {
          _isLoading = false;
          _errorMessage = 'Gagal memuat profil pengguna. Silakan coba lagi.';
        });
        return;
      }

      _user = userProfile;

      // 2. Fetch role-specific data
      if (_user!.role == 'admin') {
        final statsResponse = await _adminService.getStats();
        if (statsResponse['success'] == true) {
          _adminStats = statsResponse['stats'] as Map<String, dynamic>;
          _adminDepartments = statsResponse['departments'] as List<dynamic>;
        }
      } else if (_user!.role == 'interviewer') {
        final scheduleResponse = await _reviewerService.getSchedules();
        if (scheduleResponse['success'] == true) {
          _reviewerSchedules = scheduleResponse['data'] as List<dynamic>;
        } else {
          _errorMessage = scheduleResponse['message'] as String?;
        }
      } else {
        // Candidate role: data already returned or can be enriched
        final response = await ApiService().get('/me');
        if (response.statusCode == 200) {
          importData(response.body);
        }
      }

      setState(() {
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
        _errorMessage = 'Terjadi kesalahan koneksi: $e';
      });
    }
  }

  void importData(String responseBody) {
    // Helper to decode candidate JSON
    try {
      final decoded = jsonDecode(responseBody) as Map<String, dynamic>;
      if (decoded['success'] == true) {
        _candidateData = decoded['candidate'] as Map<String, dynamic>?;
        _announcementData = decoded['announcement'] as Map<String, dynamic>?;
        if (_announcementData != null) {
          _isAnnouncementsPublished = _announcementData!['is_published'] == true || _announcementData!['is_published'] == 1;
        }
      }
    } catch (_) {}
  }

  Future<void> _handleLogout() async {
    setState(() {
      _isLoading = true;
    });
    final response = await _authService.logout();
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(response['message'] as String)),
      );
      Navigator.pushReplacementNamed(context, '/login');
    }
  }

  Future<void> _togglePublishAnnouncements(bool value) async {
    setState(() {
      _isLoading = true;
    });

    final response = await _adminService.publishAnnouncements(value);

    setState(() {
      _isLoading = false;
    });

    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(response['message'] as String),
        backgroundColor: response['success'] == true ? Colors.green : AppColors.red,
      ),
    );

    if (response['success'] == true) {
      setState(() {
        _isAnnouncementsPublished = value;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.tertiary,
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset(
              'assets/img/logo.png',
              height: 32,
              width: 32,
            ),
            const SizedBox(width: 12),
            Text(
              _user == null
                  ? 'Dashboard'
                  : _user!.role == 'admin'
                      ? 'Admin Portal'
                      : _user!.role == 'interviewer'
                          ? 'Reviewer Portal'
                          : 'Portal Anggota',
              style: const TextStyle(
                color: AppColors.primary1,
                fontWeight: FontWeight.bold,
                fontSize: 18,
              ),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(LucideIcons.logOut, color: AppColors.red),
            tooltip: 'Keluar',
            onPressed: _handleLogout,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(
                color: AppColors.primary,
              ),
            )
          : _errorMessage != null
              ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(24.0),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(LucideIcons.helpCircle, size: 64, color: AppColors.tertiary5),
                        const SizedBox(height: 16),
                        Text(
                          _errorMessage!,
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontSize: 16, color: AppColors.neutral),
                        ),
                        const SizedBox(height: 24),
                        ElevatedButton(
                          onPressed: _loadDashboardData,
                          child: const Text('Coba Lagi'),
                        ),
                      ],
                    ),
                  ),
                )
              : _buildRoleDashboard(),
    );
  }

  Widget _buildRoleDashboard() {
    if (_user == null) return const SizedBox.shrink();
    
    switch (_user!.role) {
      case 'admin':
        return _buildAdminDashboard();
      case 'interviewer':
        return _buildReviewerDashboard();
      case 'candidate':
      default:
        return _buildCandidateDashboard();
    }
  }

  // ─────────────────────────────────────────────────────────────────
  // 1. CANDIDATE DASHBOARD
  // ─────────────────────────────────────────────────────────────────
  Widget _buildCandidateDashboard() {
    final bool hasProfile = _candidateData != null;
    final String status = _candidateData?['status'] as String? ?? 'registered';
    
    // Check if announcements are published and outcome is decided
    final bool isPublished = _isAnnouncementsPublished && _announcementData != null && _announcementData!['status'] != 'pending';
    final String annStatus = _announcementData?['status'] as String? ?? 'pending';

    String assignedDeptName = 'Departemen';
    final int? assignedDeptId = _announcementData?['assigned_department_id'] as int?;
    if (assignedDeptId != null && _candidateData?['department_choices'] != null) {
      for (final choice in _candidateData!['department_choices']) {
        final dept = choice['department'];
        if (dept != null && dept['id'] == assignedDeptId) {
          assignedDeptName = dept['name'] as String;
          break;
        }
      }
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Welcome Card
          Card(
            elevation: 0,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: AppColors.primary8, width: 1),
            ),
            color: Colors.white,
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Row(
                children: [
                  const CircleAvatar(
                    radius: 30,
                    backgroundColor: AppColors.primary10,
                    child: Icon(LucideIcons.user, size: 30, color: AppColors.primary),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Halo, ${_user?.name ?? "Calon Anggota"}!',
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary1,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          _user?.email ?? '',
                          style: const TextStyle(
                            fontSize: 14,
                            color: AppColors.tertiary5,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 24),
          
          if (isPublished) ...[
            const Text(
              'Hasil Seleksi Kepengurusan',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppColors.primary1,
              ),
            ),
            const SizedBox(height: 12),
            Card(
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
                side: BorderSide(
                  color: annStatus == 'accepted' ? Colors.green : AppColors.red,
                  width: 1.5,
                ),
              ),
              color: annStatus == 'accepted' ? Colors.green.shade50 : AppColors.lightRed,
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(
                          annStatus == 'accepted' ? LucideIcons.checkCircle : LucideIcons.helpCircle,
                          color: annStatus == 'accepted' ? Colors.green : AppColors.red,
                          size: 28,
                        ),
                        const SizedBox(width: 12),
                        Text(
                          annStatus == 'accepted' ? 'DITERIMA' : 'TIDAK DITERIMA',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: annStatus == 'accepted' ? Colors.green.shade800 : AppColors.red,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    Text(
                      annStatus == 'accepted'
                          ? 'Selamat! Anda dinyatakan LULUS seleksi kepengurusan HIMATIK PNJ periode ini dan ditempatkan di departemen/biro:\n\n$assignedDeptName\n\nSilakan menghubungi pengurus departemen untuk koordinasi lebih lanjut.'
                          : 'Terima kasih telah berpartisipasi dalam seleksi kepengurusan HIMATIK PNJ. Mohon maaf, saat ini Anda belum dapat bergabung bersama kami. Tetap semangat dan jangan pernah menyerah!',
                      style: TextStyle(
                        fontSize: 15,
                        color: annStatus == 'accepted' ? Colors.green.shade900 : AppColors.neutral,
                        height: 1.6,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
          ],

          const Text(
            'Status Pendaftaran',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppColors.primary1,
            ),
          ),
          const SizedBox(height: 12),
          // Status Box
          Card(
            elevation: 0,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: AppColors.primary8, width: 1),
            ),
            color: Colors.white,
            child: Padding(
              padding: const EdgeInsets.all(24.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Icon(
                        hasProfile ? LucideIcons.checkCircle : LucideIcons.helpCircle,
                        color: hasProfile ? Colors.green : AppColors.yellow,
                        size: 24,
                      ),
                      const SizedBox(width: 12),
                      Text(
                        !hasProfile
                            ? 'Identitas Belum Lengkap'
                            : status == 'registered'
                                ? 'Identitas Terkirim'
                                : status == 'scheduled'
                                    ? 'Jadwal Wawancara Terpilih'
                                    : 'Tahap Evaluasi',
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppColors.primary1,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Text(
                    !hasProfile
                        ? 'Silakan lengkapi formulir identitas akun Anda untuk melanjutkan proses seleksi kepengurusan.'
                        : status == 'registered'
                            ? 'Profil Anda telah terdaftar. Silakan pilih jadwal wawancara yang tersedia.'
                            : status == 'scheduled'
                                ? 'Anda telah memilih jadwal wawancara. Sesi wawancara Anda akan dilakukan sesuai waktu terpilih.'
                                : 'Proses wawancara selesai. Jawaban Anda sedang dievaluasi oleh tim penguji menggunakan DSS.',
                    style: const TextStyle(
                      fontSize: 14,
                      color: AppColors.tertiary4,
                      height: 1.5,
                    ),
                  ),
                  if (!hasProfile || (hasProfile && status == 'registered')) ...[
                    const SizedBox(height: 20),
                    AppPrimaryButton(
                      text: !hasProfile ? 'Lengkapi Profil Sekarang' : 'Pilih Jadwal Wawancara',
                      onPressed: () async {
                        final routeName = !hasProfile
                            ? '/candidate/register-profile'
                            : '/candidate/select-schedule';
                        await Navigator.pushNamed(context, routeName);
                        _loadDashboardData();
                      },
                    ),
                  ],
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  // ─────────────────────────────────────────────────────────────────
  // 2. REVIEWER DASHBOARD
  // ─────────────────────────────────────────────────────────────────
  Widget _buildReviewerDashboard() {
    final schedules = _reviewerSchedules ?? [];

    return Padding(
      padding: const EdgeInsets.all(24.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header info
          const Text(
            'Jadwal Wawancara Departemen',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: AppColors.primary1,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            'Daftar slot wawancara yang aktif di departemen Anda.',
            style: const TextStyle(
              fontSize: 14,
              color: AppColors.tertiary5,
            ),
          ),
          const SizedBox(height: 20),
          Expanded(
            child: schedules.isEmpty
                ? const Center(
                    child: Text(
                      'Tidak ada jadwal wawancara yang tersedia.',
                      style: TextStyle(color: AppColors.tertiary5),
                    ),
                  )
                : ListView.builder(
                    itemCount: schedules.length,
                    itemBuilder: (context, index) {
                      final item = schedules[index];
                      final date = item['date'] as String? ?? '-';
                      final startTime = item['start_time'] as String? ?? '-';
                      final endTime = item['end_time'] as String? ?? '-';
                      final isBlocked = item['is_blocked'] as bool? ?? false;
                      final booking = item['booking'] as Map<String, dynamic>?;

                      final hasCandidate = booking != null && booking['candidate'] != null;
                      final candidateUser = hasCandidate ? booking['candidate']['user'] as Map<String, dynamic>? : null;
                      final candidateName = candidateUser != null ? candidateUser['name'] as String? : 'Tidak Ada';
                      final candidateNim = hasCandidate ? booking['candidate']['nim'] as String? : '-';

                      return Card(
                        elevation: 0,
                        margin: const EdgeInsets.only(bottom: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                          side: const BorderSide(color: AppColors.primary8, width: 1),
                        ),
                        color: Colors.white,
                        child: Padding(
                          padding: const EdgeInsets.all(16.0),
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Container(
                                padding: const EdgeInsets.all(12),
                                decoration: BoxDecoration(
                                  color: hasCandidate ? AppColors.primary10 : AppColors.tertiary10,
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Icon(
                                  hasCandidate ? LucideIcons.user : LucideIcons.clock,
                                  color: hasCandidate ? AppColors.primary : AppColors.tertiary5,
                                  size: 24,
                                ),
                              ),
                              const SizedBox(width: 16),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      '$startTime - $endTime',
                                      style: const TextStyle(
                                        fontWeight: FontWeight.bold,
                                        fontSize: 15,
                                        color: AppColors.primary1,
                                      ),
                                    ),
                                    const SizedBox(height: 4),
                                    Row(
                                      children: [
                                        const Icon(LucideIcons.calendar, size: 14, color: AppColors.tertiary5),
                                        const SizedBox(width: 6),
                                        Text(
                                          date,
                                          style: const TextStyle(fontSize: 13, color: AppColors.tertiary5),
                                        ),
                                      ],
                                    ),
                                    const Divider(height: 20, color: AppColors.primary8),
                                    Text(
                                      hasCandidate ? 'Pendaftar: $candidateName' : 'Slot Kosong',
                                      style: TextStyle(
                                        fontWeight: FontWeight.w600,
                                        fontSize: 14,
                                        color: hasCandidate ? AppColors.primary1 : AppColors.tertiary5,
                                      ),
                                    ),
                                    if (hasCandidate) ...[
                                      const SizedBox(height: 2),
                                      Text(
                                        'NIM: $candidateNim',
                                        style: const TextStyle(fontSize: 13, color: AppColors.tertiary5),
                                      ),
                                      const SizedBox(height: 12),
                                      AppPrimaryButton(
                                        text: 'Nilai Wawancara',
                                        onPressed: () async {
                                          final refresh = await Navigator.pushNamed(
                                            context,
                                            '/interviewer/grade',
                                            arguments: {
                                              'candidateId': booking['candidate']['id'] as int,
                                              'departmentId': item['department_id'] as int,
                                              'candidateName': candidateName,
                                              'candidateNim': candidateNim,
                                            },
                                          );
                                          if (refresh == true) {
                                            _loadDashboardData();
                                          }
                                        },
                                      ),
                                    ],
                                    if (isBlocked) ...[
                                      const SizedBox(height: 6),
                                      Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                        decoration: BoxDecoration(
                                          color: AppColors.lightRed,
                                          borderRadius: BorderRadius.circular(4),
                                        ),
                                        child: const Text(
                                          'Blocked',
                                          style: TextStyle(fontSize: 11, color: AppColors.red, fontWeight: FontWeight.bold),
                                        ),
                                      ),
                                    ],
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }

  // ─────────────────────────────────────────────────────────────────
  // 3. ADMIN DASHBOARD
  // ─────────────────────────────────────────────────────────────────
  Widget _buildAdminDashboard() {
    final stats = _adminStats ?? {};
    final depts = _adminDepartments ?? [];

    final totalCandidates = stats['total_candidates']?.toString() ?? '0';
    final totalRegistered = stats['total_registered']?.toString() ?? '0';
    final totalScheduled = stats['total_scheduled']?.toString() ?? '0';
    final totalEvaluated = stats['total_evaluated']?.toString() ?? '0';

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Headline
          const Text(
            'Ringkasan Rekrutmen',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: AppColors.primary1,
            ),
          ),
          const SizedBox(height: 16),
          // Stats Grid
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisSpacing: 16,
            mainAxisSpacing: 16,
            childAspectRatio: 1.4,
            children: [
              _buildStatCard('Total Pendaftar', totalCandidates, LucideIcons.users, AppColors.primary),
              _buildStatCard('Registrasi', totalRegistered, LucideIcons.clipboardList, AppColors.secondary),
              _buildStatCard('Terjadwal', totalScheduled, LucideIcons.calendar, AppColors.yellow),
              _buildStatCard('Terevaluasi', totalEvaluated, LucideIcons.checkCircle, Colors.green),
            ],
          ),
          const SizedBox(height: 28),
          // Announcement Toggle Card
          Card(
            elevation: 0,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: const BorderSide(color: AppColors.primary8, width: 1),
            ),
            color: Colors.white,
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
              child: Row(
                children: [
                  const Icon(LucideIcons.shield, color: AppColors.primary, size: 28),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Papan Pengumuman',
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
                        ),
                        const SizedBox(height: 2),
                        Text(
                          _isAnnouncementsPublished ? 'Hasil SPK dipublikasikan' : 'Hasil SPK masih disembunyikan',
                          style: const TextStyle(fontSize: 13, color: AppColors.tertiary5),
                        ),
                      ],
                    ),
                  ),
                  Switch(
                    value: _isAnnouncementsPublished,
                    onChanged: _togglePublishAnnouncements,
                    activeTrackColor: AppColors.primary,
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 28),
          const Text(
            'Peminat per Departemen',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppColors.primary1,
            ),
          ),
          const SizedBox(height: 12),
          // Departments List
          Card(
            elevation: 0,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            color: Colors.white,
            child: depts.isEmpty
                ? const Padding(
                    padding: EdgeInsets.all(24.0),
                    child: Center(child: Text('Tidak ada data departemen.', style: TextStyle(color: AppColors.tertiary5))),
                  )
                : ListView.separated(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: depts.length,
                    separatorBuilder: (context, index) => const Divider(height: 1, color: AppColors.primary8),
                    itemBuilder: (context, index) {
                      final item = depts[index];
                      final name = item['name'] as String? ?? '-';
                      final firstChoiceCount = item['first_choice_candidates_count']?.toString() ?? '0';
                      final secondChoiceCount = item['second_choice_candidates_count']?.toString() ?? '0';

                      return InkWell(
                        onTap: () async {
                          await Navigator.pushNamed(
                            context,
                            '/admin/decide',
                            arguments: {
                              'departmentId': item['id'] as int,
                              'departmentName': name,
                            },
                          );
                          _loadDashboardData();
                        },
                        child: Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 20.0, vertical: 16.0),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Expanded(
                                child: Row(
                                  children: [
                                    const Icon(LucideIcons.arrowRight, size: 14, color: AppColors.primary),
                                    const SizedBox(width: 8),
                                    Expanded(
                                      child: Text(
                                        name,
                                        style: const TextStyle(
                                          fontWeight: FontWeight.w600,
                                          fontSize: 14,
                                          color: AppColors.primary1,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              Row(
                                children: [
                                  Column(
                                    crossAxisAlignment: CrossAxisAlignment.end,
                                    children: [
                                      Text(
                                        'Pilihan 1: $firstChoiceCount',
                                        style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w500, color: AppColors.primary),
                                      ),
                                      const SizedBox(height: 2),
                                      Text(
                                        'Pilihan 2: $secondChoiceCount',
                                        style: const TextStyle(fontSize: 12, color: AppColors.tertiary5),
                                      ),
                                    ],
                                  ),
                                  const SizedBox(width: 8),
                                  const Icon(LucideIcons.arrowRight, size: 16, color: AppColors.tertiary5),
                                ],
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  Widget _buildStatCard(String title, String value, IconData icon, Color color) {
    return Card(
      elevation: 0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
      ),
      color: Colors.white,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary1,
                  ),
                ),
                Icon(icon, color: color, size: 22),
              ],
            ),
            Text(
              title,
              style: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w500,
                color: AppColors.tertiary5,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
