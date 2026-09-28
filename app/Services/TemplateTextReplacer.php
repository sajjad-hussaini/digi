<?php
namespace App\Services;

class TemplateTextReplacer
{
    public function replaceDocument(string $content, string $find, string $replacement): array
    {
        return $this->replaceManyDocument($content, [$find => $replacement]);
    }

    public function replaceManyDocument(string $content, array $replacements): array
    {
        if (!$replacements || array_key_exists('', $replacements)) throw new \InvalidArgumentException('Enter the text to find.');
        $path = tempnam(sys_get_temp_dir(), 'template_replace_');
        if ($path === false) throw new \RuntimeException('Unable to prepare the template.');
        $zip = new \ZipArchive();
        $opened = false;
        try {
            file_put_contents($path, $content);
            if ($zip->open($path) !== true) throw new \RuntimeException('Please upload a valid DOCX document.');
            $opened = true;
            if ($zip->locateName('word/document.xml') === false) throw new \RuntimeException('Please upload a valid DOCX document.');
            $count = 0;
            $counts = array_fill_keys(array_keys($replacements), 0);
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $name = $zip->getNameIndex($index);
                if (!preg_match('#^word/(document|header\d+|footer\d+|footnotes|endnotes)\.xml$#', $name)) continue;
                $xml = $zip->getFromIndex($index);
                $updated = $this->replaceXml($xml, $replacements, $count, $counts);
                if ($updated !== $xml && !$zip->addFromString($name, $updated)) throw new \RuntimeException('Unable to update the template.');
            }
            $closed = $zip->close();
            $opened = false;
            if (!$closed) throw new \RuntimeException('Unable to save the template.');
            return [$count ? file_get_contents($path) : $content, $count, $counts];
        } finally {
            if ($opened) $zip->close();
            unlink($path);
        }
    }

    public function replaceXml(string $xml, array $replacements, int &$count = 0, array &$counts = []): string
    {
        // Escape literal ampersands in template text without double-encoding XML entities.
        $xml = preg_replace('/&(?!(?:amp|lt|gt|quot|apos|#\d+|#x[0-9a-fA-F]+);)/', '&amp;', $xml);
        $document = new \DOMDocument();
        $document->preserveWhiteSpace = true;
        if (!$document->loadXML($xml, LIBXML_NONET)) {
            throw new \RuntimeException('The template contains invalid Word XML.');
        }
        $xpath = new \DOMXPath($document);
        $changed = false;
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        foreach ($xpath->query('//w:p') as $paragraph) {
            $nodes = [];
            $text = '';
            foreach ($xpath->query('.//w:t', $paragraph) as $node) {
                $nodes[] = ['node' => $node, 'start' => strlen($text), 'length' => strlen($node->textContent)];
                $text .= $node->textContent;
            }
            $pattern = '/' . implode('|', array_map(static fn ($key) => preg_quote($key, '/'), array_keys($replacements))) . '/';
            preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
            // Work backwards so offsets remain valid when replacement lengths differ.
            foreach (array_reverse($matches[0]) as [$key, $start]) {
                $changed = true;
                $count++;
                $counts[$key] = ($counts[$key] ?? 0) + 1;
                $end = $start + strlen($key);
                foreach ($nodes as $entry) {
                    $nodeStart = $entry['start'];
                    $nodeEnd = $nodeStart + $entry['length'];
                    if ($nodeEnd <= $start || $nodeStart >= $end) {
                        continue;
                    }
                    $node = $entry['node'];
                    $value = substr($node->textContent, 0, max(0, $start - $nodeStart))
                        . ($start >= $nodeStart ? (string) $replacements[$key] : '')
                        . substr($node->textContent, min($entry['length'], $end - $nodeStart));
                    $node->textContent = $value;
                    $node->setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
                }
            }
        }

        return $changed ? $document->saveXML() : $xml;
    }

}
