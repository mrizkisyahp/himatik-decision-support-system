import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';
import '../theme/app_state.dart';

// --- SHARED FORM LAYOUT FOR CANDIDATE FLOW ---
class _CandidateFormLayout extends StatelessWidget {
  final int step;
  final String title;
  final String subtitle;
  final Widget child;
  final VoidCallback onNext;
  final VoidCallback? onBack;
  final String nextLabel;
  final IconData? nextIcon;

  const _CandidateFormLayout({
    required this.step,
    required this.title,
    required this.subtitle,
    required this.child,
    required this.onNext,
    this.onBack,
    this.nextLabel = 'Berikutnya',
    this.nextIcon = LucideIcons.arrowRight,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: onBack != null
            ? IconButton(
                icon: const Icon(LucideIcons.arrowLeft, color: AppColors.primary1),
                onPressed: onBack,
              )
            : null,
        title: Text(
          'Formulir Pendaftaran',
          style: GoogleFonts.dmSans(
            fontSize: 16,
            fontWeight: FontWeight.bold,
            color: AppColors.primary1,
          ),
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
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(18.0), // Normal gap
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Progress Bar
              if (step > 0) ...[
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Langkah $step dari 5',
                      style: GoogleFonts.dmSans(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary3,
                      ),
                    ),
                    Text(
                      '${((step - 1) / 4 * 100).toInt()}% Selesai',
                      style: GoogleFonts.dmSans(
                        fontSize: 12,
                        color: AppColors.tertiary4,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 6), // Label to sublabel gap
                LinearProgressIndicator(
                  value: (step - 1) / 4,
                  backgroundColor: AppColors.tertiary8,
                  color: AppColors.primary,
                  borderRadius: BorderRadius.circular(12), // Round corners 12
                  minHeight: 6,
                ),
                const SizedBox(height: 18), // Normal gap
              ],

              // Headers
              Text(
                title,
                style: GoogleFonts.dmSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                  height: 1.2,
                ),
              ),
              const SizedBox(height: 6), // Label to sublabel gap
              Text(
                subtitle,
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: AppColors.tertiary4,
                  height: 1.4,
                ),
              ),
              const SizedBox(height: 18), // Normal gap

              // Content Child
              Expanded(
                child: child,
              ),
              const SizedBox(height: 18), // Normal gap

              // Navigation Buttons
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton(
                      onPressed: onNext,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        elevation: 0,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text(
                            nextLabel,
                            style: GoogleFonts.dmSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          if (nextIcon != null) ...[
                            const SizedBox(width: 8),
                            Icon(nextIcon, size: 16),
                          ],
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// ==========================================
// --- TEXT EDITOR OVERLAY / FULL SCREEN WRITING SCREEN ---
// ==========================================
class TextEditorOverlay extends StatefulWidget {
  final String title;
  final String initialValue;
  final String hint;

  const TextEditorOverlay({
    super.key,
    required this.title,
    required this.initialValue,
    required this.hint,
  });

  @override
  State<TextEditorOverlay> createState() => _TextEditorOverlayState();
}

class _TextEditorOverlayState extends State<TextEditorOverlay> {
  late TextEditingController _controller;

  @override
  void initState() {
    super.initState();
    _controller = TextEditingController(text: widget.initialValue);
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        title: Text(
          widget.title,
          style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1),
        ),
        leading: IconButton(
          icon: const Icon(LucideIcons.x, color: AppColors.red),
          onPressed: () => Navigator.pop(context), // Cancel edit
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(18.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Expanded(
                child: Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: AppColors.tertiary,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: AppColors.tertiary6),
                  ),
                  child: TextField(
                    controller: _controller,
                    maxLines: null,
                    keyboardType: TextInputType.multiline,
                    autofocus: true,
                    style: GoogleFonts.dmSans(fontSize: 16, color: AppColors.primary1),
                    decoration: InputDecoration(
                      hintText: widget.hint,
                      border: InputBorder.none,
                      enabledBorder: InputBorder.none,
                      focusedBorder: InputBorder.none,
                      filled: false,
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 18),
              ElevatedButton(
                onPressed: () => Navigator.pop(context, _controller.text),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: Text(
                  'Selesai Menulis',
                  style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// Widget Helper to trigger Text Editor Overlay
Widget _buildOverlayInputTrigger(
  BuildContext context,
  String label,
  String value,
  String hint,
  ValueChanged<String> onChanged,
) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      Text(
        label,
        style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2),
      ),
      const SizedBox(height: 6), // label to input gap
      GestureDetector(
        onTap: () async {
          final result = await Navigator.push<String>(
            context,
            MaterialPageRoute(
              builder: (context) => TextEditorOverlay(
                title: label,
                initialValue: value,
                hint: hint,
              ),
            ),
          );
          if (result != null) {
            onChanged(result);
          }
        },
        child: Container(
          width: double.infinity,
          constraints: const BoxConstraints(minHeight: 100),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: AppColors.tertiary6),
          ),
          child: Text(
            value.isNotEmpty ? value : hint,
            style: GoogleFonts.dmSans(
              fontSize: 16,
              color: value.isNotEmpty ? AppColors.primary1 : AppColors.tertiary5,
            ),
          ),
        ),
      ),
    ],
  );
}

// ==========================================
// --- STEP 1: INFORMASI IDENTITAS DIRI (Identitas Akun) ---
// ==========================================
class CandidateForm1 extends StatefulWidget {
  const CandidateForm1({super.key});

  @override
  State<CandidateForm1> createState() => _CandidateForm1State();
}

class _CandidateForm1State extends State<CandidateForm1> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _namaController;
  late TextEditingController _panggilanController;
  late TextEditingController _nimController;
  late TextEditingController _kelasController;
  late TextEditingController _telpController;
  String _alamatText = '';
  String _selectedProdi = 'Teknik Informatika';

  final List<String> _prodiList = [
    'Teknik Informatika',
    'Teknik Komputer',
    'Broadcasting',
    'Instrumentasi Kontrol',
  ];

  @override
  void initState() {
    super.initState();
    final state = AppState.instance;
    _namaController = TextEditingController(text: state.namaLengkap);
    _panggilanController = TextEditingController(text: state.namaPanggilan);
    _nimController = TextEditingController(text: state.nim);
    _kelasController = TextEditingController(text: state.kelas);
    _telpController = TextEditingController(text: state.nomorTelepon);
    _alamatText = state.alamatLengkap;
  }

  @override
  void dispose() {
    _namaController.dispose();
    _panggilanController.dispose();
    _nimController.dispose();
    _kelasController.dispose();
    _telpController.dispose();
    super.dispose();
  }

  void _saveProfile() {
    if (_formKey.currentState!.validate()) {
      if (_alamatText.isEmpty) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Alamat lengkap tidak boleh kosong.', style: GoogleFonts.dmSans(fontSize: 12)),
            backgroundColor: AppColors.red,
          ),
        );
        return;
      }
      final state = AppState.instance;
      state.namaLengkap = _namaController.text;
      state.namaPanggilan = _panggilanController.text;
      state.nim = _nimController.text;
      state.programStudi = _selectedProdi;
      state.kelas = _kelasController.text;
      state.nomorTelepon = _telpController.text;
      state.alamatLengkap = _alamatText;
      state.hasSubmittedProfile = true;

      Navigator.pushReplacementNamed(context, '/dashboard');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.logOut, color: AppColors.red),
          onPressed: () => Navigator.pushReplacementNamed(context, '/login'),
        ),
        title: Text(
          'Informasi Identitas Diri',
          style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(18.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Text(
                  'Pastikan informasi identitas dan kontak yang kamu masukkan valid serta aktif.',
                  style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4),
                ),
                const SizedBox(height: 18),

                // Nama Lengkap
                _buildFieldLabel('Nama Lengkap'),
                TextFormField(
                  controller: _namaController,
                  decoration: const InputDecoration(hintText: 'Masukkan Nama Lengkap'),
                  validator: (v) => v!.isEmpty ? 'Nama lengkap tidak boleh kosong' : null,
                ),
                const SizedBox(height: 12),

                // Nama Panggilan
                _buildFieldLabel('Nama Panggilan'),
                TextFormField(
                  controller: _panggilanController,
                  decoration: const InputDecoration(hintText: 'Masukkan Nama Panggilan'),
                  validator: (v) => v!.isEmpty ? 'Nama panggilan tidak boleh kosong' : null,
                ),
                const SizedBox(height: 12),

                // NIM
                _buildFieldLabel('Nomor Induk Mahasiswa'),
                TextFormField(
                  controller: _nimController,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(hintText: 'Masukkan NIM'),
                  validator: (v) => v!.isEmpty ? 'NIM tidak boleh kosong' : null,
                ),
                const SizedBox(height: 12),

                // Prodi
                _buildFieldLabel('Program Studi'),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: AppColors.tertiary6),
                  ),
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      dropdownColor: Colors.white,
                      value: _selectedProdi,
                      isExpanded: true,
                      items: _prodiList.map((String item) {
                        return DropdownMenuItem<String>(
                          value: item,
                          child: Text(item, style: GoogleFonts.dmSans(fontSize: 16)),
                        );
                      }).toList(),
                      onChanged: (v) {
                        setState(() {
                          _selectedProdi = v!;
                        });
                      },
                    ),
                  ),
                ),
                const SizedBox(height: 12),

                // Kelas
                _buildFieldLabel('Kelas'),
                TextFormField(
                  controller: _kelasController,
                  decoration: const InputDecoration(hintText: 'Masukkan Kelas'),
                  validator: (v) => v!.isEmpty ? 'Kelas tidak boleh kosong' : null,
                ),
                const SizedBox(height: 12),

                // No Telp
                _buildFieldLabel('Nomor Telepon'),
                TextFormField(
                  controller: _telpController,
                  keyboardType: TextInputType.phone,
                  decoration: const InputDecoration(hintText: 'Masukkan Nomor Telepon (cth. 081...)'),
                  validator: (v) => v!.isEmpty ? 'Nomor telepon tidak boleh kosong' : null,
                ),
                const SizedBox(height: 12),

                // Alamat Lengkap (uses TextEditorOverlay)
                _buildOverlayInputTrigger(
                  context,
                  'Alamat Lengkap',
                  _alamatText,
                  'Masukkan Alamat Lengkap',
                  (val) {
                    setState(() {
                      _alamatText = val;
                    });
                  },
                ),
                const SizedBox(height: 18),

                // Submit Button
                ElevatedButton(
                  onPressed: _saveProfile,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text('Selesai', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold)),
                      const SizedBox(width: 8),
                      const Icon(LucideIcons.check, size: 18),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildFieldLabel(String label) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2),
        ),
        const SizedBox(height: 6),
      ],
    );
  }
}

// ==========================================
// --- STEP 2: MULAI DAFTAR MENJADI STAFF (Daftar Calon - 0) ---
// ==========================================
class CandidateForm2 extends StatefulWidget {
  const CandidateForm2({super.key});

  @override
  State<CandidateForm2> createState() => _CandidateForm2State();
}

class _CandidateForm2State extends State<CandidateForm2> {
  String _selectedType = 'Staff'; // Default to Staff

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.arrowLeft, color: AppColors.primary1),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Tipe Pendaftaran',
          style: GoogleFonts.dmSans(
            fontSize: 16,
            fontWeight: FontWeight.bold,
            color: AppColors.primary1,
          ),
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(18.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const SizedBox(height: 12),
              Text(
                'Pilih Tipe Pendaftaran',
                style: GoogleFonts.dmSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                  height: 1.2,
                ),
              ),
              const SizedBox(height: 6),
              Text(
                'Silakan pilih tipe kepengurusan yang ingin Anda ikuti untuk menyesuaikan persyaratan berkas.',
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: AppColors.tertiary4,
                  height: 1.4,
                ),
              ),
              const SizedBox(height: 24),

              // BPH Option Card
              _buildChoiceCard(
                type: 'BPH',
                title: 'Badan Pengurus Harian (BPH)',
                subtitle: 'Menggunakan Pas Foto dengan Jaket TIK background Biru. Bebas upload bukti Instagram/Youtube & surat pernyataan.',
                icon: LucideIcons.userCheck,
              ),
              const SizedBox(height: 16),

              // Staff Option Card
              _buildChoiceCard(
                type: 'Staff',
                title: 'Staff Departemen / Biro',
                subtitle: 'Menggunakan Pas Foto dengan Kemeja Putih background Biru. Memerlukan bukti Instagram/Youtube & surat pernyataan.',
                icon: LucideIcons.users,
              ),

              const Spacer(),

              ElevatedButton(
                onPressed: () {
                  AppState.instance.registrationType = _selectedType;
                  Navigator.pushNamed(context, '/candidate/form-3');
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text('Berikutnya', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(width: 8),
                    const Icon(LucideIcons.arrowRight, size: 18),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildChoiceCard({
    required String type,
    required String title,
    required String subtitle,
    required IconData icon,
  }) {
    final isSelected = _selectedType == type;

    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedType = type;
        });
      },
      child: Container(
        padding: const EdgeInsets.all(18.0),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? AppColors.primary : AppColors.tertiary8,
            width: isSelected ? 2.0 : 1.5,
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: AppColors.primary.withOpacity(0.08),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  )
                ]
              : null,
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: isSelected ? AppColors.primary.withOpacity(0.1) : AppColors.tertiary,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(
                icon,
                color: isSelected ? AppColors.primary : AppColors.tertiary4,
                size: 24,
              ),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: isSelected ? AppColors.primary1 : AppColors.primary2,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    subtitle,
                    style: GoogleFonts.dmSans(
                      fontSize: 12,
                      color: AppColors.tertiary4,
                      height: 1.4,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ==========================================
// --- STEP 3: PREFERENSI DEPARTMEN & EVALUASI DIRI (Daftar Calon - 1) ---
// ==========================================
class CandidateForm3 extends StatefulWidget {
  const CandidateForm3({super.key});

  @override
  State<CandidateForm3> createState() => _CandidateForm3State();
}

class _CandidateForm3State extends State<CandidateForm3> {
  final _formKey = GlobalKey<FormState>();
  final List<String> _minatList = [
    'Riset & Teknologi (Ristek)',
    'Hubungan Masyarakat (Humas)',
    'Pengembangan Sumber Daya Mahasiswa (PSDM)',
    'Kewirausahaan (KWU)',
    'Pengabdian Masyarakat (Pengmas)',
    'Seni & Olahraga (SBO)',
    'Biro Kreatif',
    'Departemen Komunikasi dan Informasi'
  ];

  String? _pilihan1;
  String? _pilihan2;
  String _alasanText = '';
  String _kekuranganText = '';
  String _langkahText = '';

  @override
  void initState() {
    super.initState();
    final state = AppState.instance;
    if (state.biroPilihan.isNotEmpty) _pilihan1 = state.biroPilihan[0];
    if (state.biroPilihan.length > 1) _pilihan2 = state.biroPilihan[1];
    _alasanText = state.alasanMemilih;
    _kekuranganText = state.deskripsiKekurangan;
    _langkahText = state.langkahKonkret;
  }

  void _next() {
    if (_formKey.currentState!.validate()) {
      if (_pilihan1 == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Silakan pilih Pilihan 1.', style: GoogleFonts.dmSans(fontSize: 12)),
            backgroundColor: AppColors.red,
          ),
        );
        return;
      }
      if (_pilihan1 == _pilihan2) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Pilihan 1 dan Pilihan 2 tidak boleh sama.', style: GoogleFonts.dmSans(fontSize: 12)),
            backgroundColor: AppColors.red,
          ),
        );
        return;
      }
      if (_alasanText.isEmpty || _kekuranganText.isEmpty || _langkahText.isEmpty) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Silakan lengkapi seluruh esai evaluasi diri.', style: GoogleFonts.dmSans(fontSize: 12)),
            backgroundColor: AppColors.red,
          ),
        );
        return;
      }

      final state = AppState.instance;
      state.biroPilihan = [_pilihan1!];
      if (_pilihan2 != null) state.biroPilihan.add(_pilihan2!);
      state.alasanMemilih = _alasanText;
      state.deskripsiKekurangan = _kekuranganText;
      state.langkahKonkret = _langkahText;

      Navigator.pushNamed(context, '/candidate/form-4');
    }
  }

  @override
  Widget build(BuildContext context) {
    return _CandidateFormLayout(
      step: 1,
      title: 'Preferensi Departmen/Biro & Evaluasi Diri',
      subtitle: 'Tentukan pilihanmu dan jabarkan alasan serta langkah konkretmu secara jujur. Masih bingung bidang yang ingin dipilih? Klik link booklet ini untuk ketahui lebih lanjut: Link Booklet',
      onBack: () => Navigator.pop(context),
      onNext: _next,
      child: Form(
        key: _formKey,
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const SizedBox(height: 12),
              
              Text(
                'Pilih Biro atau Departemen',
                style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
              ),
              Text(
                'Pilih biro atau departemen yang diinginkan. Maksimum dua.',
                style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4),
              ),
              const SizedBox(height: 12),

              // Dropdown Pilihan 1
              _buildDropdownLabel('Pilihan 1'),
              _buildDropdownField(
                value: _pilihan1,
                hint: 'Pilih Pilihan 1',
                onChanged: (val) => setState(() => _pilihan1 = val),
              ),
              const SizedBox(height: 12),

              // Dropdown Pilihan 2
              _buildDropdownLabel('Pilihan 2 (Opsional)'),
              _buildDropdownField(
                value: _pilihan2,
                hint: 'Pilih Pilihan 2 (Opsional)',
                onChanged: (val) => setState(() => _pilihan2 = val),
              ),
              const SizedBox(height: 18),

              // Alasan (Uses TextEditorOverlay)
              _buildOverlayInputTrigger(
                context,
                'Alasan Memilih Biro atau Departemen',
                _alasanText,
                'Masukkan Alasan Memilih Biro atau Departemen (Pilihan 1 Maupun Pilihan 2 Jika Ada)',
                (val) => setState(() => _alasanText = val),
              ),
              const SizedBox(height: 12),

              // Kekurangan (Uses TextEditorOverlay)
              _buildOverlayInputTrigger(
                context,
                'Deskripsikan Kekurangan Kamu',
                _kekuranganText,
                'Masukkan Kekuranganmu',
                (val) => setState(() => _kekuranganText = val),
              ),
              const SizedBox(height: 12),

              // Langkah Konkret (Uses TextEditorOverlay)
              _buildOverlayInputTrigger(
                context,
                'Langkah Konkret Apa yang Kamu Ambil Jika Terpilih',
                _langkahText,
                'Masukkan Langkah Konkretmu',
                (val) => setState(() => _langkahText = val),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDropdownLabel(String label) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6.0),
      child: Text(
        label,
        style: GoogleFonts.dmSans(fontSize: 12, fontWeight: FontWeight.w500, color: AppColors.primary2),
      ),
    );
  }

  Widget _buildDropdownField({
    required String? value,
    required String hint,
    required ValueChanged<String?> onChanged,
  }) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary6),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<String>(
          dropdownColor: Colors.white,
          value: value,
          hint: Text(hint, style: GoogleFonts.dmSans(fontSize: 16, color: AppColors.tertiary5)),
          isExpanded: true,
          items: _minatList.map((String item) {
            return DropdownMenuItem<String>(
              value: item,
              child: Text(item, style: GoogleFonts.dmSans(fontSize: 16, color: AppColors.primary1)),
            );
          }).toList(),
          onChanged: onChanged,
        ),
      ),
    );
  }
}

// ==========================================
// --- STEP 4: RIWAYAT PENDIDIKAN & PENGALAMAN (Daftar Calon - 2) ---
// ==========================================
class CandidateForm4 extends StatefulWidget {
  const CandidateForm4({super.key});

  @override
  State<CandidateForm4> createState() => _CandidateForm4State();
}

class _CandidateForm4State extends State<CandidateForm4> {
  // Helper bottom sheet runner
  void _openBottomSheet({required Widget child}) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(12)), // Corner round 12
      ),
      builder: (context) => Padding(
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
        child: child,
      ),
    );
  }

  void _tambahRiwayatFormal() {
    _openBottomSheet(child: const _AddFormalSheet());
  }

  void _tambahRiwayatInformal() {
    _openBottomSheet(child: const _AddInformalSheet());
  }

  void _tambahOrganisasi() {
    _openBottomSheet(child: const _AddOrgSheet());
  }

  void _tambahPanitia() {
    _openBottomSheet(child: const _AddPanitiaSheet());
  }

  void _editFormal(Map<String, String> item) {
    _openBottomSheet(child: _EditFormalSheet(item: item));
  }

  void _editInformal(Map<String, String> item) {
    _openBottomSheet(child: _EditInformalSheet(item: item));
  }

  void _editOrganisasi(Map<String, String> item) {
    _openBottomSheet(child: _EditOrgSheet(item: item));
  }

  void _editPanitia(Map<String, String> item) {
    _openBottomSheet(child: _EditPanitiaSheet(item: item));
  }

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;

    return _CandidateFormLayout(
      step: 2,
      title: 'Riwayat Pendidikan & Pengalaman',
      subtitle: 'Ceritakan latar belakang dan pengalaman yang telah kamu lalui.',
      onBack: () => Navigator.pop(context),
      onNext: () => Navigator.pushNamed(context, '/candidate/form-5').then((_) => setState(() {})),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 12),

            // Riwayat Pendidikan Formal
            _buildSectionHeader('Riwayat Pendidikan Formal'),
            const SizedBox(height: 6),
            _buildUnifiedListTable(
              items: state.riwayatFormal,
              titleFn: (item) => item['school']!,
              sublabelFn: (item) => 'Tahun ${item['years']!}, di ${item['city']!}.${item['major'] != '-' ? ' Jurusan ${item['major']!}' : ''}',
              onTap: (item) => _editFormal(item),
              emptyStateBuilder: () => _buildEmptyState(),
            ),
            const SizedBox(height: 6),
            _buildAddButton('+ Tambah Riwayat', _tambahRiwayatFormal),
            const SizedBox(height: 18),

            // Riwayat Pendidikan Informal
            _buildSectionHeader('Riwayat Pendidikan Informal'),
            const SizedBox(height: 6),
            _buildUnifiedListTable(
              items: state.riwayatInformal,
              titleFn: (item) => item['school']!,
              sublabelFn: (item) => 'Tahun ${item['years']!}, oleh ${item['city']!}',
              onTap: (item) => _editInformal(item),
              emptyStateBuilder: () => _buildEmptyState(),
            ),
            const SizedBox(height: 6),
            _buildAddButton('+ Tambah Riwayat', _tambahRiwayatInformal),
            const SizedBox(height: 18),

            // Pengalaman Organisasi Luar Kampus
            _buildSectionHeader('Pengalaman Organisasi Luar Kampus'),
            const SizedBox(height: 6),
            _buildUnifiedListTable(
              items: state.pengalamanOrganisasi,
              titleFn: (item) => item['name']!,
              sublabelFn: (item) => 'Tahun ${item['years']!}, di ${item['institution']!}. ${item['position']!}.',
              onTap: (item) => _editOrganisasi(item),
              emptyStateBuilder: () => _buildEmptyState(),
            ),
            const SizedBox(height: 6),
            _buildAddButton('+ Tambah Pengalaman', _tambahOrganisasi),
            const SizedBox(height: 18),

            // Pengalaman Kepanitiaan Dalam/Luar Kampus
            _buildSectionHeader('Pengalaman Kepanitiaan Dalam/Luar Kampus'),
            const SizedBox(height: 6),
            _buildUnifiedListTable(
              items: state.pengalamanKepanitiaan,
              titleFn: (item) => item['name']!,
              sublabelFn: (item) => 'Tahun ${item['years']!}, oleh ${item['institution']!}. ${item['position']!}.',
              onTap: (item) => _editPanitia(item),
              emptyStateBuilder: () => _buildEmptyState(),
            ),
            const SizedBox(height: 6),
            _buildAddButton('+ Tambah Pengalaman', _tambahPanitia),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
    );
  }

  Widget _buildEmptyState() {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary8),
      ),
      child: Column(
        children: [
          const Icon(LucideIcons.moreHorizontal, color: AppColors.tertiary6, size: 24),
          const SizedBox(height: 6),
          Text(
            'Belum Ada',
            style: GoogleFonts.dmSans(color: AppColors.tertiary5, fontSize: 12, fontWeight: FontWeight.w500),
          )
        ],
      ),
    );
  }

  Widget _buildAddButton(String text, VoidCallback onPressed) {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton(
        onPressed: onPressed,
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          elevation: 0,
          padding: const EdgeInsets.symmetric(vertical: 12),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
        child: Text(text, style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 14)),
      ),
    );
  }
}

// --- CORE BOTTOM SHEETS FOR POPUPS ---

class _AddFormalSheet extends StatefulWidget {
  const _AddFormalSheet();

  @override
  State<_AddFormalSheet> createState() => _AddFormalSheetState();
}

class _AddFormalSheetState extends State<_AddFormalSheet> {
  final _schoolController = TextEditingController();
  final _startYearController = TextEditingController();
  final _endYearController = TextEditingController();
  final _cityController = TextEditingController();
  final _majorController = TextEditingController();

  @override
  void dispose() {
    _schoolController.dispose();
    _startYearController.dispose();
    _endYearController.dispose();
    _cityController.dispose();
    _majorController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisSize: MainAxisSize.min,
          children: [
            Align(
              alignment: Alignment.topLeft,
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
                label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
                style: TextButton.styleFrom(
                  padding: EdgeInsets.zero,
                  minimumSize: Size.zero,
                  tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Tambah Riwayat Pendidikan Formal',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
            ),
            const SizedBox(height: 12),
            
            _buildPopupLabel('Nama Sekolah'),
            TextField(controller: _schoolController, decoration: const InputDecoration(hintText: 'Masukkan Nama Sekolah')),
            
            const SizedBox(height: 12),
            _buildPopupLabel('Tahun Pendidikan'),
            Row(
              children: [
                Expanded(child: TextField(controller: _startYearController, decoration: const InputDecoration(hintText: 'Awal'))),
                const SizedBox(width: 12),
                Expanded(child: TextField(controller: _endYearController, decoration: const InputDecoration(hintText: 'Akhir (Opsional)'))),
              ],
            ),
            
            const SizedBox(height: 12),
            _buildPopupLabel('Tempat/Kota'),
            TextField(controller: _cityController, decoration: const InputDecoration(hintText: 'Masukkan Tempat Sekolah')),
            
            const SizedBox(height: 12),
            _buildPopupLabel('Jurusan'),
            TextField(controller: _majorController, decoration: const InputDecoration(hintText: 'Masukkan Jurusan')),
            
            const SizedBox(height: 18),
            ElevatedButton(
              onPressed: () {
                if (_schoolController.text.isNotEmpty) {
                  AppState.instance.riwayatFormal.add({
                    'id': DateTime.now().millisecondsSinceEpoch.toString(),
                    'school': _schoolController.text,
                    'years': '${_startYearController.text} - ${_endYearController.text.isEmpty ? 'sekarang' : _endYearController.text}',
                    'city': _cityController.text,
                    'major': _majorController.text.isEmpty ? '-' : _majorController.text,
                  });
                  Navigator.pop(context);
                }
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              child: Text('Tambahkan', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16)),
            ),
          ],
        ),
      ),
    );
  }
}

class _EditFormalSheet extends StatefulWidget {
  final Map<String, String> item;
  const _EditFormalSheet({required this.item});

  @override
  State<_EditFormalSheet> createState() => _EditFormalSheetState();
}

class _EditFormalSheetState extends State<_EditFormalSheet> {
  late TextEditingController _schoolController;
  late TextEditingController _startYearController;
  late TextEditingController _endYearController;
  late TextEditingController _cityController;
  late TextEditingController _majorController;

  @override
  void initState() {
    super.initState();
    _schoolController = TextEditingController(text: widget.item['school']);
    final years = widget.item['years']!.split(' - ');
    _startYearController = TextEditingController(text: years[0]);
    _endYearController = TextEditingController(text: years.length > 1 ? years[1] : '');
    _cityController = TextEditingController(text: widget.item['city']);
    _majorController = TextEditingController(text: widget.item['major']);
  }

  @override
  void dispose() {
    _schoolController.dispose();
    _startYearController.dispose();
    _endYearController.dispose();
    _cityController.dispose();
    _majorController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisSize: MainAxisSize.min,
          children: [
            Align(
              alignment: Alignment.topLeft,
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
                label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
                style: TextButton.styleFrom(
                  padding: EdgeInsets.zero,
                  minimumSize: Size.zero,
                  tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Edit Riwayat Pendidikan Formal',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
            ),
            const SizedBox(height: 12),
            
            _buildPopupLabel('Nama Sekolah'),
            TextField(controller: _schoolController, decoration: const InputDecoration(hintText: 'Masukkan Nama Sekolah')),
            
            const SizedBox(height: 12),
            _buildPopupLabel('Tahun Pendidikan'),
            Row(
              children: [
                Expanded(child: TextField(controller: _startYearController, decoration: const InputDecoration(hintText: 'Awal'))),
                const SizedBox(width: 12),
                Expanded(child: TextField(controller: _endYearController, decoration: const InputDecoration(hintText: 'Akhir (Opsional)'))),
              ],
            ),
            
            const SizedBox(height: 12),
            _buildPopupLabel('Tempat/Kota'),
            TextField(controller: _cityController, decoration: const InputDecoration(hintText: 'Masukkan Tempat Sekolah')),
            
            const SizedBox(height: 12),
            _buildPopupLabel('Jurusan'),
            TextField(controller: _majorController, decoration: const InputDecoration(hintText: 'Masukkan Jurusan')),
            
            const SizedBox(height: 18),
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      AppState.instance.riwayatFormal.removeWhere((x) => x['id'] == widget.item['id']);
                      Navigator.pop(context);
                    },
                    icon: const Icon(LucideIcons.trash2, size: 14),
                    label: Text('Hapus', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.red,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      final idx = AppState.instance.riwayatFormal.indexWhere((x) => x['id'] == widget.item['id']);
                      if (idx != -1) {
                        AppState.instance.riwayatFormal[idx] = {
                          'id': widget.item['id']!,
                          'school': _schoolController.text,
                          'years': '${_startYearController.text} - ${_endYearController.text.isEmpty ? 'sekarang' : _endYearController.text}',
                          'city': _cityController.text,
                          'major': _majorController.text.isEmpty ? '-' : _majorController.text,
                        };
                      }
                      Navigator.pop(context);
                    },
                    icon: const Icon(LucideIcons.edit3, size: 14),
                    label: Text('Edit', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _EditInformalSheet extends StatefulWidget {
  final Map<String, String> item;
  const _EditInformalSheet({required this.item});

  @override
  State<_EditInformalSheet> createState() => _EditInformalSheetState();
}

class _EditInformalSheetState extends State<_EditInformalSheet> {
  late TextEditingController _schoolController;
  late TextEditingController _yearController;
  late TextEditingController _cityController;

  @override
  void initState() {
    super.initState();
    _schoolController = TextEditingController(text: widget.item['school']);
    _yearController = TextEditingController(text: widget.item['years']);
    _cityController = TextEditingController(text: widget.item['city']);
  }

  @override
  void dispose() {
    _schoolController.dispose();
    _yearController.dispose();
    _cityController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        mainAxisSize: MainAxisSize.min,
        children: [
          Align(
            alignment: Alignment.topLeft,
            child: TextButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
              label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
              style: TextButton.styleFrom(
                padding: EdgeInsets.zero,
                minimumSize: Size.zero,
                tapTargetSize: MaterialTapTargetSize.shrinkWrap,
              ),
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Edit Pendidikan Informal',
            style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
          ),
          const SizedBox(height: 12),
          _buildPopupLabel('Nama Kursus / Pelatihan'),
          TextField(controller: _schoolController, decoration: const InputDecoration(hintText: 'Nama Kursus / Pelatihan')),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildPopupLabel('Tahun'),
                    TextField(controller: _yearController, decoration: const InputDecoration(hintText: 'Tahun')),
                  ],
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildPopupLabel('Penyelenggara'),
                    TextField(controller: _cityController, decoration: const InputDecoration(hintText: 'Penyelenggara')),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 18),
          Row(
            children: [
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    AppState.instance.riwayatInformal.removeWhere((x) => x['id'] == widget.item['id']);
                    Navigator.pop(context);
                  },
                  icon: const Icon(LucideIcons.trash2, size: 14),
                  label: Text('Hapus', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.red,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    final idx = AppState.instance.riwayatInformal.indexWhere((x) => x['id'] == widget.item['id']);
                    if (idx != -1) {
                      AppState.instance.riwayatInformal[idx] = {
                        'id': widget.item['id']!,
                        'school': _schoolController.text,
                        'years': _yearController.text,
                        'city': _cityController.text,
                      };
                    }
                    Navigator.pop(context);
                  },
                  icon: const Icon(LucideIcons.edit3, size: 14),
                  label: Text('Edit', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _EditOrgSheet extends StatefulWidget {
  final Map<String, String> item;
  const _EditOrgSheet({required this.item});

  @override
  State<_EditOrgSheet> createState() => _EditOrgSheetState();
}

class _EditOrgSheetState extends State<_EditOrgSheet> {
  late TextEditingController _nameController;
  late TextEditingController _startYearController;
  late TextEditingController _endYearController;
  late TextEditingController _instController;
  late TextEditingController _posController;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.item['name']);
    final years = widget.item['years']!.split(' - ');
    _startYearController = TextEditingController(text: years[0]);
    _endYearController = TextEditingController(text: years.length > 1 ? years[1] : '');
    _instController = TextEditingController(text: widget.item['institution']);
    String rawPos = widget.item['position'] ?? '';
    if (rawPos.startsWith('Sebagai ')) {
      rawPos = rawPos.substring(8);
    }
    _posController = TextEditingController(text: rawPos);
  }

  @override
  void dispose() {
    _nameController.dispose();
    _startYearController.dispose();
    _endYearController.dispose();
    _instController.dispose();
    _posController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisSize: MainAxisSize.min,
          children: [
            Align(
              alignment: Alignment.topLeft,
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
                label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
                style: TextButton.styleFrom(
                  padding: EdgeInsets.zero,
                  minimumSize: Size.zero,
                  tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Edit Pengalaman Organisasi Luar Kampus',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
            ),
            const SizedBox(height: 12),
            _buildPopupLabel('Nama Organisasi'),
            TextField(controller: _nameController, decoration: const InputDecoration(hintText: 'Nama Organisasi')),
            const SizedBox(height: 12),
            _buildPopupLabel('Tahun'),
            Row(
              children: [
                Expanded(child: TextField(controller: _startYearController, decoration: const InputDecoration(hintText: 'Awal'))),
                const SizedBox(width: 12),
                Expanded(child: TextField(controller: _endYearController, decoration: const InputDecoration(hintText: 'Akhir'))),
              ],
            ),
            const SizedBox(height: 12),
            _buildPopupLabel('Tempat/Institusi'),
            TextField(controller: _instController, decoration: const InputDecoration(hintText: 'Tempat/Institusi')),
            const SizedBox(height: 12),
            _buildPopupLabel('Jabatan'),
            TextField(controller: _posController, decoration: const InputDecoration(hintText: 'Jabatan')),
            const SizedBox(height: 18),
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      AppState.instance.pengalamanOrganisasi.removeWhere((x) => x['id'] == widget.item['id']);
                      Navigator.pop(context);
                    },
                    icon: const Icon(LucideIcons.trash2, size: 14),
                    label: Text('Hapus', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.red,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      final idx = AppState.instance.pengalamanOrganisasi.indexWhere((x) => x['id'] == widget.item['id']);
                      if (idx != -1) {
                        AppState.instance.pengalamanOrganisasi[idx] = {
                          'id': widget.item['id']!,
                          'name': _nameController.text,
                          'years': '${_startYearController.text} - ${_endYearController.text}',
                          'institution': _instController.text,
                          'position': 'Sebagai ${_posController.text}',
                        };
                      }
                      Navigator.pop(context);
                    },
                    icon: const Icon(LucideIcons.edit3, size: 14),
                    label: Text('Edit', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _EditPanitiaSheet extends StatefulWidget {
  final Map<String, String> item;
  const _EditPanitiaSheet({required this.item});

  @override
  State<_EditPanitiaSheet> createState() => _EditPanitiaSheetState();
}

class _EditPanitiaSheetState extends State<_EditPanitiaSheet> {
  late TextEditingController _nameController;
  late TextEditingController _yearController;
  late TextEditingController _instController;
  late TextEditingController _posController;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.item['name']);
    _yearController = TextEditingController(text: widget.item['years']);
    _instController = TextEditingController(text: widget.item['institution']);
    String rawPos = widget.item['position'] ?? '';
    if (rawPos.startsWith('Sebagai ')) {
      rawPos = rawPos.substring(8);
    }
    _posController = TextEditingController(text: rawPos);
  }

  @override
  void dispose() {
    _nameController.dispose();
    _yearController.dispose();
    _instController.dispose();
    _posController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisSize: MainAxisSize.min,
          children: [
            Align(
              alignment: Alignment.topLeft,
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
                label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
                style: TextButton.styleFrom(
                  padding: EdgeInsets.zero,
                  minimumSize: Size.zero,
                  tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Edit Pengalaman Kepanitiaan',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
            ),
            const SizedBox(height: 12),
            _buildPopupLabel('Nama Kepanitiaan'),
            TextField(controller: _nameController, decoration: const InputDecoration(hintText: 'Nama Kepanitiaan')),
            const SizedBox(height: 12),
            _buildPopupLabel('Tahun'),
            TextField(controller: _yearController, decoration: const InputDecoration(hintText: 'Tahun')),
            const SizedBox(height: 12),
            _buildPopupLabel('Penyelenggara'),
            TextField(controller: _instController, decoration: const InputDecoration(hintText: 'Penyelenggara')),
            const SizedBox(height: 12),
            _buildPopupLabel('Jabatan'),
            TextField(controller: _posController, decoration: const InputDecoration(hintText: 'Jabatan')),
            const SizedBox(height: 18),
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      AppState.instance.pengalamanKepanitiaan.removeWhere((x) => x['id'] == widget.item['id']);
                      Navigator.pop(context);
                    },
                    icon: const Icon(LucideIcons.trash2, size: 14),
                    label: Text('Hapus', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.red,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      final idx = AppState.instance.pengalamanKepanitiaan.indexWhere((x) => x['id'] == widget.item['id']);
                      if (idx != -1) {
                        AppState.instance.pengalamanKepanitiaan[idx] = {
                          'id': widget.item['id']!,
                          'name': _nameController.text,
                          'years': _yearController.text,
                          'institution': _instController.text,
                          'position': 'Sebagai ${_posController.text}',
                        };
                      }
                      Navigator.pop(context);
                    },
                    icon: const Icon(LucideIcons.edit3, size: 14),
                    label: Text('Edit', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _EditSkillSheet extends StatefulWidget {
  final Map<String, String> item;
  final bool isSoftSkill;
  const _EditSkillSheet({required this.item, required this.isSoftSkill});

  @override
  State<_EditSkillSheet> createState() => _EditSkillSheetState();
}

class _EditSkillSheetState extends State<_EditSkillSheet> {
  late TextEditingController _skillController;
  late String _level;

  @override
  void initState() {
    super.initState();
    _skillController = TextEditingController(text: widget.item['skill']);
    _level = widget.item['level'] ?? 'Dasar';
  }

  @override
  void dispose() {
    _skillController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        mainAxisSize: MainAxisSize.min,
        children: [
          Align(
            alignment: Alignment.topLeft,
            child: TextButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
              label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
              style: TextButton.styleFrom(
                padding: EdgeInsets.zero,
                minimumSize: Size.zero,
                tapTargetSize: MaterialTapTargetSize.shrinkWrap,
              ),
            ),
          ),
          const SizedBox(height: 6),
          Text(
            widget.isSoftSkill ? 'Edit Kemampuan Non-Teknis' : 'Edit Kemampuan Teknis',
            style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
          ),
          const SizedBox(height: 12),
          _buildPopupLabel('Nama Kemampuan / Keahlian'),
          TextField(
            controller: _skillController,
            decoration: const InputDecoration(hintText: 'Masukkan Nama Kemampuan'),
          ),
          const SizedBox(height: 12),
          _buildPopupLabel('Tingkat Kemampuan'),
          const SizedBox(height: 6),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: ['Dasar', 'Sedang', 'Cakap'].map((l) {
              return Row(
                children: [
                  Radio<String>(
                    value: l,
                    groupValue: _level,
                    activeColor: AppColors.primary,
                    onChanged: (val) {
                      setState(() {
                        _level = val!;
                      });
                    },
                  ),
                  Text(l, style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.primary2, fontWeight: FontWeight.w500)),
                ],
              );
            }).toList(),
          ),
          const SizedBox(height: 18),
          Row(
            children: [
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    if (widget.isSoftSkill) {
                      AppState.instance.softSkills.removeWhere((x) => x['skill'] == widget.item['skill']);
                    } else {
                      AppState.instance.hardSkills.removeWhere((x) => x['skill'] == widget.item['skill']);
                    }
                    Navigator.pop(context);
                  },
                  icon: const Icon(LucideIcons.trash2, size: 14),
                  label: Text('Hapus', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.red,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    if (_skillController.text.isNotEmpty) {
                      if (widget.isSoftSkill) {
                        final idx = AppState.instance.softSkills.indexWhere((x) => x['skill'] == widget.item['skill']);
                        if (idx != -1) {
                          AppState.instance.softSkills[idx] = {
                            'skill': _skillController.text,
                            'level': _level,
                          };
                        }
                      } else {
                        final idx = AppState.instance.hardSkills.indexWhere((x) => x['skill'] == widget.item['skill']);
                        if (idx != -1) {
                          AppState.instance.hardSkills[idx] = {
                            'skill': _skillController.text,
                            'level': _level,
                          };
                        }
                      }
                      Navigator.pop(context);
                    }
                  },
                  icon: const Icon(LucideIcons.edit3, size: 14),
                  label: Text('Edit', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _AddInformalSheet extends StatefulWidget {
  const _AddInformalSheet();

  @override
  State<_AddInformalSheet> createState() => _AddInformalSheetState();
}

class _AddInformalSheetState extends State<_AddInformalSheet> {
  final _schoolController = TextEditingController();
  final _yearController = TextEditingController();
  final _cityController = TextEditingController();

  @override
  void dispose() {
    _schoolController.dispose();
    _yearController.dispose();
    _cityController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        mainAxisSize: MainAxisSize.min,
        children: [
          Align(
            alignment: Alignment.topLeft,
            child: TextButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
              label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
              style: TextButton.styleFrom(
                padding: EdgeInsets.zero,
                minimumSize: Size.zero,
                tapTargetSize: MaterialTapTargetSize.shrinkWrap,
              ),
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Tambah Pendidikan Informal',
            style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
          ),
          const SizedBox(height: 12),
          _buildPopupLabel('Nama Kursus / Pelatihan'),
          TextField(controller: _schoolController, decoration: const InputDecoration(hintText: 'Nama Kursus / Pelatihan')),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildPopupLabel('Tahun'),
                    TextField(controller: _yearController, decoration: const InputDecoration(hintText: 'Tahun')),
                  ],
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildPopupLabel('Penyelenggara'),
                    TextField(controller: _cityController, decoration: const InputDecoration(hintText: 'Penyelenggara')),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 18),
          ElevatedButton(
            onPressed: () {
              if (_schoolController.text.isNotEmpty) {
                AppState.instance.riwayatInformal.add({
                  'id': DateTime.now().millisecondsSinceEpoch.toString(),
                  'school': _schoolController.text,
                  'years': _yearController.text,
                  'city': _cityController.text,
                });
                Navigator.pop(context);
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: Text('Tambahkan', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16)),
          ),
        ],
      ),
    );
  }
}

class _AddOrgSheet extends StatefulWidget {
  const _AddOrgSheet();

  @override
  State<_AddOrgSheet> createState() => _AddOrgSheetState();
}

class _AddOrgSheetState extends State<_AddOrgSheet> {
  final _nameController = TextEditingController();
  final _startYearController = TextEditingController();
  final _endYearController = TextEditingController();
  final _instController = TextEditingController();
  final _posController = TextEditingController();

  @override
  void dispose() {
    _nameController.dispose();
    _startYearController.dispose();
    _endYearController.dispose();
    _instController.dispose();
    _posController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisSize: MainAxisSize.min,
          children: [
            Align(
              alignment: Alignment.topLeft,
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
                label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
                style: TextButton.styleFrom(
                  padding: EdgeInsets.zero,
                  minimumSize: Size.zero,
                  tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Tambah Pengalaman Organisasi Luar Kampus',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
            ),
            const SizedBox(height: 12),
            _buildPopupLabel('Nama Organisasi'),
            TextField(controller: _nameController, decoration: const InputDecoration(hintText: 'Nama Organisasi')),
            const SizedBox(height: 12),
            _buildPopupLabel('Tahun'),
            Row(
              children: [
                Expanded(child: TextField(controller: _startYearController, decoration: const InputDecoration(hintText: 'Awal'))),
                const SizedBox(width: 12),
                Expanded(child: TextField(controller: _endYearController, decoration: const InputDecoration(hintText: 'Akhir'))),
              ],
            ),
            const SizedBox(height: 12),
            _buildPopupLabel('Tempat/Institusi'),
            TextField(controller: _instController, decoration: const InputDecoration(hintText: 'Tempat/Institusi')),
            const SizedBox(height: 12),
            _buildPopupLabel('Jabatan'),
            TextField(controller: _posController, decoration: const InputDecoration(hintText: 'Jabatan')),
            const SizedBox(height: 18),
            ElevatedButton(
              onPressed: () {
                if (_nameController.text.isNotEmpty) {
                  AppState.instance.pengalamanOrganisasi.add({
                    'id': DateTime.now().millisecondsSinceEpoch.toString(),
                    'name': _nameController.text,
                    'years': '${_startYearController.text} - ${_endYearController.text}',
                    'institution': _instController.text,
                    'position': 'Sebagai ${_posController.text}',
                  });
                  Navigator.pop(context);
                }
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              child: Text('Tambahkan', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16)),
            ),
          ],
        ),
      ),
    );
  }
}

class _AddPanitiaSheet extends StatefulWidget {
  const _AddPanitiaSheet();

  @override
  State<_AddPanitiaSheet> createState() => _AddPanitiaSheetState();
}

class _AddPanitiaSheetState extends State<_AddPanitiaSheet> {
  final _nameController = TextEditingController();
  final _yearController = TextEditingController();
  final _instController = TextEditingController();
  final _posController = TextEditingController();

  @override
  void dispose() {
    _nameController.dispose();
    _yearController.dispose();
    _instController.dispose();
    _posController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisSize: MainAxisSize.min,
          children: [
            Align(
              alignment: Alignment.topLeft,
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
                label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
                style: TextButton.styleFrom(
                  padding: EdgeInsets.zero,
                  minimumSize: Size.zero,
                  tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ),
            ),
            const SizedBox(height: 6),
            Text(
              'Tambah Pengalaman Kepanitiaan',
              style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
            ),
            const SizedBox(height: 12),
            _buildPopupLabel('Nama Kepanitiaan'),
            TextField(controller: _nameController, decoration: const InputDecoration(hintText: 'Nama Kepanitiaan')),
            const SizedBox(height: 12),
            _buildPopupLabel('Tahun'),
            TextField(controller: _yearController, decoration: const InputDecoration(hintText: 'Tahun')),
            const SizedBox(height: 12),
            _buildPopupLabel('Penyelenggara'),
            TextField(controller: _instController, decoration: const InputDecoration(hintText: 'Penyelenggara')),
            const SizedBox(height: 12),
            _buildPopupLabel('Jabatan'),
            TextField(controller: _posController, decoration: const InputDecoration(hintText: 'Jabatan')),
            const SizedBox(height: 18),
            ElevatedButton(
              onPressed: () {
                if (_nameController.text.isNotEmpty) {
                  AppState.instance.pengalamanKepanitiaan.add({
                    'id': DateTime.now().millisecondsSinceEpoch.toString(),
                    'name': _nameController.text,
                    'years': _yearController.text,
                    'institution': _instController.text,
                    'position': 'Sebagai ${_posController.text}',
                  });
                  Navigator.pop(context);
                }
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              child: Text('Tambahkan', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16)),
            ),
          ],
        ),
      ),
    );
  }
}

Widget _buildPopupLabel(String label) {
  return Padding(
    padding: const EdgeInsets.only(bottom: 6.0),
    child: Text(
      label,
      style: GoogleFonts.dmSans(fontSize: 12, fontWeight: FontWeight.bold, color: AppColors.primary2),
    ),
  );
}

// ==========================================
// --- STEP 5: KEMAMPUAN & FASILITAS PENUNJANG (Daftar Calon - 3) ---
// ==========================================
class CandidateForm5 extends StatefulWidget {
  const CandidateForm5({super.key});

  @override
  State<CandidateForm5> createState() => _CandidateForm5State();
}

class _CandidateForm5State extends State<CandidateForm5> {
  final _facilityInputController = TextEditingController();

  void _openBottomSheet({required Widget child}) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(12)),
      ),
      builder: (context) => Padding(
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
        child: child,
      ),
    );
  }

  void _tambahSoftSkill() {
    _openBottomSheet(child: const _AddSkillSheet(isSoftSkill: true));
  }

  void _tambahHardSkill() {
    _openBottomSheet(child: const _AddSkillSheet(isSoftSkill: false));
  }

  void _editSkill(Map<String, String> item, bool isSoftSkill) {
    _openBottomSheet(child: _EditSkillSheet(item: item, isSoftSkill: isSoftSkill));
  }

  void _addFacility() {
    if (_facilityInputController.text.isNotEmpty) {
      setState(() {
        AppState.instance.fasilitasDimiliki.add(_facilityInputController.text);
        _facilityInputController.clear();
      });
    }
  }

  void _removeFacility(String fac) {
    setState(() {
      AppState.instance.fasilitasDimiliki.remove(fac);
    });
  }

  @override
  void dispose() {
    _facilityInputController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;

    return _CandidateFormLayout(
      step: 3,
      title: 'Kemampuan & Fasilitas Penunjang',
      subtitle: 'Masukkan keahlian dan alat penunjang yang kamu miliki untuk mendukung kinerjamu nanti.',
      onBack: () => Navigator.pop(context),
      onNext: () => Navigator.pushNamed(context, '/candidate/form-6').then((_) => setState(() {})),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 12),

            // Soft Skills
            _buildSectionHeader('Kemampuan Non-Teknis (Soft Skill)'),
            const SizedBox(height: 6),
            _buildSkillsTable(state.softSkills, true),
            const SizedBox(height: 6),
            _buildAddButton('+ Tambah Kemampuan', _tambahSoftSkill),
            const SizedBox(height: 18),

            // Hard Skills
            _buildSectionHeader('Kemampuan Teknis (Hard Skill)'),
            const SizedBox(height: 6),
            _buildSkillsTable(state.hardSkills, false),
            const SizedBox(height: 6),
            _buildAddButton('+ Tambah Kemampuan', _tambahHardSkill),
            const SizedBox(height: 18),

            // Facilities
            _buildSectionHeader('Fasilitas yang Dimiliki'),
            const SizedBox(height: 6),
            _buildFacilitiesTable(state.fasilitasDimiliki),
            const SizedBox(height: 12),

            // Input Row
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _facilityInputController,
                    decoration: const InputDecoration(
                      hintText: 'Masukkan Fasilitas',
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                ElevatedButton(
                  onPressed: _addFacility,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.all(16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: const Icon(LucideIcons.plus, size: 20),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSkillsTable(List<Map<String, String>> items, bool isSoftSkill) {
    if (items.isEmpty) return _buildEmptyState();
    return _buildUnifiedListTable(
      items: items,
      titleFn: (item) => item['skill']!,
      sublabelFn: (item) => item['level']!,
      onTap: (item) => _editSkill(item, isSoftSkill),
      emptyStateBuilder: () => _buildEmptyState(),
    );
  }

  Widget _buildFacilitiesTable(List<String> items) {
    if (items.isEmpty) return _buildEmptyState();
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary8, width: 1.0),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: List.generate(items.length, (index) {
          final item = items[index];
          final isLast = index == items.length - 1;
          return Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(
                        item,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: GoogleFonts.dmSans(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppColors.primary1,
                        ),
                      ),
                    ),
                    IconButton(
                      icon: const Icon(LucideIcons.minus, color: AppColors.red, size: 20),
                      onPressed: () => _removeFacility(item),
                    ),
                  ],
                ),
              ),
              if (!isLast)
                const Divider(
                  height: 1,
                  thickness: 1,
                  color: AppColors.tertiary9,
                ),
            ],
          );
        }),
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
    );
  }

  Widget _buildEmptyState() {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary8),
      ),
      child: Column(
        children: [
          const Icon(LucideIcons.moreHorizontal, color: AppColors.tertiary6, size: 24),
          const SizedBox(height: 6),
          Text(
            'Belum Ada',
            style: GoogleFonts.dmSans(color: AppColors.tertiary5, fontSize: 12, fontWeight: FontWeight.w500),
          )
        ],
      ),
    );
  }

  Widget _buildAddButton(String text, VoidCallback onPressed) {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton(
        onPressed: onPressed,
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          elevation: 0,
          padding: const EdgeInsets.symmetric(vertical: 12),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
        child: Text(text, style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 14)),
      ),
    );
  }
}

class _AddSkillSheet extends StatefulWidget {
  final bool isSoftSkill;
  const _AddSkillSheet({required this.isSoftSkill});

  @override
  State<_AddSkillSheet> createState() => _AddSkillSheetState();
}

class _AddSkillSheetState extends State<_AddSkillSheet> {
  final _skillController = TextEditingController();
  String _level = 'Dasar';

  @override
  void dispose() {
    _skillController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        mainAxisSize: MainAxisSize.min,
        children: [
          Align(
            alignment: Alignment.topLeft,
            child: TextButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(LucideIcons.x, size: 14, color: AppColors.red),
              label: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
              style: TextButton.styleFrom(
                padding: EdgeInsets.zero,
                minimumSize: Size.zero,
                tapTargetSize: MaterialTapTargetSize.shrinkWrap,
              ),
            ),
          ),
          const SizedBox(height: 6),
          Text(
            widget.isSoftSkill ? 'Tambah Kemampuan Non-Teknis' : 'Tambah Kemampuan Teknis',
            style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary1),
          ),
          const SizedBox(height: 12),
          _buildPopupLabel('Nama Kemampuan / Keahlian'),
          TextField(
            controller: _skillController,
            decoration: const InputDecoration(hintText: 'Masukkan Nama Kemampuan'),
          ),
          const SizedBox(height: 12),
          _buildPopupLabel('Tingkat Kemampuan'),
          const SizedBox(height: 6),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: ['Dasar', 'Sedang', 'Cakap'].map((l) {
              return Row(
                children: [
                  Radio<String>(
                    value: l,
                    groupValue: _level,
                    activeColor: AppColors.primary,
                    onChanged: (val) {
                      setState(() {
                        _level = val!;
                      });
                    },
                  ),
                  Text(l, style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.primary2, fontWeight: FontWeight.w500)),
                ],
              );
            }).toList(),
          ),
          const SizedBox(height: 18),
          ElevatedButton(
            onPressed: () {
              if (_skillController.text.isNotEmpty) {
                if (widget.isSoftSkill) {
                  AppState.instance.softSkills.add({'skill': _skillController.text, 'level': _level});
                } else {
                  AppState.instance.hardSkills.add({'skill': _skillController.text, 'level': _level});
                }
                Navigator.pop(context);
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: Text('Tambahkan', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16)),
          ),
        ],
      ),
    );
  }
}

// ==========================================
// --- STEP 6: PERSYARATAN BERKAS ADMINISTRATIF TAMBAHAN (Daftar Calon - 4) ---
// ==========================================
class CandidateForm6 extends StatefulWidget {
  const CandidateForm6({super.key});

  @override
  State<CandidateForm6> createState() => _CandidateForm6State();
}

class _CandidateForm6State extends State<CandidateForm6> {
  void _simulateUpload(String docKey) {
    setState(() {
      AppState.instance.uploadedFiles[docKey] = '${docKey.replaceAll(' ', '_')}_Upload.pdf';
    });
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('$docKey berhasil diunggah.', style: GoogleFonts.dmSans(fontSize: 12)),
        backgroundColor: Colors.green,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;
    final isBph = state.registrationType == 'BPH';

    return _CandidateFormLayout(
      step: 4,
      title: 'Persyaratan Berkas Administratif Tambahan',
      subtitle: 'Unggah berkas persyaratan pendukung yang dibutuhkan untuk memvalidasi pendaftaranmu.',
      onBack: () => Navigator.pop(context),
      onNext: () => Navigator.pushNamed(context, '/candidate/form-7').then((_) => setState(() {})),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 12),
            if (isBph) ...[
              _buildUploadCard(
                'Pas Foto Ukuran 3x4',
                state.uploadedFiles['Pas Foto']!,
                'Tambah Pas Foto',
                info: 'Syarat: Menggunakan Jaket TIK dengan background warna Biru.',
              ),
            ] else ...[
              _buildUploadCard(
                'Pas Foto Ukuran 3x4',
                state.uploadedFiles['Pas Foto']!,
                'Tambah Pas Foto',
                info: 'Syarat: Menggunakan Kemeja putih dengan background warna Biru.',
              ),
              const SizedBox(height: 12),
              _buildUploadCard('Bukti Mengikuti Instagram HIMATIK PNJ', state.uploadedFiles['Bukti Instagram']!, 'Tambah Bukti Mengikuti di Instagram', info: 'Instagram: @himatikpnj'),
              const SizedBox(height: 12),
              _buildUploadCard('Bukti Berlangganan ke Youtube HIMATIK PNJ', state.uploadedFiles['Bukti Youtube']!, 'Tambah Bukti Berlangganan di Youtube', info: 'Youtube: HIMATIK PNJ'),
              const SizedBox(height: 12),
              _buildUploadCard('Surat Pernyataan Bukan Dari Ekstra Kampus dan Partai Politik', state.uploadedFiles['Surat Pernyataan']!, 'Tambah Surat Pernyataan', info: 'Klik link ini untuk mendapatkan template-nya: Link Template'),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildUploadCard(String label, String fileName, String buttonText, {String? info}) {
    final bool hasFile = fileName.isNotEmpty;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2)),
        if (info != null) ...[
          const SizedBox(height: 4),
          Text(info, style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4)),
        ],
        const SizedBox(height: 6), // label to input/box gap
        
        Container(
          height: 90,
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: AppColors.tertiary8, width: 1.5),
          ),
          child: InkWell(
            onTap: () => _simulateUpload(label.replaceAll('Ukuran 3x4', 'Pas Foto').replaceAll('Bukti Mengikuti ', 'Bukti ').replaceAll('Bukti Berlangganan ke ', 'Bukti ').replaceAll('Surat Pernyataan Bukan Dari Ekstra Kampus dan Partai Politik', 'Surat Pernyataan')),
            borderRadius: BorderRadius.circular(12),
            child: Center(
              child: hasFile
                  ? Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(LucideIcons.fileCheck2, color: Colors.green, size: 24),
                        const SizedBox(width: 8),
                        Text(
                          fileName,
                          style: GoogleFonts.dmSans(fontSize: 14, color: Colors.green.shade800, fontWeight: FontWeight.bold),
                        ),
                      ],
                    )
                  : Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(LucideIcons.filePlus2, color: AppColors.tertiary6, size: 24),
                        const SizedBox(width: 6),
                        Text(
                          buttonText,
                          style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary5, fontWeight: FontWeight.w600),
                        ),
                      ],
                    ),
            ),
          ),
        ),
      ],
    );
  }
}

// ==========================================
// --- STEP 7: PERNYATAAN & PERSETUJUAN (Daftar Calon - 5) ---
// ==========================================
class CandidateForm7 extends StatelessWidget {
  const CandidateForm7({super.key});

  @override
  Widget build(BuildContext context) {
    return const _CandidateForm7Body();
  }
}

class _CandidateForm7Body extends StatefulWidget {
  const _CandidateForm7Body();

  @override
  State<_CandidateForm7Body> createState() => _CandidateForm7BodyState();
}

class _CandidateForm7BodyState extends State<_CandidateForm7Body> {
  bool _candidateSigned = false;
  bool _parentSigned = false;

  void _submitApplication() {
    if (!_candidateSigned || !_parentSigned) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Keduanya (Calon & Orang Tua) wajib bertanda tangan.', style: GoogleFonts.dmSans(fontSize: 12)),
          backgroundColor: AppColors.red,
        ),
      );
      return;
    }

    // Confirmation Popup Dialog
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        backgroundColor: Colors.white,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        title: Text('Apakah kamu yakin?', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1)),
        content: Text(
          'Formulir pendaftaran tidak akan bisa diubah setelah dikirim.',
          style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('Batal', style: GoogleFonts.dmSans(color: AppColors.red, fontWeight: FontWeight.bold)),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context); // Pop dialog
              AppState.instance.hasSubmittedRecruitment = true;
              AppState.instance.isSignedCandidate = true;
              AppState.instance.isSignedParent = true;
              Navigator.pushReplacementNamed(context, '/candidate/sent');
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary, 
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: Text('Kirim', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  void _uploadSignature(bool isCandidate) {
    setState(() {
      if (isCandidate) {
        _candidateSigned = true;
        AppState.instance.isSignedCandidate = true;
        // Mock points for rendering
        AppState.instance.candidateSignaturePoints = [
          const Offset(10, 50), const Offset(40, 20), const Offset(70, 80), const Offset(100, 30), null
        ];
      } else {
        _parentSigned = true;
        AppState.instance.isSignedParent = true;
        AppState.instance.parentSignaturePoints = [
          const Offset(20, 70), const Offset(50, 40), const Offset(80, 60), const Offset(110, 30), null
        ];
      }
    });
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Gambar Tanda Tangan berhasil diunggah.', style: GoogleFonts.dmSans(fontSize: 12)),
        backgroundColor: Colors.green,
      ),
    );
  }

  void _drawSignature(bool isCandidate) async {
    final List<Offset?>? result = await Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => SignatureFullscreenCanvas(
          title: isCandidate ? 'Tanda Tangan Calon' : 'Tanda Tangan Orang Tua Calon',
        ),
      ),
    );

    if (result != null && result.isNotEmpty) {
      if (!mounted) return;
      setState(() {
        if (isCandidate) {
          _candidateSigned = true;
          AppState.instance.isSignedCandidate = true;
          AppState.instance.candidateSignaturePoints = result;
        } else {
          _parentSigned = true;
          AppState.instance.isSignedParent = true;
          AppState.instance.parentSignaturePoints = result;
        }
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Tanda tangan berhasil dibuat.', style: GoogleFonts.dmSans(fontSize: 12)),
          backgroundColor: Colors.green,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;

    return _CandidateFormLayout(
      step: 5,
      title: 'Pernyataan & Persetujuan',
      subtitle: 'Bubuhkan tanda tangan sebagai persetujuan bahwa seluruh data yang diisi adalah benar.',
      onBack: () => Navigator.pop(context),
      onNext: _submitApplication,
      nextLabel: 'Kirim',
      nextIcon: LucideIcons.send,
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 12),

            // Tanda Tangan Calon
            _buildSignatureSelection(
              title: 'Tanda Tangan Calon',
              isSigned: _candidateSigned,
              points: state.candidateSignaturePoints,
              onUpload: () => _uploadSignature(true),
              onDraw: () => _drawSignature(true),
              onClear: () {
                setState(() {
                  _candidateSigned = false;
                  state.candidateSignaturePoints = null;
                });
              },
            ),
            const SizedBox(height: 18),

            // Tanda Tangan Orang Tua
            _buildSignatureSelection(
              title: 'Tanda Tangan Orang Tua Calon',
              isSigned: _parentSigned,
              points: state.parentSignaturePoints,
              onUpload: () => _uploadSignature(false),
              onDraw: () => _drawSignature(false),
              onClear: () {
                setState(() {
                  _parentSigned = false;
                  state.parentSignaturePoints = null;
                });
              },
            ),
          ],
        ),
      ),
    );
  }

  void _showSignatureOptionsPopup(BuildContext context, {required VoidCallback onUpload, required VoidCallback onDraw, required String title}) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(12)),
      ),
      builder: (context) {
        return Container(
          padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 18),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Text(
                'Pilih Metode Tanda Tangan',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 6),
              Text(
                title,
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: AppColors.tertiary4,
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 18),
              ListTile(
                leading: const Icon(LucideIcons.pencil, color: AppColors.primary),
                title: Text(
                  'Gambar Langsung',
                  style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 14, color: AppColors.primary1),
                ),
                subtitle: Text(
                  'Gambar tanda tangan menggunakan layar ponsel',
                  style: GoogleFonts.dmSans(fontSize: 11, color: AppColors.tertiary4),
                ),
                onTap: () {
                  Navigator.pop(context);
                  onDraw();
                },
              ),
              const Divider(color: AppColors.tertiary9, height: 1),
              ListTile(
                leading: const Icon(LucideIcons.upload, color: AppColors.primary),
                title: Text(
                  'Upload Gambar',
                  style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 14, color: AppColors.primary1),
                ),
                subtitle: Text(
                  'Pilih file gambar tanda tangan dari galeri',
                  style: GoogleFonts.dmSans(fontSize: 11, color: AppColors.tertiary4),
                ),
                onTap: () {
                  Navigator.pop(context);
                  onUpload();
                },
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildSignatureSelection({
    required String title,
    required bool isSigned,
    required List<Offset?>? points,
    required VoidCallback onUpload,
    required VoidCallback onDraw,
    required VoidCallback onClear,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2),
        ),
        const SizedBox(height: 6), // label to box gap

        GestureDetector(
          onTap: isSigned
              ? null
              : () => _showSignatureOptionsPopup(
                    context,
                    onUpload: onUpload,
                    onDraw: onDraw,
                    title: title,
                  ),
          child: Container(
            height: 140,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: AppColors.tertiary8, width: 1.0),
            ),
            child: Stack(
              children: [
                if (isSigned) ...[
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: CustomPaint(
                        painter: SignaturePainter(points: points ?? [], isPreview: true),
                        size: const Size(200, 100),
                      ),
                    ),
                  ),
                  Positioned(
                    top: 8,
                    right: 8,
                    child: GestureDetector(
                      onTap: onClear,
                      child: const Icon(LucideIcons.x, size: 20, color: AppColors.red),
                    ),
                  ),
                ] else
                  Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(LucideIcons.signature, size: 36, color: AppColors.tertiary6),
                        const SizedBox(height: 8),
                        Text(
                          'Tambah $title',
                          style: GoogleFonts.dmSans(
                            fontSize: 14,
                            fontWeight: FontWeight.bold,
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
      ],
    );
  }
}

// --- FULL SCREEN SIGNATURE CANVAS WITH COORDINATES CROPPING & OFFSET ---
class SignatureFullscreenCanvas extends StatefulWidget {
  final String title;
  const SignatureFullscreenCanvas({super.key, required this.title});

  @override
  State<SignatureFullscreenCanvas> createState() => _SignatureFullscreenCanvasState();
}

class _SignatureFullscreenCanvasState extends State<SignatureFullscreenCanvas> {
  final List<Offset?> _points = [];

  void _finishDrawing() {
    if (_points.isEmpty) {
      Navigator.pop(context);
      return;
    }

    // 1. Calculate Bounding Box of the drawn points
    double minX = double.infinity;
    double minY = double.infinity;
    double maxX = -double.infinity;
    double maxY = -double.infinity;

    for (var p in _points) {
      if (p != null) {
        if (p.dx < minX) minX = p.dx;
        if (p.dy < minY) minY = p.dy;
        if (p.dx > maxX) maxX = p.dx;
        if (p.dy > maxY) maxY = p.dy;
      }
    }

    // 2. Crop signature with Offset/Padding (e.g. 16px padding around signature)
    List<Offset?> croppedPoints = [];
    const double padding = 16.0;

    if (minX < double.infinity && minY < double.infinity) {
      for (var p in _points) {
        if (p != null) {
          // Shift point to origin and add padding offset
          croppedPoints.add(Offset(p.dx - minX + padding, p.dy - minY + padding));
        } else {
          croppedPoints.add(null);
        }
      }
    }

    Navigator.pop(context, croppedPoints);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        title: Text(widget.title, style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary1)),
        leading: IconButton(
          icon: const Icon(LucideIcons.x, color: AppColors.red),
          onPressed: () => Navigator.pop(context), // Close fullscreen canvas
        ),
      ),
      body: SafeArea(
        child: Column(
          children: [
            // Instructions
            Padding(
              padding: const EdgeInsets.all(12.0),
              child: Text(
                'Gunakan jari Anda untuk menggambar tanda tangan pada area di bawah ini.',
                style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4),
                textAlign: TextAlign.center,
              ),
            ),

            // Canvas (Takes remaining space)
            Expanded(
              child: Container(
                margin: const EdgeInsets.symmetric(horizontal: 18),
                decoration: BoxDecoration(
                  color: AppColors.tertiary,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: AppColors.tertiary8, width: 1.5),
                ),
                child: GestureDetector(
                  onPanUpdate: (details) {
                    setState(() {
                      _points.add(details.localPosition);
                    });
                  },
                  onPanEnd: (_) => _points.add(null),
                  child: CustomPaint(
                    painter: SignaturePainter(points: _points),
                    size: Size.infinite,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 18), // Normal gap

            // Action Buttons
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 18.0, vertical: 12.0),
              child: Row(
                children: [
                  Expanded(
                    child: OutlinedButton(
                      onPressed: () => setState(() => _points.clear()),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: AppColors.red,
                        side: const BorderSide(color: AppColors.red),
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      child: Text('Bersihkan', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold)),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: ElevatedButton(
                      onPressed: _finishDrawing,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      child: Text('Selesai', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold)),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ==========================================
// --- SUCCESS SCREEN (Formulir Terkirim) ---
// ==========================================
class CandidateSent extends StatelessWidget {
  const CandidateSent({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const Spacer(),
              const Center(
                child: Icon(
                  LucideIcons.checkCircle2,
                  size: 90,
                  color: Colors.green,
                ),
              ),
              const SizedBox(height: 18),

              Text(
                'Pendaftaran Terkirim!',
                textAlign: TextAlign.center,
                style: GoogleFonts.dmSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                  height: 1.2,
                ),
              ),
              const SizedBox(height: 6),

              Text(
                'Terima kasih! Data pendaftaran Anda berhasil terkirim. Status kelulusan dan seleksi berkas dapat dipantau berkala melalui Dashboard.',
                textAlign: TextAlign.center,
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: AppColors.tertiary4,
                  height: 1.5,
                ),
              ),
              const Spacer(),

              ElevatedButton(
                onPressed: () {
                  Navigator.pushNamedAndRemoveUntil(
                    context,
                    '/interviewSelection',
                    (route) => route.settings.name == '/dashboard',
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: Text('Pilih Jadwal Wawancara', style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16)),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// ==========================================
// --- CANDIDATE DETAIL SCREEN (Formulir Kandidat) ---
// ==========================================
class CandidateDetailScreen extends StatelessWidget {
  const CandidateDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.arrowLeft, color: AppColors.primary1),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Formulir Pendaftaran',
          style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(18.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Text(
                'Formulir Pendaftaran',
                style: GoogleFonts.dmSans(fontSize: 32, fontWeight: FontWeight.bold, color: AppColors.primary1),
              ),
              const SizedBox(height: 6),
              Text(
                'Ingin mengubah formulir? Hubungi PSDM.',
                style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4),
              ),
              const SizedBox(height: 18),

              // Tipe Pendaftaran
              _buildDetailLabel('Tipe Pendaftaran'),
              _buildDetailValueCard(state.registrationType),
              const SizedBox(height: 12),

              // Pilihan Biro
              _buildDetailLabel('Biro Pilihan'),
              ...state.biroPilihan.asMap().entries.map((entry) => _buildDetailValueCard('${entry.value} (${entry.key + 1})')),
              const SizedBox(height: 12),

              // Alasan
              _buildDetailLabel('Alasan Memilih Biro atau Departemen'),
              _buildDetailValueCard(state.alasanMemilih),
              const SizedBox(height: 12),

              // Kekurangan
              _buildDetailLabel('Deskripsikan Kekurangan Kamu'),
              _buildDetailValueCard(state.deskripsiKekurangan),
              const SizedBox(height: 12),

              // Langkah Konkret
              _buildDetailLabel('Langkah Konkret Apa yang Kamu Ambil Jika Terpilih'),
              _buildDetailValueCard(state.langkahKonkret),
              const SizedBox(height: 12),

              // Formal Education
              _buildDetailLabel('Riwayat Pendidikan Formal'),
              _buildEducationDetailTable(state.riwayatFormal, true),
              const SizedBox(height: 12),

              // Informal Education
              _buildDetailLabel('Riwayat Pendidikan Informal'),
              _buildEducationDetailTable(state.riwayatInformal, false),
              const SizedBox(height: 12),

              // Pengalaman Organisasi
              _buildDetailLabel('Pengalaman Organisasi Luar Kampus'),
              _buildExperienceDetailTable(state.pengalamanOrganisasi, true),
              const SizedBox(height: 12),

              // Pengalaman Kepanitiaan
              _buildDetailLabel('Pengalaman Kepanitiaan Dalam/Luar Kampus'),
              _buildExperienceDetailTable(state.pengalamanKepanitiaan, false),
              const SizedBox(height: 12),

              // Soft Skills
              _buildDetailLabel('Kemampuan Non-Teknis (Soft Skill)'),
              _buildSkillsDetailTable(state.softSkills),
              const SizedBox(height: 12),

              // Hard Skills
              _buildDetailLabel('Kemampuan Teknis (Hard Skill)'),
              _buildSkillsDetailTable(state.hardSkills),
              const SizedBox(height: 12),

              // Facilities
              _buildDetailLabel('Fasilitas yang Dimiliki'),
              _buildFacilitiesDetailTable(state.fasilitasDimiliki),
              const SizedBox(height: 18),

              // View Attachments Button
              ElevatedButton.icon(
                onPressed: () => Navigator.pushNamed(context, '/candidate/attachments'),
                icon: const Icon(LucideIcons.paperclip, size: 16),
                label: Text('Lihat Lampiran', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildEducationDetailTable(List<Map<String, String>> items, bool isFormal) {
    return _buildReadOnlyListTable(
      items: items,
      titleFn: (item) => item['school']!,
      sublabelFn: (item) => isFormal
          ? 'Tahun ${item['years']!}, di ${item['city']!}.${item['major'] != '-' ? ' Jurusan ${item['major']!}' : ''}'
          : 'Tahun ${item['years']!}, oleh ${item['city']!}',
      emptyStateBuilder: () => _buildDetailValueCard('Tidak ada'),
    );
  }

  Widget _buildExperienceDetailTable(List<Map<String, String>> items, bool isOrg) {
    return _buildReadOnlyListTable(
      items: items,
      titleFn: (item) => item['name']!,
      sublabelFn: (item) => isOrg
          ? 'Tahun ${item['years']!}, di ${item['institution']!}. ${item['position']!}.'
          : 'Tahun ${item['years']!}, oleh ${item['institution']!}. ${item['position']!}.',
      emptyStateBuilder: () => _buildDetailValueCard('Tidak ada'),
    );
  }

  Widget _buildSkillsDetailTable(List<Map<String, String>> items) {
    return _buildReadOnlyListTable(
      items: items,
      titleFn: (item) => item['skill']!,
      sublabelFn: (item) => item['level']!,
      emptyStateBuilder: () => _buildDetailValueCard('Tidak ada'),
    );
  }

  Widget _buildFacilitiesDetailTable(List<String> items) {
    if (items.isEmpty) return _buildDetailValueCard('Tidak ada');
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary8, width: 1.0),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: List.generate(items.length, (index) {
          final item = items[index];
          final isLast = index == items.length - 1;
          return Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Row(
                  children: [
                    Expanded(
                      child: Text(
                        item,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: GoogleFonts.dmSans(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppColors.primary1,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              if (!isLast)
                const Divider(
                  height: 1,
                  thickness: 1,
                  color: AppColors.tertiary9,
                ),
            ],
          );
        }),
      ),
    );
  }

  Widget _buildDetailLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6.0),
      child: Text(
        text,
        style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2),
      ),
    );
  }

  Widget _buildDetailValueCard(String text) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(bottom: 6),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary8),
      ),
      child: Text(text, style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.primary1)),
    );
  }
}

// ==========================================
// --- CANDIDATE ATTACHMENTS SCREEN (Lampiran Kandidat) ---
// ==========================================
class CandidateAttachmentsScreen extends StatelessWidget {
  const CandidateAttachmentsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;
    final isBph = state.registrationType == 'BPH';

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.arrowLeft, color: AppColors.primary1),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Lampiran Pendaftaran',
          style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16),
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(18.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Text(
                'Lampiran Pendaftaran',
                style: GoogleFonts.dmSans(fontSize: 32, fontWeight: FontWeight.bold, color: AppColors.primary1),
              ),
              const SizedBox(height: 18),

              _buildAttachmentItem(
                context,
                'Pas Foto Ukuran 3x4',
                state.uploadedFiles['Pas Foto']!,
                info: isBph
                    ? 'Syarat BPH: Jaket TIK background Biru'
                    : 'Syarat Staff: Kemeja Putih background Biru',
              ),
              if (!isBph) ...[
                const SizedBox(height: 12),
                _buildAttachmentItem(context, 'Bukti Mengikuti Instagram HIMATIK PNJ', state.uploadedFiles['Bukti Instagram']!),
                const SizedBox(height: 12),
                _buildAttachmentItem(context, 'Bukti Berlangganan ke Youtube HIMATIK PNJ', state.uploadedFiles['Bukti Youtube']!),
                const SizedBox(height: 12),
                _buildAttachmentItem(context, 'Surat Pernyataan Bukan Dari Ekstra Kampus dan Partai Politik', state.uploadedFiles['Surat Pernyataan']!),
              ],
              const SizedBox(height: 12),
              _buildAttachmentItem(context, 'Tanda Tangan Calon', state.isSignedCandidate ? 'Terlampir' : ''),
              const SizedBox(height: 12),
              _buildAttachmentItem(context, 'Tanda Tangan Orang Tua Calon', state.isSignedParent ? 'Terlampir' : ''),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildAttachmentItem(BuildContext context, String title, String val, {String? info}) {
    final hasVal = val.isNotEmpty;
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.tertiary8),
      ),
      child: ListTile(
        title: Text(title, style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2)),
        subtitle: info != null
            ? Text(info, style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4))
            : null,
        trailing: const Icon(LucideIcons.chevronRight, size: 16),
        onTap: () {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(
                hasVal ? 'Membuka berkas: $title' : 'Berkas belum diunggah.',
                style: GoogleFonts.dmSans(fontSize: 12),
              ),
              backgroundColor: hasVal ? Colors.green : AppColors.red,
            ),
          );
        },
      ),
    );
  }
}

// ==========================================
// --- CANDIDATE INTERVIEW DETAIL SCREEN (Wawancara Kandidat) ---
// ==========================================
class CandidateInterviewDetailScreen extends StatelessWidget {
  const CandidateInterviewDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = AppState.instance;
    final slot = state.getSelectedSlotDetails() ?? {
      'day': 'Jum\'at',
      'date': '12 Juni 2026',
      'time': 'Sesi 3 (13:00 s.d. 14:30)',
      'room': 'Ruang AA.302',
    };

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.arrowLeft, color: AppColors.primary1),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Detail Wawancara',
          style: GoogleFonts.dmSans(fontWeight: FontWeight.bold, fontSize: 16),
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(18.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Text(
                'Wawancara Staff HIMATIK PNJ',
                style: GoogleFonts.dmSans(fontSize: 32, fontWeight: FontWeight.bold, color: AppColors.primary1),
              ),
              const SizedBox(height: 18),

              Text('Detail Pelaksanaan', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2)),
              const SizedBox(height: 12),
              _buildIconTextRow(LucideIcons.calendar, '${slot['day']}, ${slot['date']}'),
              const SizedBox(height: 12),
              _buildIconTextRow(LucideIcons.clock, slot['time']),
              const SizedBox(height: 12),
              _buildIconTextRow(LucideIcons.mapPin, slot['room']),
              const SizedBox(height: 18),

              Text('Persyaratan', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2)),
              const SizedBox(height: 6),
              _buildBulletPoint('Mengenakan kemeja bebas rapi'),
              _buildBulletPoint('Memakai sepatu tertutup'),
              const SizedBox(height: 18),

              Text('Kontak', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2)),
              const SizedBox(height: 6),
              _buildBulletPoint('Hubungi Hubungan Masyarakat (Humas)'),
              _buildBulletPoint('WA: 0812-3456-7890 (Kak Humas)'),
              const Spacer(),

              ElevatedButton.icon(
                onPressed: () => Navigator.pushNamed(context, '/candidate/detail'),
                icon: const Icon(LucideIcons.fileText, size: 16),
                label: Text('Lihat Formulir Pendaftaran', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildIconTextRow(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, color: AppColors.primary3, size: 20),
        const SizedBox(width: 12),
        Text(text, style: GoogleFonts.dmSans(fontSize: 16, color: AppColors.primary1, fontWeight: FontWeight.w500)),
      ],
    );
  }

  Widget _buildBulletPoint(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4.0),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('• ', style: GoogleFonts.dmSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.primary2)),
          Expanded(child: Text(text, style: GoogleFonts.dmSans(fontSize: 12, color: AppColors.tertiary4))),
        ],
      ),
    );
  }
}

class SignaturePainter extends CustomPainter {
  final List<Offset?> points;
  final bool isPreview;
  SignaturePainter({required this.points, this.isPreview = false});

  @override
  void paint(Canvas canvas, Size size) {
    if (points.isEmpty) return;

    final paint = Paint()
      ..color = AppColors.primary1
      ..strokeCap = StrokeCap.round
      ..strokeWidth = 3.0
      ..style = PaintingStyle.stroke;

    // Calculate bounding box of the points to determine if and how to scale
    double minX = double.infinity;
    double minY = double.infinity;
    double maxX = -double.infinity;
    double maxY = -double.infinity;

    for (var p in points) {
      if (p != null) {
        if (p.dx < minX) minX = p.dx;
        if (p.dy < minY) minY = p.dy;
        if (p.dx > maxX) maxX = p.dx;
        if (p.dy > maxY) maxY = p.dy;
      }
    }

    if (minX == double.infinity || minY == double.infinity) {
      return;
    }

    double width = maxX - minX;
    double height = maxY - minY;

    if (width == 0) width = 1;
    if (height == 0) height = 1;

    // Only scale/center if it is explicitly a preview
    bool shouldScale = isPreview && size.width.isFinite && size.height.isFinite;

    canvas.save();
    if (shouldScale) {
      double scaleX = size.width / (width + 32);
      double scaleY = size.height / (height + 32);
      double scale = scaleX < scaleY ? scaleX : scaleY;

      // Translate coordinates to center within the canvas
      double dx = (size.width - (width * scale)) / 2 - minX * scale;
      double dy = (size.height - (height * scale)) / 2 - minY * scale;

      canvas.translate(dx, dy);
      canvas.scale(scale);
    }

    for (int i = 0; i < points.length - 1; i++) {
      if (points[i] != null && points[i + 1] != null) {
        canvas.drawLine(points[i]!, points[i + 1]!, paint);
      }
    }
    canvas.restore();
  }

  @override
  bool shouldRepaint(SignaturePainter oldDelegate) => true;
}

// ==========================================
// --- GLOBAL HELPER LIST TABLE WIDGETS ---
// ==========================================

Widget _buildUnifiedListTable({
  required List<Map<String, String>> items,
  required String Function(Map<String, String>) titleFn,
  required String Function(Map<String, String>) sublabelFn,
  required ValueChanged<Map<String, String>> onTap,
  required Widget Function() emptyStateBuilder,
}) {
  if (items.isEmpty) return emptyStateBuilder();
  return Container(
    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(12),
      border: Border.all(color: AppColors.tertiary8, width: 1.0),
    ),
    child: Column(
      mainAxisSize: MainAxisSize.min,
      children: List.generate(items.length, (index) {
        final item = items[index];
        final isLast = index == items.length - 1;
        return Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            InkWell(
              onTap: () => onTap(item),
              borderRadius: BorderRadius.vertical(
                top: index == 0 ? const Radius.circular(11) : Radius.zero,
                bottom: isLast ? const Radius.circular(11) : Radius.zero,
              ),
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Row(
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            titleFn(item),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.dmSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: AppColors.primary1,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            sublabelFn(item),
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.dmSans(
                              fontSize: 12,
                              color: AppColors.tertiary4,
                              height: 1.3,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 12),
                    const Icon(
                      LucideIcons.chevronRight,
                      size: 20,
                      color: AppColors.tertiary5,
                    ),
                  ],
                ),
              ),
            ),
            if (!isLast)
              const Divider(
                height: 1,
                thickness: 1,
                color: AppColors.tertiary9,
              ),
          ],
        );
      }),
    ),
  );
}

Widget _buildReadOnlyListTable({
  required List<Map<String, String>> items,
  required String Function(Map<String, String>) titleFn,
  required String Function(Map<String, String>) sublabelFn,
  required Widget Function() emptyStateBuilder,
}) {
  if (items.isEmpty) return emptyStateBuilder();
  return Container(
    margin: const EdgeInsets.only(bottom: 12),
    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(12),
      border: Border.all(color: AppColors.tertiary8, width: 1.0),
    ),
    child: Column(
      mainAxisSize: MainAxisSize.min,
      children: List.generate(items.length, (index) {
        final item = items[index];
        final isLast = index == items.length - 1;
        return Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              child: Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          titleFn(item),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: GoogleFonts.dmSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary1,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          sublabelFn(item),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: GoogleFonts.dmSans(
                            fontSize: 12,
                            color: AppColors.tertiary4,
                            height: 1.3,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            if (!isLast)
              const Divider(
                height: 1,
                thickness: 1,
                color: AppColors.tertiary9,
              ),
          ],
        );
      }),
    ),
  );
}

