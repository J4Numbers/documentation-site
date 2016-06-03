<?php

$home_dir = getcwd().'/';

//Boot in our required classes
require_once $home_dir . '/vendor/autoload.php';
require_once $home_dir . '/classes/TwigFile.php';

use m4numbers\Twig\TwigFile;

//These are all the available pages we can serve to the user,
// excluding master.twig, which is a thing we don't want the user
// to ever see.
$avail = array_diff( scandir('./documents/'), array('.', '..'));

//If that thing turned out to be false, then it means that we've
// got an issue in that there are no files there, or the directory
// is completely inaccessible (or something)
if ($avail === false)
{
    die('Sorry, something has gone terribly wrong...');
}

//This is more a check to see that the .htaccess is behaving itself,
// but if there is no 'mode' set at all, then it's not, and we must
// cry.
if (!isset($_GET['mode']))
{
    die('Sorry, something has gone terribly wrong with htaccess...');
}

$fnf = false;

//If no file was set at all...
if ($_GET['mode'] == '')
{
    //We can just serve them the index page and have done with it
    $twig = new TwigFile('index.twig', $home_dir);
    $act = 'index';
    $p = 'Index';
    $build = './';
    $parts = array();
}
else
{
    $parts = explode('/', $_GET['mode']);
    $built = './';
    $i = 0;

    do
    {
        if ($i < sizeof($parts))
        {
            $a = array_search(strtolower($parts[$i]), $avail);

            //And if they tried to access a page which didn't exist at all...
            if ($a == false)
            {
                //We point them towards the nice 404 file and run away
                $twig = new TwigFile('404.twig', $home_dir);
                $act = '404';
                $p = '404 - File Not Found';
                $fnf = true;
                break;
            }

            $build .= '/' . $parts[$i];
            ++$i;

            $avail = array_diff(scandir('./documents/' . $build), array('.', '..'));
        }
        else
        {
            if (is_file('./documents/' . $build))
            {
                //We can serve that file through twig
                $twig = new TwigFile('specific.twig', $home_dir);
                $act = 'specific';
            }
            else
            {
                $twig = new TwigFile('directory.twig', $home_dir);
                $act = 'index';
                $build .= '/';
                $avail = array_diff(scandir('./documents/' . $build), array('.', '..'));
            }
            $file = $build;
            $p =  ucwords(strtolower(str_replace('-', ' ', explode(".md", $parts[$i -1])[0])));
            break;
        }
    } while (true);
}

//Now we get our title from our file name, which has either been set above
// if we're on the index page or a 404, but for anything else, we strip out
// any dashes (which we replace with spaces), and capitalise every word
if (!isset($p))
{
    $p = ucwords(strtolower(str_replace('-', ' ', $_GET['mode'])));
}

//Then we compile all our renderables (most of which aren't actually necessary
// at this point, but have been left in because this is mostly legacy code
$general = array(
    'title' => $p,
    'description' => 'The home of the internet nobody: M4Numbers. This site '
                    .'contains documentation details for the various projects'
                    .' that he has taken part in at some point or another.',
    'location' => 'http://doc.m4numbers.co.uk/',
    'base_location' => 'http://doc.m4numbers.co.uk/',
);

$twig->addRenderable('general', $general);

if ($fnf) {
    die($twig->getFinishedTemplate());
}

$additional = array();

@include_once $home_dir . '/modes/' . $act . '.php';

foreach ($additional as $key => $val) {
    $twig->addRenderable($key, $val);
}

echo $twig->getFinishedTemplate();
