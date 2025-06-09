# FiberPay PHP

Oficjalna biblioteka PHP do integracji z bramką płatności FiberPay.

## Spis treści

- [FiberPay PHP](#fiberpay-php)
  - [Spis treści](#spis-treści)
  - [Instalacja](#instalacja)
  - [Wymagania](#wymagania)
  - [Konfiguracja](#konfiguracja)
    - [Generowanie kluczy API](#generowanie-kluczy-api)
    - [Inicjalizacja klienta](#inicjalizacja-klienta)
  - [Użycie](#użycie)
    - [FiberSplit - Podział płatności](#fibersplit---podział-płatności)
      - [Tworzenie zamówienia split](#tworzenie-zamówienia-split)
      - [Dodawanie pozycji do podziału](#dodawanie-pozycji-do-podziału)
      - [Finalizacja definicji podziału](#finalizacja-definicji-podziału)
      - [Pobieranie informacji](#pobieranie-informacji)
    - [FiberCollect - Zbieranie płatności](#fibercollect---zbieranie-płatności)
      - [Tworzenie kolekcji](#tworzenie-kolekcji)
      - [Dodawanie pozycji do kolekcji](#dodawanie-pozycji-do-kolekcji)
      - [Zarządzanie kolekcją](#zarządzanie-kolekcją)
    - [FiberDirect - Płatności bezpośrednie](#fiberdirect---płatności-bezpośrednie)
      - [Tworzenie płatności bezpośredniej](#tworzenie-płatności-bezpośredniej)
      - [Zarządzanie płatnością](#zarządzanie-płatnością)
    - [FiberForward - Przekazywanie płatności](#fiberforward---przekazywanie-płatności)
    - [Rozliczenia](#rozliczenia)
  - [Środowiska](#środowiska)
    - [Środowisko produkcyjne](#środowisko-produkcyjne)
    - [Środowisko testowe](#środowisko-testowe)
  - [Bezpieczeństwo](#bezpieczeństwo)
    - [Podpisywanie żądań](#podpisywanie-żądań)
    - [Dobre praktyki](#dobre-praktyki)
  - [Obsługa błędów](#obsługa-błędów)
  - [Wsparcie](#wsparcie)
  - [Licencja](#licencja)

## Instalacja

Bibliotekę można zainstalować za pomocą Composera:

```bash
composer require fiberpay/fiberpay-php
```

## Wymagania

- PHP >= 7.0.0
- Rozszerzenie cURL
- Konto FiberPay z wygenerowanymi kluczami API

## Konfiguracja

### Generowanie kluczy API

1. Zaloguj się do panelu FiberPay:
   - Środowisko produkcyjne: https://fiberpay.pl
   - Środowisko testowe: https://test.fiberpay.pl

2. Przejdź do sekcji ustawień API i wygeneruj parę kluczy:
   - **Klucz jawny (API Key)** - używany do identyfikacji
   - **Klucz tajny (API Secret)** - używany do podpisywania żądań (przechowuj go bezpiecznie!)

### Inicjalizacja klienta

```php
<?php
require_once 'vendor/autoload.php';

use FiberPay\FiberPayClient;

// Środowisko produkcyjne
$client = new FiberPayClient('TWÓJ_API_KEY', 'TWÓJ_API_SECRET');

// Środowisko testowe
$clientTest = new FiberPayClient('TWÓJ_API_KEY', 'TWÓJ_API_SECRET', true);
```

## Użycie

### FiberSplit - Podział płatności

FiberSplit umożliwia automatyczny podział płatności pomiędzy wielu odbiorców.

#### Tworzenie zamówienia split

```php
// Utworzenie nowego zamówienia split
$response = $client->createSplit('PLN', 'metadata_opcjonalna');
$splitOrder = json_decode($response, true);
$orderCode = $splitOrder['code'];
```

#### Dodawanie pozycji do podziału

```php
// Dodanie pierwszego odbiorcy
$response = $client->addSplitItem(
    $orderCode,
    'Jan Kowalski',
    'PL61109010140000071219812874',
    'Płatność za usługę A',
    1500.00,
    'PLN',
    'https://example.com/callback',
    ['orderId' => '12345'],
    'metadata_pozycji'
);

// Dodanie drugiego odbiorcy
$response = $client->addSplitItem(
    $orderCode,
    'Anna Nowak',
    'PL27114020040000300201355387',
    'Płatność za usługę B',
    500.00,
    'PLN'
);
```

#### Finalizacja definicji podziału

```php
// Zakończenie definiowania podziału
$response = $client->endDefinitionOfSplit($orderCode);
```

#### Pobieranie informacji

```php
// Pobranie szczegółów zamówienia split
$response = $client->getSplit($orderCode);
$splitDetails = json_decode($response, true);

// Pobranie szczegółów konkretnej pozycji
$response = $client->getSplitItem($orderItemCode);
$itemDetails = json_decode($response, true);
```

### FiberCollect - Zbieranie płatności

FiberCollect pozwala na zbieranie płatności od wielu płatników na jedno konto.

#### Tworzenie kolekcji

```php
// Utworzenie nowej kolekcji płatności
$response = $client->createCollect(
    'Firma XYZ Sp. z o.o.',
    'PL61109010140000071219812874',
    'PLN',
    'metadata_kolekcji'
);
$collectOrder = json_decode($response, true);
$orderCode = $collectOrder['code'];
```

#### Dodawanie pozycji do kolekcji

```php
// Dodanie pozycji do zapłaty
$response = $client->addCollectItem(
    $orderCode,
    'Faktura FV/2024/001',
    2500.00,
    'PLN',
    'https://example.com/callback',
    ['invoiceId' => 'FV/2024/001'],
    'metadata_pozycji',
    'https://example.com/success'
);
$item = json_decode($response, true);
$paymentUrl = $item['paymentUrl']; // Link do płatności dla klienta
```

#### Zarządzanie kolekcją

```php
// Pobranie informacji o kolekcji
$response = $client->getCollectOrderInfo($orderCode);

// Pobranie informacji o pozycji
$response = $client->getCollectOrderItemInfo($orderItemCode);

// Usunięcie pozycji z kolekcji
$response = $client->deleteCollectOrderItem($orderItemCode);
```

### FiberDirect - Płatności bezpośrednie

FiberDirect służy do prostych płatności bezpośrednich.

#### Tworzenie płatności bezpośredniej

```php
$response = $client->createDirect(
    'Sklep ABC',
    'PL61109010140000071219812874',
    'Zamówienie #12345',
    999.99,
    'PLN',
    'https://example.com/callback',
    ['orderId' => '12345'],
    'metadata_platnosci'
);
$directOrder = json_decode($response, true);
$orderCode = $directOrder['code'];
```

#### Zarządzanie płatnością

```php
// Pobranie szczegółów płatności
$response = $client->getDirectOrderInfo($orderCode);

// Anulowanie płatności
$response = $client->deleteDirectOrder($orderCode);
```

### FiberForward - Przekazywanie płatności

FiberForward umożliwia przekazywanie płatności przez pośrednika (brokera).

```php
$response = $client->createForward(
    'Odbiorca końcowy',              // targetName
    'PL61109010140000071219812874',  // targetIban
    'Broker Płatności Sp. z o.o.',   // brokerName
    'PL27114020040000300201355387',  // brokerIban
    'Przekazanie płatności',         // description
    1000.00,                         // sourceAmount
    950.00,                          // targetAmount (po potrąceniu prowizji)
    'PLN',
    'https://example.com/callback',
    ['transactionId' => 'TRX123'],
    'metadata',
    'https://example.com/redirect',
    'Informacja przed płatnością',
    'Informacja po płatności'
);

// Pobranie informacji o przekazaniu
$response = $client->getForwardOrderInfo($orderCode);
```

### Rozliczenia

```php
// Pobranie listy wszystkich rozliczeń
$response = $client->getSettlements();
$settlements = json_decode($response, true);

// Pobranie szczegółów konkretnego rozliczenia
$response = $client->getSettlement($settlementCode);
$settlementDetails = json_decode($response, true);
```

## Środowiska

Biblioteka obsługuje dwa środowiska:

### Środowisko produkcyjne
- API URL: `https://api.fiberpay.pl`
- Panel: https://fiberpay.pl

### Środowisko testowe
- API URL: `https://apitest.fiberpay.pl`
- Panel: https://test.fiberpay.pl

Środowiska są całkowicie rozdzielone. Klucze API z jednego środowiska nie działają w drugim.

## Bezpieczeństwo

### Podpisywanie żądań

Biblioteka automatycznie podpisuje wszystkie żądania używając HMAC-SHA512. Każde żądanie zawiera następujące nagłówki:

- `X-API-Key` - Twój klucz API
- `X-API-Nonce` - Unikalny znacznik czasowy
- `X-API-Route` - Metoda HTTP i ścieżka
- `X-API-Signature` - Podpis HMAC-SHA512

### Dobre praktyki

1. **Nigdy nie udostępniaj klucza tajnego (API Secret)**
2. Przechowuj klucze API w zmiennych środowiskowych
3. Używaj środowiska testowego do rozwoju i testów
4. Implementuj właściwą obsługę błędów
5. Weryfikuj callbacki webhooków

## Obsługa błędów

Biblioteka rzuca wyjątki w przypadku błędów HTTP 5xx. Zalecana obsługa:

```php
try {
    $response = $client->createDirect(...);
    $order = json_decode($response, true);
    
    if (isset($order['error'])) {
        // Obsługa błędu aplikacji
        echo "Błąd: " . $order['error']['message'];
    } else {
        // Sukces
        echo "Utworzono zamówienie: " . $order['code'];
    }
} catch (\Exception $e) {
    // Obsługa błędu serwera
    echo "Błąd serwera: " . $e->getMessage();
}
```

## Wsparcie

- Dokumentacja API: https://github.com/fiberpay/api
- Zgłaszanie problemów: https://github.com/fiberpay/fiberpay-php/issues
- Kontakt: support@fiberpay.pl

## Licencja

Biblioteka jest udostępniona na licencji MIT. Szczegóły w pliku [LICENSE](LICENSE).

---

Copyright (c) 2020 FiberPay sp. z o.o. (https://fiberpay.pl)