# Template PDF conversion

Deploy `config/documents.php`, `app/Services/LibreOfficePdfConverter.php` and the updated `ClientController.php`.

`auto` keeps Microsoft Word on Windows and uses LibreOffice on Linux. DOCX downloads do not require either converter.

On Ubuntu/Debian with administrator access, install Writer:

```sh
sudo apt-get update
sudo apt-get install -y libreoffice-writer fonts-liberation
command -v libreoffice
```

Set the live environment (use the actual executable path from the command above):

```dotenv
DOCUMENT_PDF_DRIVER=libreoffice
LIBREOFFICE_BINARY=/usr/bin/libreoffice
DOCUMENT_CONVERSION_TIMEOUT=90
```

Rebuild Laravel configuration after deployment:

```sh
php artisan config:cache
```

The PHP worker needs permission to execute LibreOffice, `proc_open` enabled, and a writable system temporary directory. Each conversion has its own private working directory and LibreOffice profile, removed afterwards. Set PHP/web-server request timeouts above the conversion timeout.

Test a client template PDF through the live application, including its headers, footers, tables and fonts. LibreOffice pagination can differ from Microsoft Word; install the template fonts where licensed and available. This converter has not been verified on the live host.

For shared hosting without process execution or package installation, ask the provider whether LibreOffice Writer is supported. Otherwise use DOCX downloads or a separate document-conversion server; this code cannot install a converter on a restricted host.

LibreOffice command-line reference: https://help.libreoffice.org/latest/en-US/text/shared/guide/start_parameters.html
