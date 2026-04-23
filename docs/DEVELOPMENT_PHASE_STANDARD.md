# Development Phase Standard (Perpustakaan)

Dokumen ini jadi patokan implementasi ke depan.

## Gold Standard Workflow
1. **Controller-first**
   - Definisikan flow endpoint, validasi request, authz/permission, dan response.
2. **Model/Data follow-up**
   - Pastikan query/relasi, scope data role, dan integritas data sudah benar.
3. **View/UX**
   - Terapkan UI konsisten (light/dark, i18n, empty/loading/error state).
4. **JS enhancement**
   - Tambahkan interaksi async (fetch/AJAX) untuk UX, tanpa memindahkan business logic dari backend.
5. **Verification**
   - Lint/check + uji smoke untuk route/fitur yang disentuh.

## Active Stabilization Phases
1. Logging hardening (throttle + quality)
2. Role visibility hardening (admin vs super admin)
3. Theme consistency (light/dark table+form+layout)
4. Localization consistency (id/en)
5. Real notification system (in-app, role scoped)
6. Profile enhancement (foto + kontak)
7. App settings enhancement (full card, footer, logo+background, opacity)
8. Table UX standardization (dropdown filter + JS search)

