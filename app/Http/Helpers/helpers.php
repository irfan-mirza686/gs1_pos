<?php


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
