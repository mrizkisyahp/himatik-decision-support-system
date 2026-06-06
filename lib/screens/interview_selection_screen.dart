import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';
import '../theme/app_colors.dart';
import '../theme/app_state.dart';

class InterviewSelectionScreen extends StatefulWidget {
  const InterviewSelectionScreen({super.key});

  @override
  State<InterviewSelectionScreen> createState() => _InterviewSelectionScreenState();
}

class _InterviewSelectionScreenState extends State<InterviewSelectionScreen> {
  // Current active date view (0 for Friday, 1 for Saturday)
  int _activeDateIndex = 0;
  final List<String> _dates = ['12 Juni 2026', '13 Juni 2026'];
  final List<String> _days = ['Jum\'at', 'Sabtu'];

  // Temporary selected slot ID during screen visit
  String? _tempSelectedSlotId;

  @override
  void initState() {
    super.initState();
    _tempSelectedSlotId = AppState.instance.selectedInterviewSlot;
  }

  void _saveSelection() {
    if (_tempSelectedSlotId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'Silakan pilih salah satu sesi terlebih dahulu.',
            style: GoogleFonts.dmSans(color: Colors.white, fontSize: 12),
          ),
          backgroundColor: AppColors.red,
        ),
      );
      return;
    }
    
    setState(() {
      AppState.instance.selectedInterviewSlot = _tempSelectedSlotId;
    });

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          'Jadwal wawancara berhasil disimpan!',
          style: GoogleFonts.dmSans(color: Colors.white, fontSize: 12),
        ),
        backgroundColor: Colors.green,
      ),
    );

    // Return to dashboard
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    // Filter slots for current active date
    final String targetDay = _days[_activeDateIndex];
    final activeSlots = AppState.instance.interviewSlots
        .where((slot) => slot['day'] == targetDay)
        .toList();

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(LucideIcons.menu, color: AppColors.primary1),
          onPressed: () {
            // Draw drawer menu
          },
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
              // Title (Max size 32)
              Text(
                'Pilih Jadwal Wawancara',
                style: GoogleFonts.dmSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary1,
                  height: 1.2,
                ),
              ),
              const SizedBox(height: 6), // Label to sublabel gap

              // Subtitle
              Text(
                'Pilih salah satu sesi wawancara yang tersedia untuk Anda mendaftar.',
                style: GoogleFonts.dmSans(
                  fontSize: 12,
                  color: AppColors.tertiary4,
                ),
              ),
              const SizedBox(height: 18), // Normal gap

              // Date Switcher Row
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  IconButton(
                    icon: const Icon(LucideIcons.chevronLeft, size: 20),
                    onPressed: _activeDateIndex > 0
                        ? () {
                            setState(() {
                              _activeDateIndex--;
                            });
                          }
                        : null,
                  ),
                  const SizedBox(width: 12),
                  Text(
                    '${_days[_activeDateIndex]}, ${_dates[_activeDateIndex]}',
                    style: GoogleFonts.dmSans(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: AppColors.primary2,
                    ),
                  ),
                  const SizedBox(width: 12),
                  IconButton(
                    icon: const Icon(LucideIcons.chevronRight, size: 20),
                    onPressed: _activeDateIndex < _dates.length - 1
                        ? () {
                            setState(() {
                              _activeDateIndex++;
                            });
                          }
                        : null,
                  ),
                ],
              ),
              const SizedBox(height: 12), // Intermediate gap

              // Session List
              Expanded(
                child: ListView.builder(
                  itemCount: activeSlots.length,
                  itemBuilder: (context, index) {
                    final slot = activeSlots[index];
                    final String slotId = slot['id'];
                    final String timeText = slot['time'];
                    final String state = slotId == _tempSelectedSlotId ? 'selected' : slot['state'];

                    // Define Styling based on session state
                    Color itemBgColor;
                    Color itemBorderColor;
                    Color textColor;
                    VoidCallback? onTap;

                    if (state == 'selected') {
                      itemBgColor = AppColors.secondary7.withOpacity(0.15);
                      itemBorderColor = AppColors.secondary7;
                      textColor = AppColors.secondary4;
                      onTap = () {
                        setState(() {
                          _tempSelectedSlotId = null;
                        });
                      };
                    } else if (state == 'occupied') {
                      itemBgColor = AppColors.lightRed.withOpacity(0.15);
                      itemBorderColor = AppColors.lightRed;
                      textColor = AppColors.red;
                      onTap = () {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text(
                              'Sesi ini sudah penuh.',
                              style: GoogleFonts.dmSans(fontSize: 12),
                            ),
                            backgroundColor: AppColors.red,
                          ),
                        );
                      };
                    } else if (state == 'unavail') {
                      itemBgColor = AppColors.tertiary8.withOpacity(0.1);
                      itemBorderColor = AppColors.tertiary8;
                      textColor = AppColors.tertiary4;
                      onTap = () {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text(
                              'Sesi ini tidak tersedia.',
                              style: GoogleFonts.dmSans(fontSize: 12),
                            ),
                            backgroundColor: AppColors.tertiary4,
                          ),
                        );
                      };
                    } else {
                      // Available
                      itemBgColor = Colors.white;
                      itemBorderColor = AppColors.tertiary8;
                      textColor = AppColors.primary1;
                      onTap = () {
                        setState(() {
                          _tempSelectedSlotId = slotId;
                        });
                      };
                    }

                    return Container(
                      margin: const EdgeInsets.only(bottom: 12),
                      decoration: BoxDecoration(
                        color: itemBgColor,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: itemBorderColor, width: 1.5),
                      ),
                      child: InkWell(
                        onTap: onTap,
                        borderRadius: BorderRadius.circular(12),
                        child: Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 14.0),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  Icon(
                                    state == 'selected'
                                        ? LucideIcons.calendarCheck2
                                        : state == 'occupied'
                                            ? LucideIcons.calendarX
                                            : state == 'unavail'
                                                ? LucideIcons.calendarOff
                                                : LucideIcons.calendar,
                                    color: textColor,
                                    size: 18,
                                  ),
                                  const SizedBox(width: 12),
                                  Text(
                                    timeText.split(' ')[0] + ' ' + timeText.split(' ')[1], // e.g. "Sesi 1"
                                    style: GoogleFonts.dmSans(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                      color: textColor,
                                    ),
                                  ),
                                ],
                              ),
                              Text(
                                timeText.substring(timeText.indexOf('(')), // e.g. "(08:00 s.d. 09:30)"
                                style: GoogleFonts.dmSans(
                                  fontSize: 12,
                                  fontWeight: FontWeight.w500,
                                  color: textColor,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                ),
              ),
              const SizedBox(height: 18), // Normal gap

              // Submit Button
              ElevatedButton(
                onPressed: _saveSelection,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  elevation: 0,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      'Pilih Sesi',
                      style: GoogleFonts.dmSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(width: 8),
                    const Icon(LucideIcons.check, size: 18),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
