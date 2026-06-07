import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../services/candidate_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_button.dart';
import '../widgets/app_input.dart';
import '../widgets/app_loading.dart';

class CandidateRegisterProfileScreen extends StatefulWidget {
  const CandidateRegisterProfileScreen({super.key});

  @override
  State<CandidateRegisterProfileScreen> createState() => _CandidateRegisterProfileScreenState();
}

class _CandidateRegisterProfileScreenState extends State<CandidateRegisterProfileScreen> {
  final CandidateService _candidateService = CandidateService();

  bool _isLoading = false;
  List<dynamic> _departments = [];
  String? _errorMessage;

  // Form controllers
  final TextEditingController _nicknameController = TextEditingController();
  final TextEditingController _nimController = TextEditingController();
  final TextEditingController _kelasController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _reasonController = TextEditingController();
  final TextEditingController _weaknessController = TextEditingController();
  final TextEditingController _contributionController = TextEditingController();

  // Validation errors
  String? _nicknameError;
  String? _nimError;
  String? _kelasError;
  String? _phoneError;
  String? _addressError;
  String? _reasonError;
  String? _weaknessError;
  String? _contributionError;

  String _candidateType = 'staff';
  String _prodi = 'Teknik Informatika';
  String? _firstChoiceId;
  String? _secondChoiceId;

  // File labels (for visual feedback)
  String _photoLabel = 'Belum Ada';
  String _igLabel = 'Belum Ada';
  String _ytLabel = 'Belum Ada';
  String _statementLabel = 'Belum Ada';
  String _sigLabel = 'Belum Ada';
  String _parentSigLabel = 'Belum Ada';

  // Binary file bytes
  Uint8List? _photoBytes;
  Uint8List? _igBytes;
  Uint8List? _ytBytes;
  Uint8List? _statementBytes;
  Uint8List? _sigBytes;
  Uint8List? _parentSigBytes;

  // Tiny 1x1 pixel PNG bytes to use as mock file data
  final Uint8List _dummyBytes = Uint8List.fromList([
    0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A, 0x00, 0x00, 0x00, 0x0D,
    0x49, 0x48, 0x44, 0x52, 0x00, 0x00, 0x00, 0x01, 0x00, 0x00, 0x00, 0x01,
    0x08, 0x06, 0x00, 0x00, 0x00, 0x1F, 0x15, 0xC4, 0x89, 0x00, 0x00, 0x00,
    0x0D, 0x49, 0x4D, 0x41, 0x47, 0x45, 0x20, 0x44, 0x55, 0x4D, 0x4D, 0x59,
    0x00, 0x00, 0x00, 0x00, 0x49, 0x45, 0x4E, 0x44, 0xAE, 0x42, 0x60, 0x82
  ]);

  @override
  void initState() {
    super.initState();
    _loadDepartments();
  }

  @override
  void dispose() {
    _nicknameController.dispose();
    _nimController.dispose();
    _kelasController.dispose();
    _phoneController.dispose();
    _addressController.dispose();
    _reasonController.dispose();
    _weaknessController.dispose();
    _contributionController.dispose();
    super.dispose();
  }

  Future<void> _loadDepartments() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final response = await _candidateService.getDepartments();

    if (response['success'] == true) {
      setState(() {
        _departments = response['data'] as List<dynamic>;
        _isLoading = false;
      });
    } else {
      setState(() {
        _errorMessage = response['message'] as String?;
        _isLoading = false;
      });
    }
  }

  void _autofillDemoData() {
    setState(() {
      _nicknameController.text = 'Budi';
      _nimController.text = '2211502010'; // 10 digit
      _kelasController.text = 'TI-4A';
      _phoneController.text = '081234567890';
      _addressController.text = 'Jalan Margonda Raya No. 12, Depok';
      _reasonController.text = 'Saya ingin meningkatkan skill organisasi dan berkontribusi langsung pada kegiatan mahasiswa.';
      _weaknessController.text = 'Saya kadang terlalu perfeksionis, namun saya mengatasinya dengan membuat to-do list terstruktur.';
      _contributionController.text = 'Saya akan aktif berpartisipasi dan membantu mengelola media sosial serta website organisasi.';

      _candidateType = 'staff';
      _prodi = 'Teknik Informatika';

      if (_departments.isNotEmpty) {
        _firstChoiceId = _departments[0]['id'].toString();
        if (_departments.length > 1) {
          _secondChoiceId = _departments[1]['id'].toString();
        }
      }

      // Populate file bytes
      _photoBytes = _dummyBytes;
      _igBytes = _dummyBytes;
      _ytBytes = _dummyBytes;
      _statementBytes = _dummyBytes;
      _sigBytes = _dummyBytes;
      _parentSigBytes = _dummyBytes;

      _photoLabel = 'photo_budi.png';
      _igLabel = 'ig_proof.png';
      _ytLabel = 'yt_proof.png';
      _statementLabel = 'statement.pdf';
      _sigLabel = 'signature_budi.png';
      _parentSigLabel = 'parent_signature.png';
    });

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Demo data berhasil diisi otomatis!'),
        backgroundColor: Colors.blue,
      ),
    );
  }

  void _submitProfile() async {
    setState(() {
      _nicknameError = null;
      _nimError = null;
      _kelasError = null;
      _phoneError = null;
      _addressError = null;
      _reasonError = null;
      _weaknessError = null;
      _contributionError = null;
    });

    bool hasError = false;

    if (_nicknameController.text.trim().isEmpty) {
      _nicknameError = 'Nama panggilan wajib diisi';
      hasError = true;
    }

    final String nim = _nimController.text.trim();
    if (nim.isEmpty) {
      _nimError = 'NIM wajib diisi';
      hasError = true;
    } else if (nim.length != 10 || int.tryParse(nim) == null) {
      _nimError = 'NIM harus berupa 10 digit angka';
      hasError = true;
    }

    if (_kelasController.text.trim().isEmpty) {
      _kelasError = 'Kelas wajib diisi';
      hasError = true;
    }

    if (_phoneController.text.trim().isEmpty) {
      _phoneError = 'Nomor telepon wajib diisi';
      hasError = true;
    }

    if (_addressController.text.trim().isEmpty) {
      _addressError = 'Alamat wajib diisi';
      hasError = true;
    }

    if (_firstChoiceId == null || _firstChoiceId!.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Departemen Pilihan Pertama wajib diisi'),
          backgroundColor: AppColors.red,
        ),
      );
      return;
    }

    if (_reasonController.text.trim().isEmpty) {
      _reasonError = 'Alasan pemilihan wajib diisi';
      hasError = true;
    }

    if (_weaknessController.text.trim().isEmpty) {
      _weaknessError = 'Esai kelemahan wajib diisi';
      hasError = true;
    }

    if (_contributionController.text.trim().isEmpty) {
      _contributionError = 'Rencana kontribusi wajib diisi';
      hasError = true;
    }

    if (hasError) {
      setState(() {});
      return;
    }

    if (_photoBytes == null ||
        _igBytes == null ||
        _ytBytes == null ||
        _statementBytes == null ||
        _sigBytes == null ||
        _parentSigBytes == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Semua file berkas wajib diunggah (gunakan Auto-fill untuk mengisi berkas demo)'),
          backgroundColor: AppColors.red,
        ),
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    final Map<String, String> fields = {
      'candidate_type': _candidateType,
      'nickname': _nicknameController.text.trim(),
      'nim': _nimController.text.trim(),
      'prodi': _prodi,
      'kelas': _kelasController.text.trim(),
      'phone': _phoneController.text.trim(),
      'address': _addressController.text.trim(),
      'first_choice_id': _firstChoiceId!,
      'department_choice_reason': _reasonController.text.trim(),
      'weakness_description': _weaknessController.text.trim(),
      'contribution_plan': _contributionController.text.trim(),
    };

    if (_secondChoiceId != null && _secondChoiceId!.isNotEmpty) {
      fields['second_choice_id'] = _secondChoiceId!;
    }

    final Map<String, List<int>> fileBytes = {
      'photo': _photoBytes!,
      'instagram_proof': _igBytes!,
      'youtube_proof': _ytBytes!,
      'political_statement': _statementBytes!,
      'candidate_signature': _sigBytes!,
      'parent_signature': _parentSigBytes!,
    };

    final Map<String, String> fileNames = {
      'photo': _photoLabel,
      'instagram_proof': _igLabel,
      'youtube_proof': _ytLabel,
      'political_statement': _statementLabel,
      'candidate_signature': _sigLabel,
      'parent_signature': _parentSigLabel,
    };

    final response = await _candidateService.storeProfile(
      fields: fields,
      fileBytes: fileBytes,
      fileNames: fileNames,
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
      Navigator.pushReplacementNamed(context, '/dashboard');
    } else {
      final errors = response['errors'] as Map<String, dynamic>?;
      String errMsg = response['message'] as String? ?? 'Gagal menyimpan profil';
      if (errors != null && errors.isNotEmpty) {
        final firstKey = errors.keys.first;
        final firstList = errors[firstKey] as List<dynamic>?;
        if (firstList != null && firstList.isNotEmpty) {
          errMsg = firstList.first.toString();
        }
      }

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(errMsg),
          backgroundColor: AppColors.red,
        ),
      );
    }
  }

  Widget _buildFilePickerRow(String title, String currentLabel, VoidCallback onPick) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 14, color: AppColors.primary1),
                ),
                Text(
                  currentLabel,
                  style: TextStyle(
                    fontSize: 12,
                    color: currentLabel == 'Belum Ada' ? AppColors.tertiary5 : Colors.green,
                    fontWeight: currentLabel == 'Belum Ada' ? FontWeight.normal : FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
          ElevatedButton.icon(
            onPressed: onPick,
            icon: const Icon(LucideIcons.paperclip, size: 14),
            label: const Text('Unggah'),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.tertiary10,
              foregroundColor: AppColors.primary,
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return AppLoadingOverlay(
      isLoading: _isLoading,
      child: Scaffold(
        backgroundColor: AppColors.tertiary,
        appBar: AppBar(
          title: const Text(
            'Lengkapi Profil Anggota',
            style: TextStyle(color: AppColors.primary1, fontWeight: FontWeight.bold, fontSize: 18),
          ),
          actions: [
            TextButton.icon(
              onPressed: _autofillDemoData,
              icon: const Icon(LucideIcons.skipForward, size: 16, color: Colors.blue),
              label: const Text('Demo Fill', style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
            ),
          ],
        ),
        body: _errorMessage != null
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
                        onPressed: _loadDepartments,
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
                    const Text(
                      'Formulir Identitas & Berkas',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppColors.primary1),
                    ),
                    const SizedBox(height: 4),
                    const Text(
                      'Isi data Anda secara lengkap untuk mendaftar seleksi kepengurusan HIMATIK.',
                      style: TextStyle(fontSize: 14, color: AppColors.tertiary5),
                    ),
                    const SizedBox(height: 24),

                    // SECTION 1: Personal Data
                    Card(
                      elevation: 0,
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
                            const Text(
                              'Data Pribadi',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
                            ),
                            const Divider(height: 20, color: AppColors.primary8),
                            
                            // Candidate Type
                            const Text(
                              'Tipe Keanggotaan',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _candidateType,
                              decoration: const InputDecoration(),
                              items: const [
                                DropdownMenuItem(value: 'staff', child: Text('Staff Pengurus')),
                                DropdownMenuItem(value: 'bph', child: Text('Badan Pengurus Harian (BPH)')),
                              ],
                              onChanged: (val) {
                                if (val != null) {
                                  setState(() {
                                    _candidateType = val;
                                  });
                                }
                              },
                            ),
                            const SizedBox(height: 18),

                            // Nickname
                            AppTextField(
                              label: 'Nama Panggilan',
                              placeholder: 'Masukkan nama panggilan Anda',
                              controller: _nicknameController,
                              prefixIcon: LucideIcons.user,
                              errorText: _nicknameError,
                            ),
                            const SizedBox(height: 18),

                            // NIM
                            AppTextField(
                              label: 'NIM',
                              placeholder: 'Masukkan 10 digit NIM',
                              controller: _nimController,
                              keyboardType: TextInputType.number,
                              prefixIcon: LucideIcons.clipboardList,
                              errorText: _nimError,
                            ),
                            const SizedBox(height: 18),

                            // Prodi
                            const Text(
                              'Program Studi',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _prodi,
                              decoration: const InputDecoration(),
                              items: const [
                                DropdownMenuItem(value: 'Teknik Informatika', child: Text('Teknik Informatika')),
                                DropdownMenuItem(value: 'Teknik Multimedia dan Jaringan', child: Text('Teknik Multimedia dan Jaringan')),
                                DropdownMenuItem(value: 'Teknik Multimedia dan Digital', child: Text('Teknik Multimedia dan Digital')),
                              ],
                              onChanged: (val) {
                                if (val != null) {
                                  setState(() {
                                    _prodi = val;
                                  });
                                }
                              },
                            ),
                            const SizedBox(height: 18),

                            // Kelas
                            AppTextField(
                              label: 'Kelas',
                              placeholder: 'Contoh: TI-4A',
                              controller: _kelasController,
                              prefixIcon: LucideIcons.home,
                              errorText: _kelasError,
                            ),
                            const SizedBox(height: 18),

                            // Phone
                            AppTextField(
                              label: 'Nomor Telepon / WA',
                              placeholder: 'Contoh: 081234567890',
                              controller: _phoneController,
                              keyboardType: TextInputType.phone,
                              prefixIcon: LucideIcons.phone,
                              errorText: _phoneError,
                            ),
                            const SizedBox(height: 18),

                            // Address
                            const Text(
                              'Alamat Lengkap',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            TextFormField(
                              controller: _addressController,
                              maxLines: 3,
                              decoration: InputDecoration(
                                hintText: 'Masukkan alamat lengkap Anda...',
                                errorText: _addressError,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // SECTION 2: Preferences
                    Card(
                      elevation: 0,
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
                            const Text(
                              'Pilihan Departemen & Alasan',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
                            ),
                            const Divider(height: 20, color: AppColors.primary8),

                            // First Choice
                            const Text(
                              'Pilihan Pertama (Wajib)',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _firstChoiceId,
                              hint: const Text('Pilih Departemen Pilihan 1'),
                              decoration: const InputDecoration(),
                              items: _departments.map((dept) {
                                return DropdownMenuItem<String>(
                                  value: dept['id'].toString(),
                                  child: Text(dept['name'] as String),
                                );
                              }).toList(),
                              onChanged: (val) {
                                setState(() {
                                  _firstChoiceId = val;
                                  if (_secondChoiceId == _firstChoiceId) {
                                    _secondChoiceId = null;
                                  }
                                });
                              },
                            ),
                            const SizedBox(height: 18),

                            // Second Choice
                            const Text(
                              'Pilihan Kedua (Opsional)',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _secondChoiceId,
                              hint: const Text('Pilih Departemen Pilihan 2'),
                              decoration: const InputDecoration(),
                              items: _departments
                                  .where((dept) => dept['id'].toString() != _firstChoiceId)
                                  .map((dept) {
                                return DropdownMenuItem<String>(
                                  value: dept['id'].toString(),
                                  child: Text(dept['name'] as String),
                                );
                              }).toList(),
                              onChanged: (val) {
                                setState(() {
                                  _secondChoiceId = val;
                                });
                              },
                            ),
                            const SizedBox(height: 18),

                            // Reason
                            const Text(
                              'Alasan Pemilihan Departemen',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            TextFormField(
                              controller: _reasonController,
                              maxLines: 3,
                              decoration: InputDecoration(
                                hintText: 'Jelaskan alasan Anda memilih departemen tersebut...',
                                errorText: _reasonError,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // SECTION 3: Essays
                    Card(
                      elevation: 0,
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
                            const Text(
                              'Esai Singkat',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
                            ),
                            const Divider(height: 20, color: AppColors.primary8),

                            // Weakness
                            const Text(
                              'Sebutkan Kelemahan & Cara Mengatasi',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            TextFormField(
                              controller: _weaknessController,
                              maxLines: 3,
                              decoration: InputDecoration(
                                hintText: 'Jelaskan kelemahan diri Anda dan bagaimana solusi Anda...',
                                errorText: _weaknessError,
                              ),
                            ),
                            const SizedBox(height: 18),

                            // Contribution
                            const Text(
                              'Rencana Kontribusi Anda',
                              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: AppColors.neutral),
                            ),
                            const SizedBox(height: 6),
                            TextFormField(
                              controller: _contributionController,
                              maxLines: 3,
                              decoration: InputDecoration(
                                hintText: 'Kontribusi apa yang akan Anda berikan bagi kemajuan HIMATIK?',
                                errorText: _contributionError,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // SECTION 4: Uploads
                    Card(
                      elevation: 0,
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
                            const Text(
                              'Unggah Berkas Persyaratan',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
                            ),
                            const SizedBox(height: 4),
                            const Text(
                              'Gunakan tombol "Demo Fill" di kanan atas untuk mengisi berkas demo secara otomatis.',
                              style: TextStyle(fontSize: 12, color: AppColors.tertiary5),
                            ),
                            const Divider(height: 20, color: AppColors.primary8),

                            _buildFilePickerRow('Foto 3x4 (PNG/JPG)', _photoLabel, () {
                              setState(() {
                                _photoBytes = _dummyBytes;
                                _photoLabel = 'manual_photo.png';
                              });
                            }),
                            _buildFilePickerRow('Bukti Follow IG (PNG/JPG)', _igLabel, () {
                              setState(() {
                                _igBytes = _dummyBytes;
                                _igLabel = 'manual_ig_proof.png';
                              });
                            }),
                            _buildFilePickerRow('Bukti Subscribe YouTube (PNG/JPG)', _ytLabel, () {
                              setState(() {
                                _ytBytes = _dummyBytes;
                                _ytLabel = 'manual_yt_proof.png';
                              });
                            }),
                            _buildFilePickerRow('Surat Pernyataan Bebas Parpol (PDF/PNG/JPG)', _statementLabel, () {
                              setState(() {
                                _statementBytes = _dummyBytes;
                                _statementLabel = 'manual_statement.pdf';
                              });
                            }),
                            _buildFilePickerRow('Tanda Tangan Pendaftar (PNG/JPG)', _sigLabel, () {
                              setState(() {
                                _sigBytes = _dummyBytes;
                                _sigLabel = 'manual_sig.png';
                              });
                            }),
                            _buildFilePickerRow('Tanda Tangan Orang Tua (PNG/JPG)', _parentSigLabel, () {
                              setState(() {
                                _parentSigBytes = _dummyBytes;
                                _parentSigLabel = 'manual_parent_sig.png';
                              });
                            }),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 40),

                    AppPrimaryButton(
                      text: 'Kirim Pendaftaran',
                      onPressed: _submitProfile,
                    ),
                    const SizedBox(height: 24),
                  ],
                ),
              ),
      ),
    );
  }
}
