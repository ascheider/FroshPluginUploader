<?php declare(strict_types=1);

namespace FroshPluginUploader\Tests\Components;

use FroshPluginUploader\Components\PluginZip;
use FroshPluginUploader\Components\ZipStrategy\GitStrategy;
use FroshPluginUploader\Components\ZipStrategy\PlainStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;

class PluginZipTest extends TestCase
{
    public function testZipShopware6Plugin(): void
    {
        $service = new PluginZip();
        $service->zip(dirname(__DIR__) . '/fixtures/plugins/ShopwarePlatformPlugin', new PlainStrategy(), new NullOutput());
        static::assertFileExists(getcwd() . '/ShopwarePlatformPlugin.zip');
        unlink(getcwd() . '/ShopwarePlatformPlugin.zip');
    }

    public function testZipShopware6PluginWithoutOtherDeps(): void
    {
        $service = new PluginZip();
        $service->zip(dirname(__DIR__) . '/fixtures/plugins/ShopwarePlatformPluginComposer', new PlainStrategy(), new NullOutput());
        static::assertFileExists(getcwd() . '/ShopwarePlatformPlugin.zip');
        unlink(getcwd() . '/ShopwarePlatformPlugin.zip');
    }

    public function testGit(): void
    {
        $service = new PluginZip();
        $path = dirname(__DIR__) . '/fixtures/plugins/ShopwarePlatformPluginComposer';

        exec('cd ' . $path . '; git init; git add .');
        exec('cd ' . $path . '; git config user.name Test');
        exec('cd ' . $path . '; git config user.email test@test.de');
        exec('cd ' . $path . '; git commit -m "Test"');

        $service->zip($path, new GitStrategy(null), new NullOutput());
        static::assertFileExists(getcwd() . '/ShopwarePlatformPlugin-master.zip');
        unlink(getcwd() . '/ShopwarePlatformPlugin-master.zip');

        exec('rm -rf ' . $path . '/.git');
    }
}