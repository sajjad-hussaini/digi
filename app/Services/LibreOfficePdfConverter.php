<?php
namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class LibreOfficePdfConverter
{
    public function convert(string $docxFile, string $pdfFile): void
    {
        $binary = config('documents.libreoffice_binary');
        if (!$binary || !is_file($binary) || !is_executable($binary)) {
            throw new \RuntimeException('PDF conversion requires LibreOffice Writer on this server. Install it and set LIBREOFFICE_BINARY to its executable path. DOCX download is still available.');
        }
        if (!function_exists('proc_open')) {
            throw new \RuntimeException('PDF conversion requires proc_open to be enabled by your hosting provider. DOCX download is still available.');
        }
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'docx_pdf_' . bin2hex(random_bytes(16));
        if (!mkdir($directory, 0700)) throw new \RuntimeException('Unable to create the PDF conversion directory.');
        try {
            $input = $directory . DIRECTORY_SEPARATOR . 'document.docx';
            if (!copy($docxFile, $input)) throw new \RuntimeException('Unable to prepare the document for PDF conversion.');
            $profile = str_replace('\\', '/', $directory . '/profile');
            $profileUri = 'file://' . (str_starts_with($profile, '/') ? '' : '/')
                . implode('/', array_map('rawurlencode', explode('/', $profile)));
            $process = new Process([
                $binary, '-env:UserInstallation=' . $profileUri,
                '--headless', '--nologo', '--nodefault', '--norestore',
                '--convert-to', 'pdf:writer_pdf_Export', '--outdir', $directory, $input,
            ]);
            $process->setTimeout(max(1, (int) config('documents.conversion_timeout', 90)));
            $process->run();
            $output = $directory . DIRECTORY_SEPARATOR . 'document.pdf';
            if (!$process->isSuccessful() || !is_file($output) || file_get_contents($output, false, null, 0, 5) !== '%PDF-') {
                throw new \RuntimeException('LibreOffice could not convert the template to PDF. Check the Writer installation and server permissions.');
            }
            if (!copy($output, $pdfFile)) throw new \RuntimeException('Unable to save the converted PDF.');
        } finally {
            (new Filesystem())->deleteDirectory($directory);
        }
    }
}
