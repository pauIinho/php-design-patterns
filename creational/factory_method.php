<?php

use JetBrains\PhpStorm\Pure;

/**
 * Class BulletinBoardPoster
 */
abstract class BulletinBoardPoster
{
    /**
     * @return BulletinBoardServiceConnector
     */
    abstract public function getBulletinBoardService(): BulletinBoardServiceConnector;

    /**
     * @param AdContent $content
     */
    public function post(AdContent $content): void
    {
        $bulletinBoardService = $this->getBulletinBoardService();

        $bulletinBoardService->login();
        $bulletinBoardService->placeAd($content);
        $bulletinBoardService->logout();
    }
}

/**
 * Class AvitoPoster
 */
class AvitoPoster extends BulletinBoardPoster
{
    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $password;

    /**
     * AvitoPoster constructor.
     * @param string $phone
     * @param string $password
     */
    public function __construct(string $phone, string $password)
    {
        $this->phone = $phone;
        $this->password = $password;
    }

    /**
     * @return BulletinBoardServiceConnector
     */
    public function getBulletinBoardService(): BulletinBoardServiceConnector
    {
        return new AvitoConnector($this->phone, $this->password);
    }
}

/**
 * Class VKClassifiedsPoster
 */
class VKClassifiedsPoster extends BulletinBoardPoster
{
    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * VKClassifiedsPoster constructor.
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return BulletinBoardServiceConnector
     */
    public function getBulletinBoardService(): BulletinBoardServiceConnector
    {
        return new VKClassifiedsConnector($this->email, $this->password);
    }
}

/**
 * Interface BulletinBoardServiceConnector
 */
interface BulletinBoardServiceConnector
{
    public function login(): void;

    public function placeAd($content): void;

    public function logout(): void;
}

/**
 * Class AvitoConnector
 */
class AvitoConnector implements BulletinBoardServiceConnector
{
    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $password;

    /**
     * AvitoConnector constructor.
     * @param string $phone
     * @param string $password
     */
    public function __construct(string $phone, string $password)
    {
        $this->phone = $phone;
        $this->password = $password;
    }

    public function login(): void
    {
        echo "Send HTTP API request to log in user $this->phone with " .
            "password $this->password\n";
    }

    public function placeAd($content): void
    {
        echo "Send HTTP API request to place an ad\n";
    }

    public function logout(): void
    {
        echo "Send HTTP API request to log out user $this->phone\n";
    }
}

/**
 * Class VKClassifiedsConnector
 */
class VKClassifiedsConnector implements BulletinBoardServiceConnector
{
    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * VKClassifiedsConnector constructor.
     * @param string $phone
     * @param string $password
     */
    public function __construct(string $phone, string $password)
    {
        $this->email = $phone;
        $this->password = $password;
    }

    public function login(): void
    {
        echo "Send HTTP API request to log in user $this->email with " .
            "password $this->password\n";
    }

    public function placeAd($content): void
    {
        echo "Send HTTP API request to place an ad\n";
    }

    public function logout(): void
    {
        echo "Send HTTP API request to log out user $this->email\n";
    }
}

/**
 * Class AdContent
 */
class AdContent
{
    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var int
     */
    public int $price;

    /**
     * @var array
     */
    public array $photos = [];

    /**
     * @var string
     */
    public string $phone;
}

/**
 * @param BulletinBoardPoster $creator
 */
function clientCode(BulletinBoardPoster $creator)
{
    $content = new AdContent();
    $content->title = 'Ноутбук HP Pavillion Gaming';
    $content->description = 'Продам игровой ноутбук HP в отличном состоянии. Без торга';
    $content->price = 75000;
    $content->phone = '+7909*******';
    $content->photos = [
        '/home/user/images/1.jpg',
        '/home/user/images/2.jpg',
    ];

    $creator->post($content);
}

echo "Place an ad to Avito:\n";
clientCode(new AvitoPoster('+7909*******', '******'));

echo "Place an ad to VK Classifieds:\n";
clientCode(new VKClassifiedsPoster('test@mail.ru', '******'));