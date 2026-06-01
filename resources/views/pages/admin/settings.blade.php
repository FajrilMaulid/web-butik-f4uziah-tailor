@extends('layouts.admin')

@section('page-title', 'Pengaturan Butik')

@section('content')
    <div style="max-width: 1100px; margin: 0 auto; width: 100%; min-height: calc(100vh - 130px); display: flex; flex-direction: column; justify-content: center; box-sizing: border-box; padding: 15px 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <div style="margin-bottom: 25px; text-align: center;">
            <h2 style="font-family: 'Lora', serif; color: var(--cokelat-gelap); margin: 0; font-size: 24px; font-weight: bold;">Konfigurasi Butik & Tampilan</h2>
            <p style="color: #666; font-size: 14px; margin: 5px 0 0 0;">
                Atur nomor kontak, teks promosi, dan media gambar halaman depan secara real-time.
            </p>
        </div>

        <!-- Single Cohesive Panel Card -->
        <div class="form-container" style="background-color: var(--putih); padding: 35px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-top: 4px solid var(--cokelat-utama); width: 100%; max-width: 1000px; margin: 0 auto; box-sizing: border-box;">
            
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" style="margin: 0;">
                @csrf
                
                <!-- Unified Flex Container for 3 Columns -->
                <div style="display: flex; flex-wrap: wrap; gap: 20px; width: 100%;">
                    
                    <!-- Kolom 1: Kontak & Teks Hero (Flex: 1fr) -->
                    <div style="flex: 1 1 240px; display: flex; flex-direction: column;">
                        <h3 style="font-family: 'Lora', serif; font-size: 16px; color: var(--cokelat-gelap); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #eae0d5; padding-bottom: 10px; font-weight: bold;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16" style="color: var(--cokelat-utama);">
                                <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg> Kontak & Teks Hero
                        </h3>

                        <!-- WhatsApp Input -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="admin_whatsapp" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Nomor WhatsApp Admin</label>
                            <input type="text" name="admin_whatsapp" id="admin_whatsapp" class="form-control" value="{{ old('admin_whatsapp', $adminWhatsapp) }}" required placeholder="Contoh: 6289601767100" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                            <small style="color: #888; display: block; margin-top: 4px; line-height: 1.3; font-size: 11px;">
                                *Akan otomatis dikonversi ke kode internasional <strong>62</strong>.
                            </small>
                        </div>

                        <!-- Hero Title -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="hero_title" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Judul Banner Hero</label>
                            <input type="text" name="hero_title" id="hero_title" class="form-control" value="{{ old('hero_title', $heroTitle) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                            <small style="color: #888; display: block; margin-top: 4px; line-height: 1.3; font-size: 11px;">
                                *Mendukung tag <code>&lt;br&gt;</code> untuk pindah baris (contoh: <code>Temukan Pesona&lt;br&gt;Elegan Anda</code>).
                            </small>
                        </div>

                        <!-- Hero Subtitle -->
                        <div class="form-group" style="margin-bottom: 0; flex-grow: 1; display: flex; flex-direction: column;">
                            <label for="hero_subtitle" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Deskripsi Banner Hero</label>
                            <textarea name="hero_subtitle" id="hero_subtitle" class="form-control" required style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; min-height: 80px; resize: vertical; box-sizing: border-box; flex-grow: 1;">{{ old('hero_subtitle', $heroSubtitle) }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom 2: Profil Tentang Butik (Flex: 1fr) -->
                    <div style="flex: 1 1 240px; display: flex; flex-direction: column;">
                        <h3 style="font-family: 'Lora', serif; font-size: 16px; color: var(--cokelat-gelap); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #eae0d5; padding-bottom: 10px; font-weight: bold;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16" style="color: var(--cokelat-utama);">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg> Profil Tentang Butik
                        </h3>

                        <!-- About Title -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="about_title" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Judul Profil Butik</label>
                            <input type="text" name="about_title" id="about_title" class="form-control" value="{{ old('about_title', $aboutTitle) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                        </div>

                        <!-- About Subtitle -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="about_subtitle" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Sub-Judul Profil Butik</label>
                            <input type="text" name="about_subtitle" id="about_subtitle" class="form-control" value="{{ old('about_subtitle', $aboutSubtitle) }}" required style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                        </div>

                        <!-- About Description -->
                        <div class="form-group" style="margin-bottom: 0; flex-grow: 1; display: flex; flex-direction: column;">
                            <label for="about_description" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Deskripsi Profil Butik</label>
                            <textarea name="about_description" id="about_description" class="form-control" required style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; min-height: 120px; resize: vertical; box-sizing: border-box; flex-grow: 1;">{{ old('about_description', $aboutDescription) }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom 3: Media & Gambar (Flex: 1fr) -->
                    <div style="flex: 1 1 240px; display: flex; flex-direction: column;">
                        <h3 style="font-family: 'Lora', serif; font-size: 16px; color: var(--cokelat-gelap); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #eae0d5; padding-bottom: 10px; font-weight: bold;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16" style="color: var(--cokelat-utama);">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                            </svg> Kustomisasi Media
                        </h3>

                        <!-- Hero Image -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="hero_image" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Gambar Banner Hero</label>
                            <input type="file" name="hero_image" id="hero_image" class="form-control" accept="image/*" style="width: 100%; padding: 6px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 12px; box-sizing: border-box;">
                            @if($heroImage)
                                <div style="margin-top: 10px; text-align: center;">
                                    <img src="{{ asset('storage/' . $heroImage) }}" alt="Hero Current" style="max-width: 100%; width: 280px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #eae0d5; display: inline-block;">
                                </div>
                            @else
                                <div style="margin-top: 10px; background-color: #f9f9f9; padding: 10px; border-radius: 6px; font-size: 11px; color: #888; border: 1px dashed #ddd; text-align: center;">
                                    Gambar Bawaan (Unsplash)
                                </div>
                            @endif
                        </div>

                        <!-- About Image -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="about_image" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Gambar Tentang Kami</label>
                            <input type="file" name="about_image" id="about_image" class="form-control" accept="image/*" style="width: 100%; padding: 6px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 12px; box-sizing: border-box;">
                            @if($aboutImage)
                                <div style="margin-top: 10px; text-align: center;">
                                    <img src="{{ asset('storage/' . $aboutImage) }}" alt="About Current" style="max-width: 100%; width: 280px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #eae0d5; display: inline-block;">
                                </div>
                            @else
                                <div style="margin-top: 10px; background-color: #f9f9f9; padding: 10px; border-radius: 6px; font-size: 11px; color: #888; border: 1px dashed #ddd; text-align: center;">
                                    Gambar Bawaan (Unsplash)
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom 4: Info Kontak Butik -->
                    <div style="flex: 1 1 240px; display: flex; flex-direction: column;">
                        <h3 style="font-family: 'Lora', serif; font-size: 16px; color: var(--cokelat-gelap); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #eae0d5; padding-bottom: 10px; font-weight: bold;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="color: var(--cokelat-utama);">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg> Info Kontak Butik
                        </h3>

                        <!-- Alamat -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="contact_address" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Alamat Butik</label>
                            <textarea name="contact_address" id="contact_address" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; min-height: 60px; resize: vertical; box-sizing: border-box;">{{ old('contact_address', $contactAddress) }}</textarea>
                        </div>

                        <!-- Jam Operasi -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="contact_hours" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Jam Operasi</label>
                            <input type="text" name="contact_hours" id="contact_hours" class="form-control" value="{{ old('contact_hours', $contactHours) }}" placeholder="Contoh: Senin - Minggu | 10:00 - 20:00 WIB" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                        </div>

                        <!-- Email -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="contact_email" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Email Butik</label>
                            <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email', $contactEmail) }}" placeholder="Contoh: butik@gmail.com" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                        </div>

                        <!-- Maps Embed URL -->
                        <div class="form-group" style="margin-bottom: 0; flex-grow: 1; display: flex; flex-direction: column;">
                            <label for="contact_maps_url" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">URL Embed Google Maps</label>
                            <textarea name="contact_maps_url" id="contact_maps_url" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; min-height: 70px; resize: vertical; box-sizing: border-box; flex-grow: 1;">{{ old('contact_maps_url', $contactMapsUrl) }}</textarea>
                            <small style="color: #888; display: block; margin-top: 4px; line-height: 1.3; font-size: 11px;">
                                *Buka Google Maps → Bagikan → Sematkan peta → Salin URL dari <code>src="..."</code> pada iframe.
                            </small>
                        </div>
                    </div>

                    <!-- Kolom 5: Kustomisasi Footer -->
                    <div style="flex: 1 1 240px; display: flex; flex-direction: column;">
                        <h3 style="font-family: 'Lora', serif; font-size: 16px; color: var(--cokelat-gelap); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #eae0d5; padding-bottom: 10px; font-weight: bold;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="color: var(--cokelat-utama);">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            </svg> Kustomisasi Footer
                        </h3>

                        <!-- Deskripsi Footer -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="footer_description" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Deskripsi Footer</label>
                            <textarea name="footer_description" id="footer_description" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; min-height: 60px; resize: vertical; box-sizing: border-box;">{{ old('footer_description', $footerDescription) }}</textarea>
                        </div>

                        <!-- Instagram Username -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="contact_instagram" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Instagram Username</label>
                            <input type="text" name="contact_instagram" id="contact_instagram" class="form-control" value="{{ old('contact_instagram', $contactInstagram) }}" placeholder="Contoh: @f4uziah_tailor" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                        </div>

                        <!-- Telepon Footer -->
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="contact_phone" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Nomor Telepon Footer</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $contactPhone) }}" placeholder="Contoh: +62 8234567891" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px;">
                        </div>

                        <!-- Copyright Text -->
                        <div class="form-group" style="margin-bottom: 0; flex-grow: 1; display: flex; flex-direction: column;">
                            <label for="footer_copyright" style="display: block; margin-bottom: 6px; font-weight: bold; color: var(--teks-gelap); font-size: 13px;">Teks Hak Cipta (Copyright)</label>
                            <input type="text" name="footer_copyright" id="footer_copyright" class="form-control" value="{{ old('footer_copyright', $footerCopyright) }}" placeholder="Contoh: Copyright © F4uziah Tailor 2026" style="width: 100%; padding: 10px 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; box-sizing: border-box;">
                        </div>
                    </div>

                </div>

                <!-- Cohesive Bottom Section inside the Card -->
                <hr style="border: 0; border-top: 1px solid #eae0d5; margin: 30px 0 20px 0; width: 100%;">

                <div style="width: 100%; display: flex; justify-content: flex-end; margin: 0;">
                    <button type="submit" class="btn-submit" style="padding: 12px 45px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; transition: 0.3s; box-shadow: 0 4px 12px rgba(156, 122, 99, 0.15);">Simpan Seluruh Perubahan</button>
                </div>
            </form>

        </div>
    </div>
@endsection
