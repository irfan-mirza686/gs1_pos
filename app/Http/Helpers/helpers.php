<?php


if (!function_exists('formatArabicText')) {
    function formatArabicText($text)
    {
        // Regular expression to match Arabic numerals
        $arabicNumeralsPattern = '/[\x{0660}-\x{0669}]/u';

        // Separate Arabic numerals from the text
        $parts = preg_split($arabicNumeralsPattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        // Format Arabic text and leave Arabic numerals unchanged
        foreach ($parts as &$part) {
            if (!preg_match($arabicNumeralsPattern, $part)) {
                // Use the ArPHP library for formatting Arabic text
                $arabic = new \ArPHP\I18N\Arabic();
                $part = $arabic->utf8Glyphs($part);
            }
        }

        // Convert Arabic numerals to English numerals in the whole text
        $formattedText = implode('', $parts);
        $formattedText = convertArabicNumeralsToEnglish($formattedText);

        return $formattedText;
    }
}
function convertArabicNumeralsToEnglish($text)
{
    // Mapping of Arabic numerals to English numerals
    $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $englishNumerals = range(0, 9);

    // Replace Arabic numerals with English numerals
    $convertedText = str_replace($arabicNumerals, $englishNumerals, $text);

    return $convertedText;
}
/****************************************************************************/
function uploadImage($image, $path, $old = null)
{

    $isDirectoryMade = makeDirectory($path);

    if (!$isDirectoryMade)
        throw new Exception('Directory could not made');

    // $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
    $filename = uniqid() . time();



    if ($image->getClientOriginalExtension() == 'gif') {
        copy($image->getRealPath(), $path . '/' . $filename);
    } else {

        // $imageIntervention = Image::make($image);
        $image = Image::make($image)->encode('webp', 90);

        if ($image->width() > 400) {
            $image->fit(400);
        } else {
            $image->resize($image->width(400, 400), $image->height(400, 400));
        }


        if ($old) {
            @unlink($path . '/' . $old);
        }

        $image->save($path . '/' . $filename . '.webp');
    }

    return $image->basename;
}
/****************************************************************************/
function makeDirectory($path)
{
    if (file_exists($path))
        return true;
    return mkdir($path, 0755, true);
}
/****************************************************************************/

function filePath($folder_name)
{
    return public_path('assets/uploads/' . $folder_name);
}

function getFile($folder_name, $filename)
{

    return asset('assets/uploads/'.$folder_name.'/'.$filename);
}
