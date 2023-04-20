# Refactoring time

## Cel

* poprawa jakości kodu

## Krok po kroku

1. CONTROLLER
   * wyniesienie bloku try-catch do rodzica
2. DB
   * refaktoryzacja metod
   * zmiana Database na klasę Modelu
   * utworzenie klasy abstrakcyjnej modelu
     * metody pobierające dane query i gueryAll
   * wprowadyenie interfejsu
3. Dodanie widoku obsługującego błędy
   * łapanie błędów w metodzie run()