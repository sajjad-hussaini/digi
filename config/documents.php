<?php

return [
    // auto: Microsoft Word on Windows, LibreOffice on other platforms.
    'pdf_driver' => env('DOCUMENT_PDF_DRIVER', 'auto'),
    'libreoffice_binary' => env('LIBREOFFICE_BINARY', '/usr/bin/libreoffice'),
    'conversion_timeout' => (int) env('DOCUMENT_CONVERSION_TIMEOUT', 90),
];
