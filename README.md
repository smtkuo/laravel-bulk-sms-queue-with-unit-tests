
# Bulk SMS Projesi

Bu Laravel projesi, büyük miktarda SMS mesajını tek bir istekle göndermeyi sağlayan bir Bulk SMS uygulamasını içerir. Bu proje, Laravel'in güçlü yeteneklerini kullanarak geliştirilmiştir ve JWT kimlik doğrulaması ile kullanıcıları korumaktadır.

## Özellikler

- Kullanıcı kaydı ve girişi
- JWT kimlik doğrulaması
- Kullanıcı detaylarını alma
- Tek SMS gönderme
- Toplu SMS gönderme
- SMS raporlarını alma
- SMS raporu detaylarını alma

## Gereksinimler

- PHP 8.1 veya daha yeni bir sürüm
- Composer
- Laravel 10.x
- Laravel Sanctum (JWT kimlik doğrulama için)
- Diğer bağımlılıklar (Composer ile kurulur)

## Kurulum

1. Bu projeyi klonlayın:

   ```bash
   git clone https://github.com/smtkuo/laravel-bulk-sms-queue-with-unit-tests
   ```

2. Proje dizinine gidin:

   ```bash
   cd laravel-bulk-sms-queue-with-unit-tests
   ```

3. Composer bağımlılıklarını yükleyin:

   ```bash
   composer install
   ```

4. .env dosyasını oluşturun ve veritabanı bağlantı bilgilerinizi ayarlayın:

   ```bash
   cp .env.example .env
   ```

5. Uygulama anahtarını (app key) oluşturun:

   ```bash
   php artisan key:generate
   ```

6. Veritabanını oluşturun ve tabloyu göçerleyin:

   ```bash
   php artisan migrate
   ```

7. Uygulamayı başlatın:

   ```bash
   php artisan serve
   ```

8. Tarayıcınızda `http://localhost:8000` adresini açarak uygulamayı kullanmaya başlayabilirsiniz.

## Kullanım

Bu projeyi kullanmak için aşağıdaki adımları takip edebilirsiniz:

1. Kaydolun veya giriş yapın.
2. Kullanıcı detaylarınızı görüntüleyin.
3. Tek SMS veya toplu SMS gönderin.
4. SMS raporlarını ve rapor detaylarını görüntüleyin.

## Laravel Jobları Çalıştırma Komutu

Bu Laravel projesinde, Laravel'ın iş sırası (queue) sistemini kullanarak jobları çalıştırmak için kullanılan bir komut bulunmaktadır. Bu komut, arkaplanda çalışan işleri tetiklemek için kullanılır ve çeşitli görevleri otomatize etmek için faydalıdır.

Laravel jobları çalıştırmak için aşağıdaki komutu kullanabilirsiniz:

php artisan queue:work

Bu komut, jobları işlemek ve sıraya alınmış işleri çalıştırmak için kullanılır. Çalıştırıldığında, iş sırası üzerinde bekleyen işleri işleyecektir.


## Bulk SMS Gönderme Komutu

Bu Laravel konsol komutu, toplu SMS gönderme işlemini gerçekleştirir. Komut, belirli bir kullanıcıya belirtilen sayıda SMS göndermek için kullanılır.

Aşağıda komutun nasıl kullanılacağına dair örnek bir komut bulunmaktadır:

php artisan sms:send-bulk {userId} {count=500}

- {userId}: SMS'leri göndermek istediğiniz kullanıcının kimliği.
- {count}: Gönderilecek SMS sayısı (varsayılan olarak 500).

Örneğin, aşağıdaki komut, kullanıcı kimliği 1 olan bir kullanıcıya 1000 SMS göndermek için kullanılabilir:

php artisan sms:send-bulk 1 1000

Bu komut, belirtilen kullanıcıya belirtilen sayıda rastgele SMS göndermek için SmsService servisini kullanır. Başarılı bir şekilde gönderildiğinde, "Bulk SMS sent successfully." mesajını döndürür; aksi takdirde, "Failed to send bulk SMS." hatası alınır.

## Swagger API Belgeleri

Bu proje, Swagger ile belgelenmiş bir API içerir. API belgelerine erişmek ve API'yi test etmek için aşağıdaki adımları izleyebilirsiniz:

1. Swagger UI'yi Açma: API belgelerine Swagger UI aracılığıyla erişebilirsiniz. Tarayıcınızda aşağıdaki URL'yi ziyaret edin:

   http://localhost:8000/

   Not: URL'yi proje yapılandırmasına göre güncelleyin.

2. API Belgelerini İnceleme: Swagger UI sayfası, API'nizin tüm rotalarını, istek ve yanıtlarını ayrıntılı bir şekilde gösterir. Buradan API'nizin nasıl kullanılacağına dair bilgilere erişebilirsiniz.

3. API'yi Test Etme: Swagger UI sayfası, API rotalarını doğrudan test etmek için kullanabilirsiniz. İstekler oluşturabilir ve yanıtları görüntüleyebilirsiniz.

API belgelerini kullanarak projenizin API'sini daha iyi anlamak ve kullanmak için gereken bilgilere erişebilirsiniz.

Daha fazla bilgi ve detaylı API belgelerine erişim için Swagger UI'yi kullanabilirsiniz.

## Katkıda Bulunma

Eğer bu projeye katkıda bulunmak isterseniz, lütfen bir çatal (fork) oluşturun ve pull isteği (pull request) gönderin. Her türlü katkıya açığız!

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için [LİSANS](LICENSE) dosyasına göz atabilirsiniz.