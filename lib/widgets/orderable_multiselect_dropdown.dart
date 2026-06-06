import 'package:flutter/material.dart';
import 'package:lucide_icons_flutter/lucide_icons.dart';

class OrderableMultiSelectDropdown<T> extends StatefulWidget {
  final String hint;
  final List<T> items;
  final List<T> selectedItems;
  final String Function(T) itemLabelBuilder;
  final ValueChanged<List<T>> onChanged;
  final int? maxSelections; // Membatasi jumlah maksimal pilihan

  const OrderableMultiSelectDropdown({
    super.key,
    required this.hint,
    required this.items,
    required this.selectedItems,
    required this.itemLabelBuilder,
    required this.onChanged,
    this.maxSelections,
  });

  @override
  State<OrderableMultiSelectDropdown<T>> createState() => _OrderableMultiSelectDropdownState<T>();
}

class _OrderableMultiSelectDropdownState<T> extends State<OrderableMultiSelectDropdown<T>> {
  final LayerLink _layerLink = LayerLink();
  OverlayEntry? _overlayEntry;
  bool _isOpen = false;

  void _toggleDropdown() {
    if (_isOpen) {
      _closeDropdown();
    } else {
      _openDropdown();
    }
  }

  void _openDropdown() {
    _overlayEntry = _createOverlayEntry();
    Overlay.of(context).insert(_overlayEntry!);
    setState(() {
      _isOpen = true;
    });
  }

  void _closeDropdown() {
    _overlayEntry?.remove();
    _overlayEntry = null;
    setState(() {
      _isOpen = false;
    });
  }

  // Dipanggil otomatis saat widget diperbarui oleh parent (setState di parent)
  @override
  void didUpdateWidget(covariant OrderableMultiSelectDropdown<T> oldWidget) {
    super.didUpdateWidget(oldWidget);
    // Gunakan addPostFrameCallback agar pembaruan overlay dilakukan SETELAH frame selesai digambar
    // Ini memperbaiki exception "setState() or markNeedsBuild() called during build"
    if (_isOpen) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        _overlayEntry?.markNeedsBuild();
      });
    }
  }

  OverlayEntry _createOverlayEntry() {
    RenderBox renderBox = context.findRenderObject() as RenderBox;
    Size size = renderBox.size;

    return OverlayEntry(
      builder: (context) => Stack(
        children: [
          // Background transparan untuk menutup dropdown saat klik di luar area
          GestureDetector(
            onTap: _closeDropdown,
            behavior: HitTestBehavior.translucent,
            child: Container(),
          ),
          Positioned(
            width: size.width,
            child: CompositedTransformFollower(
              link: _layerLink,
              showWhenUnlinked: false,
              offset: Offset(0, size.height + 8),
              child: Material(
                elevation: 8,
                borderRadius: BorderRadius.circular(16),
                color: Colors.white,
                shadowColor: Colors.black.withAlpha(30),
                child: Container(
                  constraints: const BoxConstraints(maxHeight: 250),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.grey.shade200),
                  ),
                  child: ListView.builder(
                    padding: EdgeInsets.zero,
                    shrinkWrap: true,
                    itemCount: widget.items.length,
                    itemBuilder: (context, index) {
                      final item = widget.items[index];
                      final isSelected = widget.selectedItems.contains(item);
                      final selectionIndex = widget.selectedItems.indexOf(item) + 1;
                      
                      // Cek apakah kuota maksimal pilihan sudah terpenuhi
                      final isMaxReached = widget.maxSelections != null && 
                          widget.selectedItems.length >= widget.maxSelections!;
                      
                      // Pilihan tidak aktif jika sudah mencapai batas dan item ini belum terpilih
                      final isDisabled = !isSelected && isMaxReached;

                      return InkWell(
                        onTap: isDisabled ? null : () {
                          List<T> newSelection = List.from(widget.selectedItems);
                          if (isSelected) {
                            newSelection.remove(item);
                          } else {
                            if (!newSelection.contains(item)) {
                              newSelection.add(item);
                            }
                          }
                          widget.onChanged(newSelection);
                          // markNeedsBuild tidak dipanggil di sini secara langsung karena
                          // didUpdateWidget dengan addPostFrameCallback sudah mengurusnya dengan aman!
                        },
                        child: Opacity(
                          opacity: isDisabled ? 0.4 : 1.0,
                          child: Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                            child: Row(
                              children: [
                                Expanded(
                                  child: Text(
                                    widget.itemLabelBuilder(item),
                                    style: TextStyle(
                                      fontWeight: isSelected ? FontWeight.w600 : FontWeight.normal,
                                      color: isSelected ? Colors.blue.shade700 : Colors.black87,
                                    ),
                                  ),
                                ),
                                if (isSelected)
                                  Container(
                                    width: 24,
                                    height: 24,
                                    decoration: BoxDecoration(
                                      color: Colors.blue.shade600,
                                      shape: BoxShape.circle,
                                    ),
                                    alignment: Alignment.center,
                                    child: Text(
                                      '$selectionIndex',
                                      style: const TextStyle(
                                        color: Colors.white,
                                        fontSize: 12,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  )
                                else
                                  Container(
                                    width: 24,
                                    height: 24,
                                    decoration: BoxDecoration(
                                      border: Border.all(
                                        color: isDisabled ? Colors.grey.shade200 : Colors.grey.shade300, 
                                        width: 2,
                                      ),
                                      shape: BoxShape.circle,
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
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    _closeDropdown();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return CompositedTransformTarget(
      link: _layerLink,
      child: GestureDetector(
        onTap: _toggleDropdown,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12),
            border: Border.all(
              color: _isOpen ? Colors.blue.shade600 : Colors.grey.shade300,
              width: 1.5,
            ),
            color: Colors.white,
          ),
          child: Row(
            children: [
              Expanded(
                child: widget.selectedItems.isEmpty
                    ? Padding(
                        padding: const EdgeInsets.symmetric(vertical: 4),
                        child: Text(
                          widget.hint,
                          style: TextStyle(color: Colors.grey.shade500),
                        ),
                      )
                    : SizedBox(
                        height: 38,
                        child: ListView.builder(
                          scrollDirection: Axis.horizontal,
                          itemCount: widget.selectedItems.length,
                          itemBuilder: (context, index) {
                            final item = widget.selectedItems[index];
                            final rank = index + 1;
                            return Padding(
                              padding: const EdgeInsets.only(right: 8.0),
                              child: GestureDetector(
                                onTap: () {}, // Mencegah dropdown terbuka/tertutup saat chip diklik
                                child: Chip(
                                  backgroundColor: Colors.blue.shade50,
                                  side: BorderSide(color: Colors.blue.shade100),
                                  labelPadding: const EdgeInsets.only(right: 4),
                                  avatar: CircleAvatar(
                                    backgroundColor: Colors.blue.shade600,
                                    child: Text(
                                      '$rank',
                                      style: const TextStyle(
                                        color: Colors.white,
                                        fontSize: 10,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                  label: Text(
                                    widget.itemLabelBuilder(item),
                                    style: TextStyle(
                                      fontSize: 12, 
                                      fontWeight: FontWeight.w500,
                                      color: Colors.blue.shade900,
                                    ),
                                  ),
                                  onDeleted: () {
                                    List<T> newSelection = List.from(widget.selectedItems);
                                    newSelection.remove(item);
                                    widget.onChanged(newSelection);
                                  },
                                  deleteIconColor: Colors.blue.shade700,
                                ),
                              ),
                            );
                          },
                        ),
                      ),
              ),
              Icon(
                _isOpen ? LucideIcons.chevronUp : LucideIcons.chevronDown,
                color: Colors.grey.shade600,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
