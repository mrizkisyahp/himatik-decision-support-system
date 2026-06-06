import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import '../widgets/orderable_multiselect_dropdown.dart';

class DropdownDemoScreen extends StatefulWidget {
  const DropdownDemoScreen({super.key});

  @override
  State<DropdownDemoScreen> createState() => _DropdownDemoScreenState();
}

class _DropdownDemoScreenState extends State<DropdownDemoScreen> {
  // Data pilihan minat HIMATIK
  final List<String> _bidangMinat = [
    'Riset & Teknologi (Ristek)',
    'Hubungan Masyarakat (Humas)',
    'Pengembangan Sumber Daya Mahasiswa (PSDM)',
    'Kewirausahaan (KWU)',
    'Pengabdian Masyarakat (Pengmas)',
    'Seni & Olahraga (SBO)',
  ];

  // List untuk menyimpan urutan pilihan user
  List<String> _minatTerpilih = [];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey.shade50,
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.arrowLeft),
          onPressed: () => Navigator.of(context).pop(),
        ),
        title: Text(
          'Demo Fitur & Font',
          style: GoogleFonts.dmSans(fontWeight: FontWeight.bold),
        ),
        backgroundColor: Colors.white,
        foregroundColor: Colors.black87,
        elevation: 0.5,
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header Card
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Colors.blue.shade600, Colors.blue.shade800],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.blue.shade200.withAlpha(128),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(LucideIcons.sparkles, color: Colors.white, size: 24),
                        const SizedBox(width: 8),
                        Text(
                          'DM Sans & Lucide Icons',
                          style: GoogleFonts.dmSans(
                            color: Colors.white,
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Text(
                      'Halaman ini menggunakan font DM Sans sebagai font utama, icon premium dari Lucide, serta widget kustom Dropdown Multiselect Berurutan.',
                      style: GoogleFonts.dmSans(
                        color: Colors.white70,
                        fontSize: 14,
                        height: 1.4,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 28),

              // Title Section
              Text(
                'Dropdown Multiselect Berurutan (Maks. 3 Pilihan)',
                style: GoogleFonts.dmSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Colors.black87,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                'Pilih maksimal 3 divisi. Urutan nomor mencerminkan prioritas pilihan Anda (1 = Prioritas Pertama).',
                style: GoogleFonts.dmSans(
                  fontSize: 13,
                  color: Colors.grey.shade600,
                ),
              ),
              const SizedBox(height: 12),

              // Dropdown Implementasi
              OrderableMultiSelectDropdown<String>(
                hint: 'Pilih Divisi Minat Anda...',
                items: _bidangMinat,
                selectedItems: _minatTerpilih,
                itemLabelBuilder: (item) => item,
                maxSelections: 3, // Batasan maksimal 3 pilihan
                onChanged: (newList) {
                  setState(() {
                    _minatTerpilih = newList;
                  });
                },
              ),
              const SizedBox(height: 28),

              // Hasil pilihan berurutan
              Text(
                'Hasil Urutan Prioritas Pilihan:',
                style: GoogleFonts.dmSans(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Colors.black87,
                ),
              ),
              const SizedBox(height: 12),
              
              if (_minatTerpilih.isEmpty)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: Colors.grey.shade200),
                  ),
                  child: Column(
                    children: [
                      Icon(LucideIcons.listFilter, size: 32, color: Colors.grey.shade400),
                      const SizedBox(height: 8),
                      Text(
                        'Belum ada divisi terpilih.\nSilakan klik dropdown di atas.',
                        textAlign: TextAlign.center,
                        style: GoogleFonts.dmSans(
                          color: Colors.grey.shade500,
                          fontSize: 14,
                        ),
                      ),
                    ],
                  ),
                )
              else
                ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: _minatTerpilih.length,
                  itemBuilder: (context, index) {
                    final rank = index + 1;
                    final value = _minatTerpilih[index];

                    return Container(
                      margin: const EdgeInsets.only(bottom: 8),
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: rank == 1 ? Colors.blue.shade100 : Colors.grey.shade200,
                        ),
                        boxShadow: [
                          if (rank == 1)
                            BoxShadow(
                              color: Colors.blue.shade50.withAlpha(128),
                              blurRadius: 4,
                              offset: const Offset(0, 2),
                            ),
                        ],
                      ),
                      child: Row(
                        children: [
                          Container(
                            width: 32,
                            height: 32,
                            decoration: BoxDecoration(
                              color: rank == 1 ? Colors.blue.shade600 : Colors.grey.shade200,
                              shape: BoxShape.circle,
                            ),
                            alignment: Alignment.center,
                            child: Text(
                              '$rank',
                              style: TextStyle(
                                color: rank == 1 ? Colors.white : Colors.black87,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  value,
                                  style: GoogleFonts.dmSans(
                                    fontSize: 15,
                                    fontWeight: rank == 1 ? FontWeight.bold : FontWeight.w500,
                                    color: rank == 1 ? Colors.blue.shade900 : Colors.black87,
                                  ),
                                ),
                                Text(
                                  rank == 1 ? 'Pilihan Utama (Prioritas 1)' : 'Pilihan Cadangan (Prioritas $rank)',
                                  style: GoogleFonts.dmSans(
                                    fontSize: 12,
                                    color: rank == 1 ? Colors.blue.shade600 : Colors.grey.shade500,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: const Icon(LucideIcons.x, size: 18),
                            color: Colors.grey.shade400,
                            onPressed: () {
                              setState(() {
                                _minatTerpilih.removeAt(index);
                              });
                            },
                          ),
                        ],
                      ),
                    );
                  },
                ),
            ],
          ),
        ),
      ),
    );
  }
}
