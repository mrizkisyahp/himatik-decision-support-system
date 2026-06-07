import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/admin_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_loading.dart';

class AdminDecideScreen extends StatefulWidget {
  const AdminDecideScreen({super.key});

  @override
  State<AdminDecideScreen> createState() => _AdminDecideScreenState();
}

class _AdminDecideScreenState extends State<AdminDecideScreen> {
  final AdminService _adminService = AdminService();

  bool _isLoading = true;
  String? _errorMessage;

  int? _departmentId;
  String? _departmentName;

  List<dynamic> _rankings = [];
  Map<String, dynamic> _announcements = {}; // Maps candidateId to announcement record

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_departmentId == null) {
      final args = ModalRoute.of(context)!.settings.arguments as Map<String, dynamic>;
      _departmentId = args['departmentId'] as int;
      _departmentName = args['departmentName'] as String?;
      _loadRankings();
    }
  }

  Future<void> _loadRankings() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final response = await _adminService.getRankings(_departmentId!);

    if (response['success'] == true) {
      setState(() {
        _rankings = response['rankings'] as List<dynamic>;
        _announcements = response['announcements'] as Map<String, dynamic>? ?? {};
        _isLoading = false;
      });
    } else {
      setState(() {
        _errorMessage = response['message'] as String?;
        _isLoading = false;
      });
    }
  }

  void _confirmDecision(int candidateId, String candidateName, String status) {
    final bool isAccept = status == 'accepted';
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Text(isAccept ? 'Terima Calon Anggota' : 'Tolak Calon Anggota'),
        content: Text(
          isAccept
              ? 'Apakah Anda yakin ingin menerima $candidateName ke dalam $_departmentName?'
              : 'Apakah Anda yakin ingin menolak $candidateName dalam seleksi ini?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal', style: TextStyle(color: AppColors.tertiary5)),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(ctx);
              _submitDecision(candidateId, status);
            },
            child: Text(
              isAccept ? 'Terima (ACC)' : 'Tolak',
              style: TextStyle(
                color: isAccept ? Colors.green : AppColors.red,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _submitDecision(int candidateId, String status) async {
    setState(() {
      _isLoading = true;
    });

    final response = await _adminService.decideCandidate(
      candidateId: candidateId,
      status: status,
      assignedDepartmentId: status == 'accepted' ? _departmentId : null,
    );

    if (response['success'] == true) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: Colors.green,
        ),
      );
      _loadRankings(); // Refresh list
    } else {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: AppColors.red,
        ),
      );
      setState(() {
        _isLoading = false;
      });
    }
  }

  Color _getRankColor(int rank) {
    if (rank == 1) return Colors.amber.shade600; // Gold
    if (rank == 2) return Colors.grey.shade400; // Silver
    if (rank == 3) return Colors.brown.shade400; // Bronze
    return AppColors.tertiary5;
  }

  @override
  Widget build(BuildContext context) {
    return AppLoadingOverlay(
      isLoading: _isLoading,
      child: Scaffold(
        backgroundColor: AppColors.tertiary,
        appBar: AppBar(
          title: Text(
            'DSS Rankings: $_departmentName',
            style: const TextStyle(color: AppColors.primary1, fontWeight: FontWeight.bold, fontSize: 18),
          ),
          leading: IconButton(
            icon: const Icon(LucideIcons.arrowLeft),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: _isLoading && _rankings.isEmpty
            ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
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
                            onPressed: _loadRankings,
                            child: const Text('Coba Lagi'),
                          ),
                        ],
                      ),
                    ),
                  )
                : Padding(
                    padding: const EdgeInsets.all(24.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Peringkat Hasil DSS',
                          style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppColors.primary1),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'Daftar kandidat yang mendaftar ke $_departmentName diurutkan berdasarkan skor Profile Matching.',
                          style: const TextStyle(fontSize: 14, color: AppColors.tertiary5),
                        ),
                        const SizedBox(height: 20),
                        Expanded(
                          child: _rankings.isEmpty
                              ? const Center(
                                  child: Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Icon(LucideIcons.clipboardList, size: 48, color: AppColors.tertiary5),
                                      SizedBox(height: 12),
                                      Text(
                                        'Tidak ada kandidat dengan penilaian di departemen ini.',
                                        textAlign: TextAlign.center,
                                        style: TextStyle(color: AppColors.tertiary5, fontSize: 14),
                                      ),
                                    ],
                                  ),
                                )
                              : ListView.builder(
                                  itemCount: _rankings.length,
                                  itemBuilder: (context, index) {
                                    final item = _rankings[index];
                                    
                                    // Extract score fields
                                    final double score = double.tryParse(item['final_score']?.toString() ?? '0') ?? 0.0;
                                    final int rankPosition = index + 1; // Show positional rank based on sorted list index
                                    
                                    final candidate = item['candidate'] as Map<String, dynamic>;
                                    final candidateId = candidate['id'] as int;
                                    final user = candidate['user'] as Map<String, dynamic>;
                                    final name = user['name'] as String? ?? '-';
                                    final nim = candidate['nim'] as String? ?? '-';

                                    // Check status from local announcements dictionary
                                    final candidateAnnStr = candidateId.toString();
                                    final hasAnnRecord = _announcements.containsKey(candidateAnnStr);
                                    final annStatus = hasAnnRecord ? _announcements[candidateAnnStr]['status'] as String? : 'pending';

                                    return Card(
                                      elevation: 0,
                                      margin: const EdgeInsets.only(bottom: 16),
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(16),
                                        side: const BorderSide(color: AppColors.primary8),
                                      ),
                                      color: Colors.white,
                                      child: Padding(
                                        padding: const EdgeInsets.all(16.0),
                                        child: Column(
                                          children: [
                                            Row(
                                              children: [
                                                // Rank Badge
                                                CircleAvatar(
                                                  radius: 18,
                                                  backgroundColor: _getRankColor(rankPosition).withOpacity(0.15),
                                                  child: Text(
                                                    '#$rankPosition',
                                                    style: TextStyle(
                                                      color: _getRankColor(rankPosition),
                                                      fontWeight: FontWeight.bold,
                                                      fontSize: 14,
                                                    ),
                                                  ),
                                                ),
                                                const SizedBox(width: 14),
                                                Expanded(
                                                  child: Column(
                                                    crossAxisAlignment: CrossAxisAlignment.start,
                                                    children: [
                                                      Text(
                                                        name,
                                                        style: const TextStyle(
                                                          fontWeight: FontWeight.bold,
                                                          fontSize: 15,
                                                          color: AppColors.primary1,
                                                        ),
                                                      ),
                                                      const SizedBox(height: 2),
                                                      Text(
                                                        'NIM: $nim',
                                                        style: const TextStyle(fontSize: 13, color: AppColors.tertiary5),
                                                      ),
                                                    ],
                                                  ),
                                                ),
                                                Column(
                                                  crossAxisAlignment: CrossAxisAlignment.end,
                                                  children: [
                                                    const Text(
                                                      'Skor SPK',
                                                      style: TextStyle(fontSize: 11, color: AppColors.tertiary5),
                                                    ),
                                                    Text(
                                                      score.toStringAsFixed(4),
                                                      style: const TextStyle(
                                                        fontWeight: FontWeight.bold,
                                                        fontSize: 16,
                                                        color: AppColors.primary,
                                                      ),
                                                    ),
                                                  ],
                                                ),
                                              ],
                                            ),
                                            const Divider(height: 24, color: AppColors.primary8),
                                            Row(
                                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                              children: [
                                                // Status Badge
                                                Row(
                                                  children: [
                                                    const Text(
                                                      'Status: ',
                                                      style: TextStyle(fontSize: 13, color: AppColors.tertiary5),
                                                    ),
                                                    Container(
                                                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                                      decoration: BoxDecoration(
                                                        color: annStatus == 'accepted'
                                                            ? Colors.green.withOpacity(0.1)
                                                            : annStatus == 'rejected'
                                                                ? AppColors.lightRed
                                                                : AppColors.lightYellow,
                                                        borderRadius: BorderRadius.circular(20),
                                                        border: Border.all(
                                                          color: annStatus == 'accepted'
                                                              ? Colors.green
                                                                : annStatus == 'rejected'
                                                                    ? AppColors.red
                                                                    : AppColors.yellow,
                                                          width: 1,
                                                        ),
                                                      ),
                                                      child: Text(
                                                        annStatus == 'accepted'
                                                            ? 'DITERIMA'
                                                            : annStatus == 'rejected'
                                                                ? 'DITOLAK'
                                                                : 'BELUM DIPUTUSKAN',
                                                        style: TextStyle(
                                                          fontSize: 11,
                                                          fontWeight: FontWeight.bold,
                                                          color: annStatus == 'accepted'
                                                              ? Colors.green
                                                              : annStatus == 'rejected'
                                                                  ? AppColors.red
                                                                  : AppColors.yellow,
                                                        ),
                                                      ),
                                                    ),
                                                  ],
                                                ),
                                                // Action Buttons
                                                Row(
                                                  children: [
                                                    ElevatedButton(
                                                      onPressed: () => _confirmDecision(candidateId, name, 'rejected'),
                                                      style: ElevatedButton.styleFrom(
                                                        backgroundColor: Colors.white,
                                                        foregroundColor: AppColors.red,
                                                        elevation: 0,
                                                        side: const BorderSide(color: AppColors.red),
                                                        shape: RoundedRectangleBorder(
                                                          borderRadius: BorderRadius.circular(8),
                                                        ),
                                                        padding: const EdgeInsets.symmetric(horizontal: 10),
                                                      ),
                                                      child: const Text('Tolak'),
                                                    ),
                                                    const SizedBox(width: 8),
                                                    ElevatedButton(
                                                      onPressed: () => _confirmDecision(candidateId, name, 'accepted'),
                                                      style: ElevatedButton.styleFrom(
                                                        backgroundColor: Colors.green,
                                                        foregroundColor: Colors.white,
                                                        elevation: 0,
                                                        shape: RoundedRectangleBorder(
                                                          borderRadius: BorderRadius.circular(8),
                                                        ),
                                                        padding: const EdgeInsets.symmetric(horizontal: 10),
                                                      ),
                                                      child: const Text('ACC'),
                                                    ),
                                                  ],
                                                ),
                                              ],
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
                  ),
      ),
    );
  }
}
