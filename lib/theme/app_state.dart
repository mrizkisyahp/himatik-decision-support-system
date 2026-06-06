
import 'dart:ui';

enum UserRole {
  none,
  candidate,
  reviewer,
}

class AppState {
  // Singleton instance
  static final AppState _instance = AppState._internal();
  static AppState get instance => _instance;

  AppState._internal() {
    reset();
  }

  // --- Auth State ---
  UserRole role = UserRole.none;
  String email = '';
  String name = '';

  // --- Candidate Profile State ---
  bool hasSubmittedProfile = false;
  String registrationType = 'Staff'; // 'BPH' or 'Staff'
  String namaLengkap = '';
  String namaPanggilan = '';
  String nim = '';
  String programStudi = '';
  String kelas = '';
  String nomorTelepon = '';
  String alamatLengkap = '';

  // --- Recruitment Application State ---
  bool hasSubmittedRecruitment = false;
  
  // Step 1: Preferences & Essays
  List<String> biroPilihan = []; // Preferred bureaus in priority order
  String alasanMemilih = '';
  String deskripsiKekurangan = '';
  String langkahKonkret = '';

  // Step 2: Education & Experience
  List<Map<String, String>> riwayatFormal = [
    {
      'id': '1',
      'school': 'SD Nusantara',
      'years': '2012 - 2018',
      'city': 'Jakarta Timur',
      'major': '-',
    },
    {
      'id': '2',
      'school': 'SMPN 255 Jakarta',
      'years': '2018 - 2021',
      'city': 'Jakarta Timur',
      'major': '-',
    },
    {
      'id': '3',
      'school': 'SMAN 12 Jakarta',
      'years': '2021 - 2024',
      'city': 'Jakarta Timur',
      'major': 'IPA',
    }
  ];

  List<Map<String, String>> riwayatInformal = [];

  List<Map<String, String>> pengalamanOrganisasi = [
    {
      'id': '1',
      'name': 'English Club',
      'years': '2022 - 2023',
      'institution': 'SMAN 12 Jakarta',
      'position': 'Sebagai Ketua',
    }
  ];

  List<Map<String, String>> pengalamanKepanitiaan = [
    {
      'id': '1',
      'name': 'CSFest',
      'years': '2024',
      'institution': 'HIMATIK PNJ',
      'position': 'Sebagai Staff Dokumentasi',
    }
  ];

  // Step 3: Skills & Facilities
  List<Map<String, String>> softSkills = [
    {'skill': 'Time Management', 'level': 'Cakap'}
  ];
  List<Map<String, String>> hardSkills = [];
  List<String> fasilitasDimiliki = ['Handphone', 'Laptop'];

  // Step 4: Documents Upload Simulation
  Map<String, String> uploadedFiles = {
    'Pas Foto': '',
    'Bukti Instagram': '',
    'Bukti Youtube': '',
    'Surat Pernyataan': '',
  };

  // Step 5: Signatures
  String candidateSignatureBase64 = '';
  String parentSignatureBase64 = '';
  List<Offset?>? candidateSignaturePoints;
  List<Offset?>? parentSignaturePoints;
  bool isSignedCandidate = false;
  bool isSignedParent = false;

  // --- Interview Session Slots ---
  String? selectedInterviewSlot;
  
  // List of all slots with states: 'available', 'occupied', 'unavail'
  final List<Map<String, dynamic>> interviewSlots = [
    {
      'id': 'slot_1',
      'day': 'Jum\'at',
      'date': '12 Juni 2026',
      'time': 'Sesi 1 (08:00 s.d. 09:30)',
      'room': 'Ruang AA.301',
      'state': 'unavail', // Gray/Tertiary
    },
    {
      'id': 'slot_2',
      'day': 'Jum\'at',
      'date': '12 Juni 2026',
      'time': 'Sesi 2 (10:00 s.d. 11:30)',
      'room': 'Ruang AA.301',
      'state': 'occupied', // Red
    },
    {
      'id': 'slot_3',
      'day': 'Jum\'at',
      'date': '12 Juni 2026',
      'time': 'Sesi 3 (13:00 s.d. 14:30)',
      'room': 'Ruang AA.302',
      'state': 'available', // Standard
    },
    {
      'id': 'slot_4',
      'day': 'Jum\'at',
      'date': '12 Juni 2026',
      'time': 'Sesi 4 (15:00 s.d. 16:30)',
      'room': 'Ruang AA.303',
      'state': 'available', // Standard
    },
    {
      'id': 'slot_5',
      'day': 'Sabtu',
      'date': '13 Juni 2026',
      'time': 'Sesi 1 (08:00 s.d. 09:30)',
      'room': 'Ruang AA.201',
      'state': 'occupied', // Red
    },
    {
      'id': 'slot_6',
      'day': 'Sabtu',
      'date': '13 Juni 2026',
      'time': 'Sesi 2 (10:00 s.d. 11:30)',
      'room': 'Ruang AA.201',
      'state': 'available', // Standard
    }
  ];

  // --- Reset to default mock states ---
  void reset() {
    role = UserRole.none;
    email = '';
    name = '';
    
    // Reset Candidate Profile
    hasSubmittedProfile = false;
    registrationType = 'Staff';
    namaLengkap = 'Nizar Rizki Ardiansyah';
    namaPanggilan = 'Nizar';
    nim = '2207421001';
    programStudi = 'Teknik Informatika';
    kelas = 'TI-4A';
    nomorTelepon = '081234567890';
    alamatLengkap = 'Jl. Grafika No. 1, Srengseng Sawah, Jagakarsa, Jakarta Selatan';

    // Reset Recruitment
    hasSubmittedRecruitment = false;
    biroPilihan = [];
    alasanMemilih = '';
    deskripsiKekurangan = '';
    langkahKonkret = '';
    
    riwayatFormal = [
      {
        'id': '1',
        'school': 'SD Nusantara',
        'years': '2012 - 2018',
        'city': 'Jakarta Timur',
        'major': '-',
      },
      {
        'id': '2',
        'school': 'SMPN 255 Jakarta',
        'years': '2018 - 2021',
        'city': 'Jakarta Timur',
        'major': '-',
      },
      {
        'id': '3',
        'school': 'SMAN 12 Jakarta',
        'years': '2021 - 2024',
        'city': 'Jakarta Timur',
        'major': 'IPA',
      }
    ];
    riwayatInformal = [];
    pengalamanOrganisasi = [
      {
        'id': '1',
        'name': 'English Club',
        'years': '2022 - 2023',
        'institution': 'SMAN 12 Jakarta',
        'position': 'Sebagai Ketua',
      }
    ];
    pengalamanKepanitiaan = [
      {
        'id': '1',
        'name': 'CSFest',
        'years': '2024',
        'institution': 'HIMATIK PNJ',
        'position': 'Sebagai Staff Dokumentasi',
      }
    ];

    softSkills = [
      {'skill': 'Time Management', 'level': 'Cakap'}
    ];
    hardSkills = [];
    fasilitasDimiliki = ['Handphone', 'Laptop'];

    uploadedFiles = {
      'Pas Foto': '',
      'Bukti Instagram': '',
      'Bukti Youtube': '',
      'Surat Pernyataan': '',
    };

    candidateSignatureBase64 = '';
    parentSignatureBase64 = '';
    candidateSignaturePoints = null;
    parentSignaturePoints = null;
    isSignedCandidate = false;
    isSignedParent = false;

    selectedInterviewSlot = null;
    
    // Restore states of slot
    interviewSlots[0]['state'] = 'unavail';
    interviewSlots[1]['state'] = 'occupied';
    interviewSlots[2]['state'] = 'available';
    interviewSlots[3]['state'] = 'available';
    interviewSlots[4]['state'] = 'occupied';
    interviewSlots[5]['state'] = 'available';
  }

  // Helper to set login
  void login(String userEmail) {
    email = userEmail.trim().toLowerCase();
    if (email.contains('reviewer') || email.contains('fikri')) {
      role = UserRole.reviewer;
      name = 'Fikri';
    } else {
      role = UserRole.candidate;
      name = namaPanggilan.isNotEmpty ? namaPanggilan : 'Nizar';
    }
  }

  // Get active schedule details
  Map<String, dynamic>? getSelectedSlotDetails() {
    if (selectedInterviewSlot == null) return null;
    try {
      return interviewSlots.firstWhere((slot) => slot['id'] == selectedInterviewSlot);
    } catch (_) {
      return null;
    }
  }
}
