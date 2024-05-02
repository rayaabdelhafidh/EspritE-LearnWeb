<?php
// src/Twig/GoogleMapExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GoogleMapExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('generateGoogleMapLink', [$this, 'generateGoogleMapLink']),
        ];
    }

    public function generateGoogleMapLink(string $location): string
    {
        $formattedLocation = urlencode($location);
        return "https://www.google.com/maps/search/?api=1&query={$formattedLocation}";
    }
}
