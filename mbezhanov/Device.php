<?php

namespace Bezhanov\Faker\Provider;

use Faker\Provider\Base;

class Device extends Base
{
    protected static $modelName = ["Apple iPhone 12", "Apple iPhone 12 mini", "Apple iPhone 11", "Apple iPhone 13", "Apple iPhone 13 Pro", "Apple iPhone 13 mini", "Apple iPhone 13 Pro Max", "Apple iPhone SE", "Samsung Galaxy Z Flip3 5G", "Samsung Galaxy Z Fold3 5G", "Samsung Galaxy S21 Ultra 5G", "Samsung Galaxy S21+ 5G", "Samsung Galaxy S21 5G", "Samsung Galaxy Note20", "Samsung Galaxy Note20 Ultra 5G"];
    protected static $platform = ["Android OS", "webOS", "iOS", "BlackBerry", "Danger OS", "Android", "Firefox OS", "Ubuntu Touch", "Windows Phone", "Windows 8", "Windows RT", "Windows 8.1", "Windows 10", "Windows 10 Mobile", "Windows Phone"];
    protected static $manufacturer = ["Dell", "HP", "ASUS", "Acer", "Lenovo", "Apple"];
    protected static $serial = ["pEekWH7zGxVITv6NTa5KHjLSwr5Ie4", "UVr864F8zUbyYOAUd4cFOW9hpsZuGn", "Kl2ZroV9a", "m6aHiiHOc", "hHhDJaHCO", "SJMZOmtU0csrv4R", "PTIA6Ff3GBvGh3j", "hrR8nflThDDaSXO", "OezkV3nTii0sMK0", "T6UuMUTani3VGY4vXGia", "NjGU0z33pgE4sTEED7VR", "05skEogwZlX7j6twhhXX", "ToFVWLzGTJhQxAaJlDDn", "ejfjnRNInxh0363JC2WM", "xC36G3Xy4n2Fu90keaW96c1Hw5QBJX", "CdNevWfqDPQw4iJgUhtyCqwCggV12T", "9vxM9fCsG9nXg8EjTN5ygV2LvaDZdG", "39gPmcOKpwhDezLdiIOZ7SH89Pbjp4", "Yr9Vt13BlgvXO9zgJTPuCLv6F82r5S", "trDuJXhT8PnD3JEtw4lsluEuYSn1Xh", "VMTnd2mMQWvjbtNcZh7UIdULKb1mMo"];

    public function deviceBuildNumber(): int
    {
        return $this->generator->numberBetween(1, 500);
    }

    public function deviceManufacturer(): string
    {
        return static::randomElement(static::$manufacturer);
    }

    public function deviceModelName(): string
    {
        return static::randomElement(static::$modelName);
    }

    public function devicePlatform(): string
    {
        return static::randomElement(static::$platform);
    }

    public function deviceSerialNumber(): string
    {
        return static::randomElement(static::$serial);
    }

    public function deviceVersion(): int
    {
        return $this->generator->numberBetween(1, 1000);
    }
}


