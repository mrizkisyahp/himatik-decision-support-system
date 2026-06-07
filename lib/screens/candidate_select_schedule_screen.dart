import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/candidate_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_loading.dart';

class CandidateSelectScheduleScreen extends StatefulWidget {
  const CandidateSelectScheduleScreen({super.key});

  @override
  State<CandidateSelectScheduleScreen> createState() => _CandidateSelectScheduleScreenState();
}

class _CandidateSelectScheduleScreenState extends State<CandidateSelectScheduleScreen> {
  final CandidateService _candidateService = CandidateService();

  bool _isLoading = true;
  String? _errorMessage;
  List<dynamic> _schedules = [];
  int? _currentBookedSlotId;

  @override
  void initState() {
    super.initState();
    _loadSchedules();
  }

  Future<void> _loadSchedules() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final response = await _candidateService.getAvailableSchedules();

    if (response['success'] == true) {
      setState(() {
        _schedules = response['data'] as List<dynamic>;
        _currentBookedSlotId = response['current_booked_slot_id'];
        _isLoading = false;
      });
    } else {
      setState(() {
        _errorMessage = response['message'] as String?;
        _isLoading = false;
      });
    }
  }

  void _confirmBooking(int scheduleId, String date, String time) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Konfirmasi Pilihan Jadwal'),
        content: Text('Apakah Anda yakin ingin memilih jadwal wawancara pada tanggal $date pukul $time?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal', style: TextStyle(color: AppColors.tertiary5)),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(ctx);
              _bookSchedule(scheduleId);
            },
            child: const Text('Pilih', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  Future<void> _bookSchedule(int scheduleId) async {
    setState(() {
      _isLoading = true;
    });

    final response = await _candidateService.bookSchedule(scheduleId);

    if (response['success'] == true) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: Colors.green,
        ),
      );
      _loadSchedules(); // Refresh the list
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

  @override
  Widget build(BuildContext context) {
    return AppLoadingOverlay(
      isLoading: _isLoading,
      child: Scaffold(
        backgroundColor: AppColors.tertiary,
        appBar: AppBar(
          title: const Text(
            'Pilih Jadwal Wawancara',
            style: TextStyle(color: AppColors.primary1, fontWeight: FontWeight.bold, fontSize: 18),
          ),
          leading: IconButton(
            icon: const Icon(LucideIcons.arrowLeft),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: _isLoading && _schedules.isEmpty
            ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
            : _errorMessage != null
                ? Center(
                    child: Padding(
                      padding: const EdgeInsets.all(24.0),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(LucideIcons.calendar, size: 64, color: AppColors.tertiary7),
                          const SizedBox(height: 16),
                          Text(
                            _errorMessage!,
                            textAlign: TextAlign.center,
                            style: const TextStyle(fontSize: 16, color: AppColors.neutral),
                          ),
                          const SizedBox(height: 24),
                          ElevatedButton(
                            onPressed: _loadSchedules,
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
                          'Jadwal Wawancara Tersedia',
                          style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppColors.primary1),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Silakan pilih salah satu jadwal wawancara di bawah ini yang sesuai dengan waktu luang Anda.',
                          style: TextStyle(fontSize: 14, color: AppColors.tertiary5),
                        ),
                        const SizedBox(height: 24),
                        Expanded(
                          child: _schedules.isEmpty
                              ? const Center(
                                  child: Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Icon(LucideIcons.clock, size: 48, color: AppColors.tertiary5),
                                      SizedBox(height: 12),
                                      Text(
                                        'Tidak ada slot jadwal wawancara yang tersedia saat ini.',
                                        textAlign: TextAlign.center,
                                        style: TextStyle(color: AppColors.tertiary5, fontSize: 14),
                                      ),
                                    ],
                                  ),
                                )
                              : ListView.builder(
                                  itemCount: _schedules.length,
                                  itemBuilder: (context, index) {
                                    final item = _schedules[index];
                                    final id = item['id'] as int;
                                    final date = item['date'] as String? ?? '';
                                    final startTime = item['start_time'] as String? ?? '';
                                    final endTime = item['end_time'] as String? ?? '';
                                    final isBookedByMe = _currentBookedSlotId == id;

                                    return Card(
                                      elevation: 0,
                                      margin: const EdgeInsets.only(bottom: 16),
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(16),
                                        side: BorderSide(
                                          color: isBookedByMe ? AppColors.primary : AppColors.primary8,
                                          width: isBookedByMe ? 2 : 1,
                                        ),
                                      ),
                                      color: Colors.white,
                                      child: Padding(
                                        padding: const EdgeInsets.all(16.0),
                                        child: Row(
                                          children: [
                                            Container(
                                              padding: const EdgeInsets.all(12),
                                              decoration: BoxDecoration(
                                                color: isBookedByMe ? AppColors.primary10 : AppColors.tertiary10,
                                                borderRadius: BorderRadius.circular(12),
                                              ),
                                              child: Icon(
                                                LucideIcons.calendar,
                                                color: isBookedByMe ? AppColors.primary : AppColors.tertiary5,
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
                                                      fontSize: 16,
                                                      color: AppColors.primary1,
                                                    ),
                                                  ),
                                                  const SizedBox(height: 4),
                                                  Text(
                                                    date,
                                                    style: const TextStyle(
                                                      fontSize: 14,
                                                      color: AppColors.tertiary5,
                                                    ),
                                                  ),
                                                ],
                                              ),
                                            ),
                                            const SizedBox(width: 12),
                                            if (isBookedByMe)
                                              Container(
                                                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                                decoration: BoxDecoration(
                                                  color: Colors.green.withOpacity(0.1),
                                                  borderRadius: BorderRadius.circular(20),
                                                  border: Border.all(color: Colors.green, width: 1),
                                                ),
                                                child: const Row(
                                                  mainAxisSize: MainAxisSize.min,
                                                  children: [
                                                    Icon(LucideIcons.check, size: 14, color: Colors.green),
                                                    SizedBox(width: 4),
                                                    Text(
                                                      'Terpilih',
                                                      style: TextStyle(
                                                        color: Colors.green,
                                                        fontSize: 12,
                                                        fontWeight: FontWeight.bold,
                                                      ),
                                                    ),
                                                  ],
                                                ),
                                              )
                                            else
                                              ElevatedButton(
                                                onPressed: () => _confirmBooking(id, date, '$startTime - $endTime'),
                                                style: ElevatedButton.styleFrom(
                                                  backgroundColor: AppColors.primary,
                                                  foregroundColor: Colors.white,
                                                  elevation: 0,
                                                  shape: RoundedRectangleBorder(
                                                    borderRadius: BorderRadius.circular(8),
                                                  ),
                                                ),
                                                child: const Text('Pilih'),
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
