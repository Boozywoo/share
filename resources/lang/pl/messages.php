<?php

return [
    'admin' => [
        'auth' => [
            'login' => 'Autoryzacja przebiegła pomyślnie!',
        ],
        'companies' => [
            'statuses' => [
                'disabled' => 'Firmy nie można zrobić nieaktywnej, autobusy stoją w harmonogramie',
            ],
        ],
        'drivers' => [
            'statuses' => [
                'disabled' => 'Kierowcy nie można zrobić nieaktywne, warto w harmonogramie',
            ],
        ],
        'cities' => [
            'statuses' => [
                'disabled' => 'Miasto nie dezaktywuj. On mianowany na przystankach',
            ],
        ],
        'stations' => [
            'statuses' => [
                'disabled' => 'Przystanek można zrobić nieaktywnej, podana w kierunku',
            ],
        ],
        'tours' => [
            'delete' => [
                'error' => 'Na ten lot już zbroi. Usunięcie nie jest możliwe!',
            ],
        ],
        'schedules' => [
            'delete' => [
                'error' => 'U tego harmonogramu jest zbroi. Usunięcie nie jest możliwe!',
            ],
        ],
        'buses' => [
        ],
        'order' => [
            'client_loaded' => 'Klient pomyślnie załadowany',
            'client_created' => 'Klient zostanie utworzony',
            'stop_updated' => 'Przystanek przeglad zrobiono',

            'client_blacklisted' => 'Klient jest na czarnej liście - sprawdź status',
            'order_deleted' => 'Rezerwacja usunięty',
            'for_children' => 'Najlepsze dla dzieci',
        ],
    ],

    'index' => [
        'order' => [
            'expired' => 'Czas do rezerwacji minął.',

            'error' => 'Błąd. Odśwież stronę!', 
            'promo_not_found' => 'Nie znaleziono kodu promocyjnego',
            'promo_success' => 'Pomyślnie zastosowano kod promocyjny',
            'error_two' => 'Błąd. Skontaktuj się z operatorem',
            'not_available' => 'Ten postój nie jest dostępny dla ',
            'from' => 'lądowanie',
            'to' => 'zejście ze statku',
            'city_from' => 'Nie wybrano miasta docelowego',
            'city_to' => 'Nie wybrano miasta docelowego',
            'empty_tours' => 'Nie znaleziono bezpłatnych lotów w dniu ',
        ],
    ],
];