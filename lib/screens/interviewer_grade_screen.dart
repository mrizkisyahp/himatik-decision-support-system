import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/reviewer_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_button.dart';
import '../widgets/app_loading.dart';

class InterviewerGradeScreen extends StatefulWidget {
  const InterviewerGradeScreen({super.key});

  @override
  State<InterviewerGradeScreen> createState() => _InterviewerGradeScreenState();
}

class _InterviewerGradeScreenState extends State<InterviewerGradeScreen> {
  final ReviewerService _reviewerService = ReviewerService();

  bool _isLoading = true;
  String? _errorMessage;

  int? _candidateId;
  int? _departmentId;
  String? _candidateName;
  String? _candidateNim;

  List<dynamic> _criteria = [];
  Map<String, int> _scores = {}; // Maps criteriaId to score (1-5)
  final TextEditingController _notesController = TextEditingController();

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_candidateId == null) {
      final args = ModalRoute.of(context)!.settings.arguments as Map<String, dynamic>;
      _candidateId = args['candidateId'] as int;
      _departmentId = args['departmentId'] as int;
      _candidateName = args['candidateName'] as String?;
      _candidateNim = args['candidateNim'] as String?;
      _loadGradingDetails();
    }
  }

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _loadGradingDetails() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final response = await _reviewerService.getGradingDetails(_candidateId!, _departmentId!);

    if (response['success'] == true) {
      final criteriaList = response['criteria'] as List<dynamic>;
      final existingScores = response['existing_scores'] as Map<String, dynamic>? ?? {};

      final Map<String, int> initialScores = {};
      String initialNotes = '';

      for (final criterion in criteriaList) {
        final idStr = criterion['id'].toString();
        if (existingScores.containsKey(idStr)) {
          initialScores[idStr] = existingScores[idStr]['score'] as int? ?? 3;
        } else {
          initialScores[idStr] = 3; // Default score is 3
        }
      }

      // Pre-fill notes if available from any existing score record
      if (existingScores.isNotEmpty) {
        for (final key in existingScores.keys) {
          final note = existingScores[key]['notes'] as String?;
          if (note != null && note.isNotEmpty) {
            initialNotes = note;
            break;
          }
        }
      }

      setState(() {
        _criteria = criteriaList;
        _scores = initialScores;
        _notesController.text = initialNotes;
        _isLoading = false;
      });
    } else {
      setState(() {
        _errorMessage = response['message'] as String?;
        _isLoading = false;
      });
    }
  }

  Future<void> _submitScores() async {
    setState(() {
      _isLoading = true;
    });

    final response = await _reviewerService.submitScores(
      candidateId: _candidateId!,
      departmentId: _departmentId!,
      scores: _scores,
      notes: _notesController.text.trim(),
    );

    setState(() {
      _isLoading = false;
    });

    if (!mounted) return;

    if (response['success'] == true) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.pop(context, true); // Return true to trigger refresh
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response['message'] as String),
          backgroundColor: AppColors.red,
        ),
      );
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
            'Penilaian Wawancara',
            style: TextStyle(color: AppColors.primary1, fontWeight: FontWeight.bold, fontSize: 18),
          ),
          leading: IconButton(
            icon: const Icon(LucideIcons.arrowLeft),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: _isLoading && _criteria.isEmpty
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
                            onPressed: _loadGradingDetails,
                            child: const Text('Coba Lagi'),
                          ),
                        ],
                      ),
                    ),
                  )
                : SingleChildScrollView(
                    padding: const EdgeInsets.all(24.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Candidate Info
                        Card(
                          elevation: 0,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                            side: const BorderSide(color: AppColors.primary8),
                          ),
                          color: Colors.white,
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Row(
                              children: [
                                const CircleAvatar(
                                  radius: 24,
                                  backgroundColor: AppColors.primary10,
                                  child: Icon(LucideIcons.user, color: AppColors.primary),
                                ),
                                const SizedBox(width: 16),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        _candidateName ?? 'Kandidat',
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 16,
                                          color: AppColors.primary1,
                                        ),
                                      ),
                                      const SizedBox(height: 2),
                                      Text(
                                        'NIM: ${_candidateNim ?? "-"}',
                                        style: const TextStyle(
                                          fontSize: 13,
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

                        const Text(
                          'Kriteria Evaluasi (SPK)',
                          style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppColors.primary1),
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'Berikan nilai consensus dari rentang 1 (Sangat Kurang) hingga 5 (Sangat Baik) untuk setiap kriteria berikut.',
                          style: TextStyle(fontSize: 14, color: AppColors.tertiary5),
                        ),
                        const SizedBox(height: 20),

                        // Criteria list
                        ListView.builder(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: _criteria.length,
                          itemBuilder: (context, index) {
                            final criterion = _criteria[index];
                            final idStr = criterion['id'].toString();
                            final name = criterion['name'] as String? ?? '-';
                            final desc = criterion['description'] as String? ?? '';
                            final type = criterion['type'] as String? ?? 'core';
                            final aspect = criterion['aspect'] as String? ?? 'personal';
                            final targetScore = criterion['target_score'] as int? ?? 3;
                            final currentScore = _scores[idStr] ?? 3;

                            return Card(
                              elevation: 0,
                              margin: const EdgeInsets.only(bottom: 18),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(16),
                                side: const BorderSide(color: AppColors.primary8),
                              ),
                              color: Colors.white,
                              child: Padding(
                                padding: const EdgeInsets.all(16.0),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Expanded(
                                          child: Text(
                                            name,
                                            style: const TextStyle(
                                              fontWeight: FontWeight.bold,
                                              fontSize: 15,
                                              color: AppColors.primary1,
                                            ),
                                          ),
                                        ),
                                        Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                          decoration: BoxDecoration(
                                            color: AppColors.primary10,
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: Text(
                                            'Target: $targetScore',
                                            style: const TextStyle(
                                              fontSize: 11,
                                              color: AppColors.primary,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 4),
                                    if (desc.isNotEmpty)
                                      Text(
                                        desc,
                                        style: const TextStyle(fontSize: 13, color: AppColors.tertiary5),
                                      ),
                                    const SizedBox(height: 8),
                                    Row(
                                      children: [
                                        Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                          decoration: BoxDecoration(
                                            color: type == 'core' ? Colors.blue.shade50 : Colors.orange.shade50,
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: Text(
                                            type == 'core' ? 'Core Factor' : 'Secondary Factor',
                                            style: TextStyle(
                                              fontSize: 10,
                                              color: type == 'core' ? Colors.blue.shade800 : Colors.orange.shade800,
                                              fontWeight: FontWeight.w600,
                                            ),
                                          ),
                                        ),
                                        const SizedBox(width: 8),
                                        Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                          decoration: BoxDecoration(
                                            color: Colors.purple.shade50,
                                            borderRadius: BorderRadius.circular(4),
                                          ),
                                          child: Text(
                                            aspect == 'personal' ? 'Aspek Personal' : 'Aspek Organisasi',
                                            style: TextStyle(
                                              fontSize: 10,
                                              color: Colors.purple.shade800,
                                              fontWeight: FontWeight.w600,
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const Divider(height: 24, color: AppColors.primary8),
                                    Row(
                                      children: [
                                        const Text('Nilai: ', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                                        Text(
                                          '$currentScore',
                                          style: const TextStyle(
                                            fontWeight: FontWeight.bold,
                                            fontSize: 18,
                                            color: AppColors.primary,
                                          ),
                                        ),
                                        Expanded(
                                          child: Slider(
                                            value: currentScore.toDouble(),
                                            min: 1,
                                            max: 5,
                                            divisions: 4,
                                            activeColor: AppColors.primary,
                                            inactiveColor: AppColors.primary8,
                                            onChanged: (val) {
                                              setState(() {
                                                _scores[idStr] = val.round();
                                              });
                                            },
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                        const SizedBox(height: 8),

                        // Global notes
                        const Text(
                          'Catatan Wawancara / Penilaian',
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
                        ),
                        const SizedBox(height: 8),
                        TextFormField(
                          controller: _notesController,
                          maxLines: 4,
                          decoration: InputDecoration(
                            hintText: 'Berikan evaluasi tertulis, catatan kekuatan/kelemahan, atau masukan untuk kandidat...',
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                        ),
                        const SizedBox(height: 40),

                        AppPrimaryButton(
                          text: 'Simpan Nilai & Selesaikan',
                          onPressed: _submitScores,
                        ),
                        const SizedBox(height: 24),
                      ],
                    ),
                  ),
      ),
    );
  }
}
