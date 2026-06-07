# HIMATIK DSS Mobile — Design System

> Design system untuk aplikasi mobile HIMATIK Decision Support System.
> Dokumen ini menjadi acuan utama seluruh pengembangan UI/UX aplikasi Flutter.

---

## 1. Filosofi Desain

| Aspek | Prinsip |
|-------|---------|
| **Visual** | Clean, minimal, profesional — mobile-first |
| **Warna** | Navy blue dominan, lavender background, white cards |
| **Interaksi** | Smooth transitions, micro-animations, responsive feedback |
| **Navigasi** | Named routes, `← Kembali` pattern, hamburger menu pada dashboard |
| **Empty State** | `"..."` dots + `"Belum Ada"` text (gray) |

---

## 2. Typography

### Font Family
- **Primary Font**: `DM Sans` (via `google_fonts` package)
- **Fallback**: System sans-serif

### Type Scale

| Style | Size | Weight | Usage |
|-------|------|--------|-------|
| `displayLarge` | 28sp | Bold (700) | Judul carousel, hero text |
| `headlineLarge` | 24sp | Bold (700) | Judul halaman (Masuk Akun, Daftar Akun) |
| `headlineMedium` | 20sp | SemiBold (600) | Section headers (Jadwal Wawancara) |
| `titleLarge` | 18sp | SemiBold (600) | Card titles, nama kandidat |
| `titleMedium` | 16sp | Medium (500) | Sub-section headers |
| `bodyLarge` | 16sp | Regular (400) | Body text, form descriptions |
| `bodyMedium` | 14sp | Regular (400) | Labels, detail text |
| `bodySmall` | 12sp | Regular (400) | Captions, placeholders, hints |
| `labelLarge` | 14sp | SemiBold (600) | Button text |
| `labelMedium` | 12sp | Medium (500) | Badge text, small labels |

---

## 3. Color Palette

### Primary Palette (Navy Blue)
```
primary     #223872  ← Brand utama, button fill, heading text
primary1    #00174B  ← Darkest, icon tint
primary2    #152D67  ← Dark variant
primary3    #2F447E  ← Hover state
primary4    #475C98  ← Active state
primary5    #6075B3  ← Medium tone
primary6    #7A8FCE  ← Light accent
primary7    #95A9EB  ← Carousel indicators (inactive)
primary8    #B4C5FF  ← Divider, borders
primary9    #DBE1FF  ← Light container
primary10   #EEF0FF  ← Lightest tint
```

### Secondary Palette (Ocean Blue)
```
secondary   #4A90E2  ← Links, interactive elements
secondary1  #001C39  ← Darkest
secondary2  #00315D
secondary3  #004883
secondary4  #0060AC
secondary5  #2D79C9
secondary6  #4D93E5
secondary7  #6EAEFF  ← Active highlights
secondary8  #A4C9FF  ← Light indicator
secondary9  #D4E3FF  ← Light fill
secondary10 #F4F7FF  ← Near-white tint
```

### Tertiary Palette (Neutral Blue-Gray)
```
tertiary    #F4F7FF  ← Scaffold background utama
tertiary1   #181C21  ← Darkest neutral
tertiary2   #2C3137
tertiary3   #43474D
tertiary4   #5B5F65
tertiary5   #73777E  ← Placeholder text
tertiary6   #8D9198  ← Input border (enabled)
tertiary7   #A8ABB3  ← Disabled state
tertiary8   #C3C6CE
tertiary9   #DFE2EA  ← Subtle divider
tertiary10  #EEF1F9  ← Card background variant
```

### Neutral Palette (Gray)
```
neutral     #333333  ← Body text
neutral1    #1B1C1C  ← Darkest
neutral2    #303030
neutral3    #474747
neutral4    #5F5E5E
neutral5    #787777
neutral6    #929090
neutral7    #ACABAA
neutral8    #E4E2E1  ← Light border
neutral9    #C8C6C6
neutral10   #F3F0F0  ← Lightest
```

### Semantic Colors
```
red         #DC2626  ← Error, destructive (Keluar/logout)
lightRed    #FEE2E2  ← Error background
yellow      #F6C157  ← Warning, rank #1 badge
lightYellow #E9D0A0  ← Warning background
white       #FFFFFF  ← Card background, button text
black       #000000  ← Rarely used
```

---

## 4. Iconography

### Icon Library
- **Package**: `lucide_icons_flutter` v3.1.14+2
- **Style**: Outline/line icons, 24×24 default size
- **Stroke**: 1.5px stroke width (Lucide default)

### Common Icon Mapping

| Context | Icon | Lucide Name |
|---------|------|-------------|
| Back navigation | ← | `LucideIcons.arrowLeft` |
| Next/Forward | → | `LucideIcons.arrowRight` |
| Menu | ☰ | `LucideIcons.menu` |
| Email | ✉ | `LucideIcons.mail` |
| Password | 🔒 | `LucideIcons.lock` |
| Show password | 👁 | `LucideIcons.eye` |
| Hide password | 👁‍ | `LucideIcons.eyeOff` |
| Calendar | 📅 | `LucideIcons.calendar` |
| Clock | 🕐 | `LucideIcons.clock` |
| Location | 📍 | `LucideIcons.mapPin` |
| Checkmark | ✓ | `LucideIcons.check` |
| Logout | ↗ | `LucideIcons.logOut` |
| Clipboard | 📋 | `LucideIcons.clipboardList` |
| Attachment | 📎 | `LucideIcons.paperclip` |
| Skip | ⏭ | `LucideIcons.skipForward` |
| User | 👤 | `LucideIcons.user` |
| Phone | 📱 | `LucideIcons.phone` |
| Home | 🏠 | `LucideIcons.home` |
| Search | 🔍 | `LucideIcons.search` |

---

## 5. Component Specs

### 5.1 Buttons

#### Primary Button (CTA)
```
Background:   AppColors.primary (#223872)
Text:         AppColors.white, DM Sans SemiBold 14sp
Height:       52px
Border:       Rounded 12px
Width:        Full-width (match parent)
Padding:      Horizontal 24px, Vertical 14px
Icon:         Optional trailing icon (→ atau ✓), white, 18px
Shadow:       elevation 0 (flat design)
```

#### Text Button (Navigasi)
```
Background:   Transparent
Text:         AppColors.primary atau AppColors.secondary, DM Sans Medium 14sp
Padding:      Horizontal 8px, Vertical 4px
```

#### Outlined Button
```
Background:   Transparent
Border:       1.5px AppColors.primary, rounded 12px
Text:         AppColors.primary, DM Sans SemiBold 14sp
Height:       48px
```

### 5.2 Input Fields

```
Background:   AppColors.white
Border:       1px AppColors.tertiary6, rounded 12px
Focused:      1.5px AppColors.primary, rounded 12px
Error:        1.5px AppColors.red, rounded 12px
Height:       ~52px (content padding)
Padding:      Horizontal 16px, Vertical 14px
Label:        DM Sans Medium 14sp, AppColors.neutral (di atas field)
Placeholder:  DM Sans Regular 14sp, AppColors.tertiary5
Suffix Icon:  24px, AppColors.tertiary5 (e.g. eye toggle)
```

### 5.3 Cards

```
Background:   AppColors.white
Border:       None (shadow-based) atau 1px AppColors.primary8
Corner:       Rounded 16px
Shadow:       BoxShadow(color: black.withOpacity(0.05), blurRadius: 10, offset: (0,4))
Padding:      16px–20px semua sisi
Margin:       Horizontal 24px
```

### 5.4 Navigation Bar (Top)

```
Background:   AppColors.tertiary (#F4F7FF)
Height:       56px
Bottom Border: 1px AppColors.primary8
Leading:      Hamburger menu icon (LucideIcons.menu) atau Back arrow
Trailing:     HIMATIK logo (assets/img/logo.png)
Elevation:    0
```

### 5.5 Page Indicator (Carousel)

```
Active:       White, rounded rectangle 24×6px, opacity 1.0
Inactive:     White, rounded rectangle 16×6px, opacity 0.4
Spacing:      8px between dots
Position:     Bottom center, 80px dari bawah
Animation:    AnimatedContainer 300ms ease-in-out
```

### 5.6 Divider

```
Color:        AppColors.primary8 (#B4C5FF)
Thickness:    1px
Margin:       Horizontal 0px (full-width)
```

---

## 6. Spacing System

### Spacing Scale (Based on AppSizing)

| Token | Value | Usage |
|-------|-------|-------|
| `xxs4` | 4px | Micro gaps, icon padding |
| `xs6` | 6px | Tight spacing |
| `s8` | 8px | Between related elements |
| `sm12` | 12px | Label-to-field gap |
| `ml8` | 18px | Between form fields |
| `mx24` | 24px | Section padding, horizontal margins |
| `xl32` | 32px | Between sections |
| `vxl48` | 48px | Large section gaps |
| `xxl64` | 64px | Top/bottom page padding |
| `vxxl72` | 72px | Hero section spacing |

### Page Layout

```
Horizontal Padding:  24px (AppSizing.mx24)
Top Safe Area:       MediaQuery.of(context).padding.top + 16px
Bottom Safe Area:    MediaQuery.of(context).padding.bottom + 24px
Section Gap:         32px (AppSizing.xl32)
Form Field Gap:      18px (AppSizing.ml8)
Label-Field Gap:     8px (AppSizing.s8)
```

---

## 7. Screen Specifications

### 7.1 Carousel (Onboarding) — 3 Slides

| Slide | Judul | Konten |
|-------|-------|--------|
| 1 | Selamat Datang di Aplikasi HIMATIK PNJ | Logo + welcome message + subtitle |
| 2 | Tentang Kami | Deskripsi HIMATIK PNJ sebagai organisasi mahasiswa |
| 3 | Visi & Misi | Visi + 4 poin misi |

**Layout:**
- Full-bleed blurred background image (`assets/img/bg_blur.png`)
- Dark navy overlay (`AppColors.primary.withOpacity(0.7)`)
- White text throughout
- Bottom: `"Lewat >|"` (skip) kiri, `"Lanjut →"` kanan
- Slide terakhir: `"Ke Portal Login →"` menggantikan `"Lanjut"`
- 3 page indicator bars di tengah bawah
- PageView dengan `PageController` + smooth scroll animation

### 7.2 Portal Login

**Layout:**
- Logo HIMATIK (centered, 80×80)
- Heading: `"Masuk Akun HIMATIK PNJ"`
- Form: Email input + Password input (dengan eye toggle)
- CTA: `"Masuk ke Aplikasi"` (full-width primary button)
- Footer: `"Ingin mendaftar menjadi anggota?"` + `"Daftar Sekarang"` link
- **API**: `POST /api/login` → token + user + next_step

### 7.3 Daftar Akun (Register)

**Layout:**
- AppBar: `"← Kembali"` back navigation
- Divider
- Heading: `"Daftar Akun HIMATIK PNJ"`
- Description: hint text
- Form: Email + Password (eye toggle) + Konfirmasi Password (eye toggle)
- CTA: `"Berikutnya →"` (bottom-pinned)
- **API**: `POST /api/register` → token + user + OTP sent

### 7.4 Verifikasi Email (OTP)

**Layout:**
- AppBar: `"← Kembali"`
- Heading: `"Verifikasi Email"`
- Instruction text
- OTP input field
- `"Tidak terkirim? Kirim lagi"` resend link
- CTA: `"Verifikasi ✓"` (bottom-pinned)
- **API**: `POST /api/email/verify-otp` → verified
- **Resend API**: `POST /api/email/resend-otp`

---

## 8. Animation & Transitions

| Elemen | Tipe | Duration | Curve |
|--------|------|----------|-------|
| Page transition | SlideTransition (right-to-left) | 300ms | `Curves.easeInOut` |
| Carousel slide | PageView physics | 400ms | `Curves.easeOut` |
| Page indicator | AnimatedContainer | 300ms | `Curves.easeInOut` |
| Button press | Scale down 0.97 | 100ms | `Curves.easeIn` |
| Input focus | Border color transition | 200ms | `Curves.linear` |
| Loading state | Circular progress indicator | ∞ | - |
| Snackbar | Slide up from bottom | 250ms | `Curves.easeOut` |
| Fade in content | FadeTransition | 400ms | `Curves.easeIn` |

---

## 9. Assets

```
assets/
└── img/
    ├── bg_blur.png     ← Background blur untuk carousel (group photo)
    └── logo.png        ← Logo HIMATIK PNJ (hexagonal badge)
```

---

## 10. API Configuration

| Setting | Value |
|---------|-------|
| **Base URL** | `http://localhost:8000/api` (configurable) |
| **Auth** | Laravel Sanctum — Bearer Token |
| **Content-Type** | `application/json` (default), `multipart/form-data` (file upload) |
| **Accept** | `application/json` |
| **Token Storage** | `SharedPreferences` (key: `auth_token`) |

### Flow Autentikasi
```
Register → Token diterima → Verify OTP → Email verified → Isi Identitas → Dashboard
Login → Token diterima → Redirect berdasarkan next_step
```

---

## 11. Project Architecture

```
lib/
├── main.dart
├── config/
│   └── api_config.dart              ← Base URL, endpoints
├── models/
│   └── user_model.dart              ← User data model
├── services/
│   ├── api_service.dart             ← HTTP client wrapper
│   └── auth_service.dart            ← Login, register, OTP, token management
├── screens/
│   ├── carousel_screen.dart         ← Onboarding carousel
│   ├── portal_screen.dart           ← Login
│   ├── registration_account_screen.dart ← Register
│   ├── verification_screen.dart     ← OTP verification
│   └── ...                          ← Future screens
├── widgets/
│   ├── app_button.dart              ← Reusable button components
│   ├── app_input.dart               ← Reusable input fields
│   ├── app_loading.dart             ← Loading indicator overlay
│   └── page_indicator.dart          ← Carousel dots
└── theme/
    ├── app_colors.dart              ← Color constants
    ├── app_sizing.dart              ← Size constants
    └── app_spacing.dart             ← Spacing constants (TBD)
```
