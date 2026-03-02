# 3S Grup Personel, Profil ve Destek Portalı

Bu proje, şirket içi personellerin, yöneticilerin ve dış kullanıcıların bir araya geldiği; eğitimlerin, iş ilanlarının, ürünlerin ve destek biletlerinin (ticket) yönetildiği kapsamlı bir **Laravel 11** uygulamasıdır. İçerik ve sistem yönetimi, güçlü ve özelleştirilebilir **Filament PHP** admin paneli üzerinden sağlanmaktadır.

## 🌟 Temel Özellikler
- **Gelişmiş Kullanıcı Profilleri:** Kullanıcıların kendilerine ait yeteneklerini (skills), sosyal medya bağlantılarını, biyografilerini ve iletişim bilgilerini ekleyebilecekleri herkese açık veya gizli yapılabilen profil sayfaları.
- **Keşfet (Discover):** Sistemdeki diğer kullanıcıları yeteneklerine veya puanlarına göre filtreleyip inceleyebileceğiniz ve tek tıkla **Beğenebileceğiniz (Like)** liderlik tablosu ağı.
- **Eğitim Yönetimi (LMS):** Personel gelişimleri için oluşturulan, yalnızca yetkisi veya kaydı (enrollment) olan kullanıcıların görebileceği, dersler (lessons) ve videolar içeren eğitim modülü.
- **İş İlanları & İş Alarmları:** Sistem üzerinden başvuru URL'si verilebilen, zengin metin düzenleyiciyle yazılmış detaylı iş ilanları ve kullanıcıların belirli kelimelerle kendilerine iş uyarısı (Job Alert) kurabilmesi.
- **Ürünler (Products):** Ücretsiz olarak incelenebilecek veya link ile satın alıma yönlendirilebilecek ürün/hizmet sergileme modülü.
- **Destek Bilet Sistemi (Ticketing):** Departmanlara (Kategori), önceliklere veya sistem yöneticilerine göre atanabilen, yöneticilerle müşterilerin karşılıklı mesajlaşabileceği gelişmiş Ticket sistemi.
- **Filament Admin Paneli:** Tüm bu verilerin (Kullanıcılar, Biletler, İlanlar, Ürünler vb.) saniyeler içinde kolayca, yetkilendirmelere (Role-base) bağlı şekilde yönetilebildiği arka plan paneli.
- **Karanlık Mod (Dark Mode):** Uygulamanın tüm sayfaları, TailwindCSS ile entegre bir şekilde kusursuz karanlık mod deneyimi sunar.

## 🛠 Kullanılan Teknolojiler
- **Backend:** Laravel 11.x, PHP 8.2+
- **Frontend:** Blade, TailwindCSS, Alpine.js
- **Veritabanı:** SQLite (Varsayılan olarak - MySQL/PostgreSQL uyumlu)
- **Admin Paneli:** Filament v3

---

## 🚀 Kurulum ve Çalıştırma

Bu projeyi yerel bilgisayarınızda veya sunucunuzda çalıştırmak oldukça basittir. Projenin devasa boyutlara ulaşmaması için `vendor` ve `node_modules` klasörleri GitHub'a yüklenmemiştir; bu nedenle projeyi indirdikten sonra inşa edilmesi (build) gerekir.

Bunu yapmak için **iki farklı yöntem** bulunmaktadır:

### Yöntem 1: Tek Tıkla Kurulum (Windows İçin Önerilen)
Eğer Windows kullanıyorsanız ve hiçbir terminal koduyla uğraşmak istemiyorsanız:
1. Projeyi bilgisayarınıza indirin (ZIP veya Clone).
2. Proje ana klasörünün içindeki **`kurulum.bat`** dosyasına çift tıklayın.
3. Siyah ekran (CMD) açılacak ve gerekli olan tüm kütüphaneleri indirecek, ayar dosyanızı (`.env`) oluşturacak, veritabanını hazırlayacak ve resim linklerini otomatik yapacaktır. Sadece bitmesini bekleyin.

### Yöntem 2: Manuel Kurulum (Linux/Mac/Geliştiriciler)
Terminal üzerinden kendiniz kurmak isterseniz proje dizininde şu komutları sırasıyla çalıştırın:
```bash
# Gerekli PHP paketlerini yükleyin
composer install

# Gerekli Frontend (JS/CSS) paketlerini yükleyin
npm install

# .env (ayar) dosyasını oluşturun
cp .env.example .env

# Uygulamanın güvenlik anahtarını (App Key) oluşturun
php artisan key:generate

# Veritabanı tablolarını içeri aktarın
php artisan migrate --force

# Yüklenen fotoğrafların görünmesi için köprü oluşturun
php artisan storage:link
```

---

## 💻 Projeyi Yayına Alma (Başlatma)
Kurulum (otomatik veya manuel) bittikten sonra projeyi çalıştırmak için **iki ayrı terminal (CMD veya VS Code Terminali)** açmalı ve proje dizinindeyken şu iki komutu ayrı ayrı girmelisiniz:

**1. Terminalde (Frontend için):**
```bash
npm run dev
```
**2. Terminalde (Backend Sunucusu için):**
```bash
php artisan serve
```

Bu işlemin ardından tarayıcınızdan `http://localhost:8000` (veya serve'in verdiği portta) adresine giderek sistemi kullanmaya başlayabilirsiniz. Sistemdeki verileri yönetmek için adresin sonuna `/admin` (veya belirlediğiniz Filament yolu) yazarak panele ulaşabilirsiniz.

---

### Geliştirici
Geliştirildi ve tasarlandı. Herhangi bir sorunda `kurulum.bat` dosyası ile kurulumu her zaman sıfırlayabilirsiniz.
