<?php

// Datos bancarios para el pago por transferencia.
// Rellena estos valores en el archivo .env (no se inventan aquí).
// Si 'iban' queda vacío, la sección de datos bancarios no se muestra.

return [
    'holder' => env('BANK_HOLDER', 'Leñas El Molar C.B.'),
    'iban' => env('BANK_IBAN', ''),
    'bic' => env('BANK_BIC', ''),
    'bank_name' => env('BANK_NAME', ''),
];
