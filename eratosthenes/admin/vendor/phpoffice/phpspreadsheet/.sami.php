<?php
/**
 * File: .sami.php
 * Description: Handles .sami operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = __DIR__ . '/src');
$versions = GitVersionCollection::create($dir)
    ->addFromTags(function ($version) {
        return preg_match('~^\d+\.\d+\.\d+$~', $version);
    })
    ->add('master');

return new Sami($iterator, [
    'title' => 'PhpSpreadsheet',
    'versions' => $versions,
    'build_dir' => __DIR__ . '/build/%version%',
    'cache_dir' => __DIR__ . '/cache/%version%',
    'remote_repository' => new GitHubRemoteRepository('PHPOffice/PhpSpreadsheet', dirname($dir)),
]);
